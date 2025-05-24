<?php
 
namespace Modules\Order\Components;
 
use Illuminate\View\Component;

class OrderCard extends Component
{
    /**
     * Create the component instance.   
     *
     */
    public function __construct()
    {
        //
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("order::dukaan.orders.components.order-card");
    }
}