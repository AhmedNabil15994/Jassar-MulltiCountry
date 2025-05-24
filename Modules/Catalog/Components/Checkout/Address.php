<?php
 
namespace Modules\Catalog\Components\Checkout;
 
use Illuminate\View\Component;

class Address extends Component
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
        $type = auth()->check() ? 'user' : 'guest';
        return view("catalog::dukaan.components.checkout.{$type}-address");
    }
}