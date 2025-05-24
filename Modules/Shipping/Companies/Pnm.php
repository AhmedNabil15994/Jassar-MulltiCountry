<?php

namespace Modules\Shipping\Companies;

use Illuminate\Http\Request;
use ExtremeSa\Aramex\API\Classes\Address;
use Modules\Area\Entities\Country;
use Modules\Catalog\Traits\ShoppingCartTrait;
use Modules\User\Entities\Address as AddressModel;
use Modules\Shipping\Interfaces\ShippingTransactionsInterface;
use Setting;
use Cart;
use Modules\Order\Entities\Order;
use Modules\Shipping\Integrations\Pnm\PnmShipmentWay;

class Pnm implements ShippingTransactionsInterface
{
    use ShoppingCartTrait;

    public $country;
    private $pnm;

    function __construct()
    {
        $this->pnm = new PnmShipmentWay;
    }

    public function getCities(Request $request)
    {
        $data = $this->pnm->cities($this->country->iso2)->get();

        $items = array_map(function ($city) {
            $city['title'] = $city['name'];
            return $city;
        }, (array)$data);

        $items = [(object)[
            'id' => 1,
            'title' => $this->country->title,
            'states' => (object)$items
        ]];
        return $items;
    }

    public function validateAddress(Request $request, $address = null): array
    {
        $addressType = 'pnm';
        $this->address = $address;
        $jsonData = $this->getAddressObjectData($request, $address);

        return [false, 'addressType' => $addressType, 'jsonData' => $jsonData];
    }


    public function getAddressObjectData(Request $request, $object): array
    {
        $response = $object && optional($object)->json_data && count(optional($object)->json_data) ? (array)optional($object)->json_data : [];
        $response['city'] = $request->city_name;
        $response['state_id'] = $request->state_id;

        if ($this->country && !array_key_exists('country_id', $response))
            $response['country_id'] = $this->country->id;

        return $response;
    }

    public function getDeliveryPrice(Request $request, AddressModel $address = null, $userToken): object
    {
        $items = $this->getCartItems();
        $receiverId = $this->getReceiverId($request,$address);


        if (is_array($receiverId) && isset($receiverId['success']) && !$receiverId['success'])
            return response()->json($receiverId, 422);

        $data = $this->pnm->calculateRate($items,$this->getItemsWeight($items),$receiverId);

        if ($data->hasErrors())
            return response()->json(['success' => false, 'errors' => $data->getFirstError()], 422);
        
        $this->companyDeliveryChargeCondition($request, $data->get()['total_charging_amount'], $userToken);
        $condition = Cart::session($userToken)->getCondition('company_delivery_fees');
        $deliveryPrice = $condition != null ? $condition->getValue() : 0;

        $data = [
            'original_price' => priceWithCurrenciesCode($data->get()['total_charging_amount'],true,false),
            'price' => priceWithCurrenciesCode($data->get()['total_charging_amount']),
            'delivery_time' => '',
            'totalDeliveryPrice' => $deliveryPrice != 0 ? priceWithCurrenciesCode(number_format($deliveryPrice, 3)) : __('catalog::frontend.checkout.free_delivery'),
            'total' => priceWithCurrenciesCode(number_format(getCartTotal(), 3)),
        ];

        return response()->json(['success' => true, 'data' => $data,'cart' => getCart(null,true)]);
    }

    public function createShipment(Request $request, Order $order):void
    {
        $items = $this->getCartItems();
        $consigneeAddress = $order->orderAddress ?? $order->unknownOrderAddress;
        $receiverId = isset($consigneeAddress->json_data['receiver_id']) ? $consigneeAddress->json_data['receiver_id'] : $this->getReceiverId($request,$consigneeAddress);
        
        $shipment = $this->pnm->createShipments($this->getItemsWeight($items), $this->country->iso2, $receiverId, $items, (string)$order->id, priceWithCurrenciesCode(number_format(getCartSubTotal(), 3),true,false));

        if (!$shipment->hasErrors()){

            $shipment = $shipment->get()['data'];

            $order->shipmentTransactions()->create([
                'shipment_id' => $shipment['pnm_number'],
                'status' => $shipment['status'],
                'json_data' => $shipment,
                'type' => 'pnm',
            ]);
        }else{

            info($shipment->getErrors());
        }

    }

    public function cancelShipment(Order $order, $reason, $pnmId):void
    {   
        $cancelShipment = $this->pnm->cancelShipment($pnmId, $reason);

        if (!$cancelShipment->hasErrors()){

            $cancelShipment = $cancelShipment->get()['data'];
            
            $order->shipmentCancel()->create([
                'shipment_id' => $pnmId,
                'status' => $cancelShipment['status'],
                'reason' => $reason,
                'response' => $cancelShipment,
                'type' => 'pnm',
            ]);
        }
    }

    protected function getCartItems(){
        $items = [];
        foreach (getCartContent() as $item) {
            array_push($items, [
                'hs_code' => optional($item->attributes->product)->sku,
                'qty' => $item['quantity'],
                'rate' => 5,
                'price' => (float)priceWithCurrenciesCode($item['price'] , true , false),
                'name' => $item['name'],
                'weight' => optional(optional($item->attributes->product)->shipment)['weight'] ?? 0.1,
                'package_type' => 'Box',
            ]);
        }
        
        return $items;
    }

    protected function getItemsWeight($items){
        $weight = 0;
        foreach ($items as $item) {
            $weight += $item['weight'] * $item['qty'];
        }
        
        return $weight;
    }

    protected function getReceiverId($request,$address){
        
        if ($address) {
            $json_data = $address->json_data;
            if(!isset($json_data['receiver_id'])){

                $receiver = $this->pnm->createAddress(
                    $address->username,
                    (string)$address->mobile,
                    $address->email ?? 'guest@guest.com',
                    $this->country->iso2,
                    (isset($address->json_data['city']) ? $address->json_data['city'] : 'unknown'),
                    600000,
                    $address->line_one
                );
    
                if ($receiver->hasErrors()) {
    
                    return ['success' => false, 'errors' => $receiver->getFirstError()];
                }
    
                $receiverId = isset($receiver->get()['data']['id']) ? $receiver->get()['data']['id'] : null;
                if($receiverId){
                    $json_data['receiver_id'] = $receiverId;
                    $address->json_data = $json_data;
                    $address->save();
                }
            }else{

                $receiverId = $json_data['receiver_id'];
            }
        }else{

            $receiver = $this->pnm->createAddress(
                'Guest',
                '+'.$request->phone_code.$request->mobile,
                'guest@guest.com',
                $this->country->iso2,
                $request->city_name,
                60000,
                $request->city_name
            );
            
            if ($receiver->hasErrors()) {
                
                return ['success' => false, 'errors' => $receiver->getFirstError()];
            }

            $receiverId = isset($receiver->get()['data']['id']) ? $receiver->get()['data']['id'] : null;
        }

        return $receiverId;
    }

    private function buildAddressObject($address): object
    {
        $jsonData = $address->json_data;

        $country = Country::active()->findOrFail($jsonData['country_id']);
        $address = (new Address())
            ->setCountryCode($country->iso2)
            ->setCity($jsonData['state_id'])
            ->setLine1($jsonData['line1'])
            ->setBuildingNumber($jsonData['buildingNumber'])
            ->setDescription($jsonData['description']);

        return $address;
    }

    private function getSourceAddress(): int
    {
        $model = Setting::get('shiping.pnm.mode');
        return Setting::get("shiping.pnm.{$model}.shipper_id");
    }
}
