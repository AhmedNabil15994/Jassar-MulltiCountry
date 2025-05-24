<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\URL;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Area\Entities\Country;
use MongoDB\Driver\Session;

class CheckCountryPrefix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $basePref = getCountryCode();
        $localSession = LaravelLocalization::getCurrentLocale() != null ? LaravelLocalization::getCurrentLocale() : setting('default_locale');

        \URL::defaults(['countryPrefix' => $basePref]);
        $allowedPrefixes = ['kw','sa','eg','qa','om','bh'];

        $root = $request->getRequestUri();
        $params = $request->route()->parameters;
        LaravelLocalization::setLocale($localSession);

        $country_id = Country::active()->where('iso2','LIKE','%'.$basePref.'%')->first()->id;
        define('COUNTRY_ID',$country_id);
        define('COUNTRY_PREFIX',$basePref);
        $currency = \Nnjeim\World\Models\Currency::where('country_id',$country_id)->first();
        $currencyCode = $currency ? $currency->code : '';
        if($currencyCode != ''){
            define('CURRENCY',$currencyCode);
        }

        if(empty($params)){
            return redirect($localSession.'/'.$basePref);
        }

        if(isset($params['countryPrefix']) && !in_array($params['countryPrefix'],$allowedPrefixes)){
            return redirect($localSession.'/'.$basePref);
        }

        if(isset($params['countryPrefix']) && $basePref != $params['countryPrefix']){
            $url = $request->fullUrl();
            $url = str_replace($params['countryPrefix'],$basePref,$url);
            return redirect($url);
        }
        return $next($request);
    }
}
