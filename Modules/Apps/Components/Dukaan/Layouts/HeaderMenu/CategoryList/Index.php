<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts\HeaderMenu\CategoryList;
 
use Illuminate\View\Component;

class Index extends Component
{
 
    public $link;

    /**
     * Create the component instance.
     *
     */
    public function __construct($link)
    {
        $this->link = $link;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.layouts.header-menu.category-list.index');
    }
}