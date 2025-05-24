<?php
 
namespace Modules\Catalog\Components;
 
use Illuminate\View\Component;
use Modules\Catalog\Transformers\Frontend\ProductResource;

class ProductTag extends Component
{
 
    public $title;
    public $background;
    public $color;
    /**
     * Create the component instance.   
     *
     */
    public function __construct($title, $background = null, $color = null)
    {
        $this->title = $title;
        $this->background = $background;
        $this->color = $color;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("catalog::dukaan.components.product-tag");
    }
}