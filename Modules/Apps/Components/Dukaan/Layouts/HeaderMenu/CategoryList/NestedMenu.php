<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts\HeaderMenu\CategoryList;
 
use Illuminate\View\Component;

class NestedMenu extends Component
{
 
    public $categories;
    public $ids;

    /**
     * Create the component instance.
     *
     */
    public function __construct($categories,$ids)
    {
        $this->categories = $categories;
        $this->ids = $ids;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.layouts.header-menu.category-list.nsted-menu');
    }
}