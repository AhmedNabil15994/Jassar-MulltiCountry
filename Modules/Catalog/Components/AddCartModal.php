<?php
 
namespace Modules\Catalog\Components;
 
use Illuminate\View\Component;
use Modules\Catalog\Transformers\Frontend\ProductResource;

class AddCartModal extends Component
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
        return view("catalog::dukaan.components.add-cart-modal");
    }
}