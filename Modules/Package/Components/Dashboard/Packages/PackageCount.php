<?php
 
namespace Modules\Package\Components\Dashboard\Packages;
 
use Illuminate\View\Component;
use Modules\Category\Entities\Category;

class PackageCount extends Component
{

    public $packages, $categories, $subscriptionrelation;
    /**
     * Create the component instance.   
     *
     */
    public function __construct($packages, $subscriptionrelation)
    {
        $this->subscriptionrelation = $subscriptionrelation;
        $this->packages = $packages;
        $this->categories = Category::all();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("package::dashboard.subscriptions.components.subscription-count");
    }
}