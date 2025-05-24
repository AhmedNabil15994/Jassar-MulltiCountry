<?php
 
namespace Modules\Catalog\Components\Checkout;
 
use Illuminate\View\Component;

class CouponForm extends Component
{
    /**
     * Create the component instance.   
     *
     */
    public function __construct()
    {
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("catalog::dukaan.components.checkout.coupon-form");
    }
}