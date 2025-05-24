<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts\HeaderMenu;
 
use Illuminate\View\Component;

class FinalBuilder extends Component
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
        return view('apps::dukaan.components.layouts.header-menu.final-builder');
    }
}