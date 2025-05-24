<?php
 
namespace Modules\Catalog\Components;
 
use Illuminate\View\Component;
use Modules\Apps\Enums\AppHomeDisplayType;

class Category extends Component
{
 
    public $category;
    /**
     * Create the component instance.   
     *
     */
    public function __construct($category)
    {
        $this->category = $category;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view("catalog::dukaan.components.category");
    }
}