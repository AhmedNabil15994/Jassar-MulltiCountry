<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Order\Entities\Order;

class FixProductsQuantity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Products qty from order if not paid in 15 minutes after creation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limitation = 15; // 15 minutes
        $orders = Order::whereIn('payment_status_id',[1,3])->where('rolled_back',0)->orderBy('id','desc')->get();
        foreach ($orders as $order){
            $date = strtotime($order->created_at) + $limitation * 60;
            $date = date('Y-m-d H:i:s',$date);
            if(date('Y-m-d H:i:s') > $date ){
                if(count($order->orderOffers)){
                    foreach ($order->orderOffers as $orderOffer){
                        $selected_products = json_decode($orderOffer['selected_products']);

                        if($selected_products){
                            foreach ($selected_products as $selected_product){
                                $free = $orderOffer->offer->freeProducts()->where('id',$selected_product->product_id)->first();
                                if (!is_null($free->qty) && intval($free->qty))
                                    $free->increment('qty',$selected_product->qty);
                            }
                        }

                        foreach ($orderOffer->offer->products as $product){
                            if (!is_null($product->qty) && intval($product->qty)){
                                $product->increment('qty',$orderOffer->offer->qty);
                            }
                        }
                    }
                }

                if(count($order->orderPackages)){
                    foreach ($order->orderPackages as $orderPackage){
                        $orderPackage->package()->increment('qty',1);
                        foreach ($orderPackage->package->products as $packageProduct) {
                            if (!is_null($packageProduct->qty) && intval($packageProduct->qty))
                                $packageProduct->increment('qty',1);
                        }
                    }
                }

                if(count($order->orderProducts)){
                    foreach ($order->orderProducts as $orderProduct){
                        $orderProduct->product()->increment('qty',$orderProduct->qty);
                    }
                }

                $order->rolled_back = 1;
                $order->save();
            }
        }
    }
}
