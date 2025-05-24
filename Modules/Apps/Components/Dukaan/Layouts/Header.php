<?php
 
namespace Modules\Apps\Components\Dukaan\Layouts;
 
use Modules\Area\Entities\CurrencyCode;
use Illuminate\View\Component;
use Modules\Area\Repositories\FrontEnd\CountryRepository;
use Setting;
use Illuminate\Support\Facades\Cache;

class Header extends Component
{
    public $current_currency;
    public $current_country;
    public $supported_currencies;
    public $supported_countries;
    public $header_links;
    public $depth;
    private $parents_steps = [];
    public $tenantSubdomain;
 
    /**
     * Create the component instance.
     *
     */
    public function __construct()
    {
        $this->tenantSubdomain = app('currentTenant')->subdomain;
        $this->current_currency = currentCurrencyCode();
        $this->current_country = currentCountry();
        $this->supported_currencies = $this->getSupportedCurrencies();
        $this->supported_countries = $this->getSupportedCountries();
    }
 
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    { 
        return view('apps::dukaan.components.layouts.header');
    }

    private function getSupportedCurrencies(){
        
        if (!Cache::has("{$this->tenantSubdomain}_front_supported_currencies")) {
            $supported_currencies = optional(optional(CurrencyCode::whereIn('code', Setting::get('supported_currencies') ?? []))->pluck('code','id'))->toArray() ?? ['kw'];
            Cache::put("{$this->tenantSubdomain}_front_supported_currencies", $supported_currencies);
        }

        return Cache::get("{$this->tenantSubdomain}_front_supported_currencies");
    }

    private function getSupportedCountries(){

        return (new CountryRepository)->getAllSuported();
    }
}