<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts\HeaderMenu\CategoryList;
 
use Illuminate\View\Component;

class MegaMenu extends Component
{
    public $categories;
    public $ids;
    public $first;

    /**
     * Create the component instance.
     *
     */
    public function __construct($categories,$ids,$first)
    {
        $this->first = $first;
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
        return view('apps::dukaan.components.layouts.header-menu.category-list.mega-menu');
    }
}