<?php

namespace Modules\Area\Repositories\FrontEnd;

use Illuminate\Http\Request;
use Modules\Area\Entities\Country;
use Modules\Shipping\Companies\Aramex;
use Setting;
use Illuminate\Support\Facades\Cache;
use Modules\Shipping\Traits\ShippingTrait;

class CountryRepository
{
    use ShippingTrait;
    protected $country;

    function __construct()
    {
        $this->country = new Country;
        $this->shipping = new Aramex();
    }

    public function getAllActive($order = 'id', $sort = 'desc')
    {
        $query = $this->country->active();
        // Get only supported countries from settings
        $query = $query->whereIn('id', setting('supported_countries') ?? []);
        return $query->orderBy($order, $sort)->get();
    }

    public function getAllSuported($order = 'id', $sort = 'desc')
    {
        $tenantSubdomain = app('currentTenant')->subdomain;
        if (!Cache::has("{$tenantSubdomain}_front_supported_countries")) {
            $supported_countries = $this->country->Active()
            ->whereIn('id',
            array_merge(
                Setting::get('supported_countries') ?? [],
                array_merge(
                    Setting::get('shiping.local.countries') ?? [],
                    Setting::get('shiping.aramex.countries') ?? []
                    )
            ))->orderBy($order, $sort)->get(['id','title','emoji','iso2','phone_code']);
            Cache::put("{$tenantSubdomain}_front_supported_countries", $supported_countries);
        }

        return Cache::get("{$tenantSubdomain}_front_supported_countries");
    }

    public function getAllSuportedWithCities($order = 'id', $sort = 'desc')
    {
        $tenantSubdomain = app('currentTenant')->subdomain;
        if (!Cache::has("{$tenantSubdomain}_front_supported_countries_with_cities")) {
            $supported_countries = $this->country->Active()->with(['cities' => function ($query) {
                $query->active();
            }])
            ->whereIn('id',
            array_merge(
                Setting::get('supported_countries') ?? [],
                array_merge(
                    Setting::get('shiping.local.countries') ?? [],
                    Setting::get('shiping.aramex.countries') ?? []
                    )
            ))->orderBy($order, $sort)->get();

            Cache::put("{$tenantSubdomain}_front_supported_countries_with_cities", $supported_countries);
        }

        return Cache::get("{$tenantSubdomain}_front_supported_countries_with_cities");
    }

    public function getCountriesWithCitiesAndStates($request = null, $order = 'id', $sort = 'asc')
    {
        $query = $this->country->Active()->with(['cities' => function ($q) {
            $q->Active();
            $q->with(['states' => function ($q) {
                $q->active();
            }]);
        }]);
        // Get only supported countries from settings
        $query = $query->whereIn('id', setting('supported_countries') ?? []);
        return $query->orderBy($order, $sort)->get();
    }

    public function getCitiesWithStatesByCountryId($countryId, $order = 'id', $sort = 'asc')
    {
        $request = new Request;
        $request->merge(['country_id' => $countryId]);
        $this->setShippingTypeByRequest($request);
        return $this->shipping->getCities($request);
    }

}
