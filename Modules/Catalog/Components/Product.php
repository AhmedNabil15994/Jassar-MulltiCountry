<?php
 
namespace Modules\Catalog\Components;
 
use Illuminate\View\Component;
use Modules\Catalog\Transformers\Frontend\ProductResource;

class Product extends Component
{
 
    public $product;
    /**
     * Create the component instance.   
     *
     */
    public function __construct($product)
    {
        $this->product = (object)(new ProductResource($product))->jsonSerialize();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("catalog::dukaan.components.product");
    }
}