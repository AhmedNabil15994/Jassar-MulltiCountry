<?php
 
namespace Modules\Apps\Components\Dukaan\Home;
 
use Illuminate\View\Component;
use Modules\Apps\Repositories\Frontend\AppHomeRepository as Home;

class HomeBuilder extends Component
{
 
    public $home_sections;
    /**
     * Create the component instance.   
     *
     */
    public function __construct()
    {
        $home = new Home;
        $this->home_sections = $home->getAll();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.home.builder');
    }
}