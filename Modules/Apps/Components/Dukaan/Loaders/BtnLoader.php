<?php
 
namespace Modules\Apps\Components\Dukaan\Loaders;
 
use Illuminate\View\Component;

class BtnLoader extends Component
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
        return view('apps::dukaan.components.loaders.btn-loader');
    }
}