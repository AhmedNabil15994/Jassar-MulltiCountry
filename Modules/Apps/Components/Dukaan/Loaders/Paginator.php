<?php
 
namespace Modules\Apps\Components\Dukaan\Loaders;
 
use Illuminate\View\Component;

class Paginator extends Component
{
 
    public $paginator;
    /**
     * Create the component instance.
     *
     */
    public function __construct($paginator)
    {
        $this->paginator = $paginator;
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.loaders.paginator');
    }
}