<?php
 
namespace Modules\Apps\Components\Dukaan\Home;
 
use Illuminate\View\Component;
use Modules\Apps\Enums\AppHomeDisplayType;

class Categories extends Component
{
 
    public $records;
    public $view;
    public $home;
    /**
     * Create the component instance.   
     *
     */
    public function __construct($home, $records)
    {
        $this->home = $home;
        $this->records = $records;
        $this->view = $home->display_type == AppHomeDisplayType::__default ? 'carousel' : 'grid';
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view("apps::dukaan.components.home.categories-{$this->view}");
    }
}