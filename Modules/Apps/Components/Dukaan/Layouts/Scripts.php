<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts;
 
use Illuminate\View\Component;

class Scripts extends Component
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
        return view('apps::dukaan.components.layouts.scripts');
    }
}