<?php

namespace Modules\Transaction\Services;

use Modules\Transaction\Services\PaymentInterface;

class KnetPaymentService implements PaymentInterface
{
    // TEST CREDENTIALS
    protected $MERCHANT_ID    = "1201";
    protected $USERNAME              = "test";
    protected $PASSWORD          = "test";
    protected $API_KEY        = "jtest123";
    protected $URL            = "https://api.upayments.com/test-payment";
    protected $TEST_MOD       = 1;

    // LIVE CREDENTIALS
    // const MERCHANT_ID    = "1201";
    // const USERNAME            = "test";
    // const PASSWORD        = "test";
    // const API_KEY        = "jtest123";

    public function __construct()
    {
        $this->extraMerchantsData                   = [];
        // if (config("app.env") == "production") {
        //     $this->API_KEY =  password_hash($this->API_KEY, PASSWORD_BCRYPT);
        //     $this->TEST_MOD = 0;

        //     $this->extraMerchantsData['amounts'][0]        = $order['total'];
        //     $this->extraMerchantsData['charges'][0]        = 0.300;
        //     $this->extraMerchantsData['chargeType'][0]     = 'fixed';
        //     $this->extraMerchantsData['cc_charges'][0]     = 2.7;
        //     $this->extraMerchantsData['cc_chargeType'][0] = 'percentage';
        //     $this->extraMerchantsData['ibans'][0]          = 'KW29BBYN0000000000000554495001';
        // }
    }

    public function send($order, $type = "api-order", $payment = "knet")
    {
        if (! isset($order['success_url']) && ! isset($order['error_url'])) {
            $url = $this->paymentUrls($type);
        }

        $extraMerchantsData = [];
        if (! config('services.upayments.test_mode')) {
            $this->URL = 'https://api.upayments.com/payment-request';
            $this->API_KEY =  password_hash($this->API_KEY, PASSWORD_BCRYPT);
            $this->TEST_MOD = 0;

            $extraMerchantsData['amounts'][0]        = $order['total'];
            $extraMerchantsData['charges'][0]        = 0.350;
            $extraMerchantsData['chargeType'][0]     = 'fixed';
            $extraMerchantsData['cc_charges'][0]     = 2.7;
            $extraMerchantsData['cc_chargeType'][0] = 'percentage';
            $extraMerchantsData['ibans'][0]          = config('services.upayments.iban', config('setting.payment_gateway.upayment.IBAN', ''));
        }

        $fields = [
                // 'api_key'            => password_hash(self::EMAIL_MYFATOORAH,PASSWORD_BCRYPT),
                'api_key'               => $this->API_KEY,
                'merchant_id'           =>  $this->MERCHANT_ID,
                'username'              => $this->USERNAME,
                'password'              => stripslashes($this->PASSWORD),
                'order_id'              => $order['id'],
                'CurrencyCode'      => 'KWD', //only works in production mode
                'CstFName'              => $order["name"] ??  'null',
                'CstEmail'              => $order["email"] ?? 'null',
                'CstMobile'             => $order["mobile"] ? str_replace("-", "", $order["mobile"]) : 'null',
                'success_url'       => $order['success_url'] ?? $url["success"],
                'error_url'             => $order['error_url'] ?? $url["failed"],
                'test_mode'         => $this->TEST_MOD, // test mode enabled
                'whitelabled'       => true, // only accept in live credentials (it will not work in test)
                'payment_gateway'   => $payment,// knet / cc
                'total_price'           => $order["total"] ,
                'ExtraMerchantsData'    => json_encode($extraMerchantsData),

                'reference' => $order['id'],
                // 'notifyURL' => url(route('frontend.orders.webhooks')),
            ];

        // dd($fields);

        $fields_string = http_build_query($fields);
        // dd($fields  , $fields_string);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $server_output = json_decode($server_output, true);
        if ($server_output["status"] == "errors") {
            throw new \Exception($server_output["error_msg"], 502);
        }

        return $server_output['paymentURL'];
    }

    public function paymentUrls($type)
    {
        if ($type == 'api-order') {
            $url['success'] = url(route('api.payment.success'));
            $url['failed']  = url(route('api.payment.failed'));
        }

        if ($type == 'frontend-order') {
            $url['success'] = url(route('frontend.payment.success'));
            $url['failed']  = url(route('frontend.payment.failed'));
        }

        return $url;
    }


    public function getResultForPayment($data, $type = "api-order", $payment = "knet")
    {
        $order["id"]     = $data["id"];
        $order["total"]  = $data["total"];
        $order["name"]   = optional($data->user)->name ?? "" ;
        $order["email"]  = optional($data->user)->email ?? "" ;
        $order["mobile"] = optional($data->user)->phone_code . "" . optional($data->user)->mobile;
        return $this->send($order, $type, $payment);
    }
}