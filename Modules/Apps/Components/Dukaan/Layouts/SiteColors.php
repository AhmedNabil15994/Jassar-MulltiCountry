<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts;
 
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;
use Modules\DeveloperTools\Entities\SiteColor;

class SiteColors extends Component
{
    public $site_colors;
    /**
     * Create the component instance.
     *
     */
    public function __construct()
    {
        $tenantSubdomain = app('currentTenant')->subdomain;
        
        if (!Cache::has("{$tenantSubdomain}_front_site_colors")) {
            
            Cache::put("{$tenantSubdomain}_front_site_colors", optional(SiteColor::first())->css);
        }

        $this->site_colors =  Cache::get("{$tenantSubdomain}_front_site_colors");
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.layouts.site-colors');
    }
}