<?php
 
namespace Modules\User\Components;
 
use Illuminate\View\Component;

class UserMenu extends Component
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
        return view("user::dukaan.components.user-menu");
    }
}