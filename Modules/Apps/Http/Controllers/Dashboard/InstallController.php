<?php

namespace Modules\Apps\Http\Controllers\Dashboard;

use Illuminate\Support\Facades\Artisan;
use Modules\Apps\Http\Requests\Dashboard\Install\{SaveGeneralInfoRequest,SaveCountrySetupRequest,SaveLogoRequest};
use Modules\Area\Entities\Country;
use Modules\Setting\Repositories\Dashboard\SettingRepository as Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Setting as SettingModel;
use Nnjeim\World\Actions\SeedCustomTableSeeder;

class InstallController extends Controller
{
    protected $setting;
    function __construct(Setting $setting){

        $this->setting = $setting;
    }

    public function index(Request $request)
    {
        if(session()->has('installing_steps_active_route') && session('installing_steps_active_route') != 'dashboard.install.index'){
            return redirect()->route(session('installing_steps_active_route'));
        }else{

            session()->put('installing_steps_active_route','dashboard.install.index');
        }
        return view('apps::dashboard.install.new');
    }

    public function saveGeneralInfo(SaveGeneralInfoRequest $request)
    {
        try {
            $this->setting->set($request);
            session()->put('installing_steps_active_route','dashboard.install.country-setup');
            return Response()->json([true, __('apps::dashboard.messages.created'),'url' => route('dashboard.install.country-setup')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function countrySetup(Request $request)
    {
        return view('apps::dashboard.install.country-setup');
        if(session()->has('installing_steps_active_route') && session('installing_steps_active_route') == 'dashboard.install.country-setup'){
            
            return view('apps::dashboard.install.country-setup');
        }else{

            return redirect()->route(session('installing_steps_active_route') ?? 'dashboard.install.index');
        }
    }

    public function saveCountrySetup(SaveCountrySetupRequest $request)
    {
        try {
            $this->setting->set($request);
            // ini_set('memory_limit', '-1');
            
            $country = Country::find($request->default_country);
            if($country && $country->instaled == 0){
                
                Artisan::call("install:country {$request->default_country}");
                (new SeedCustomTableSeeder())->run();

                $country->installed = 1;
                $country->save();
            }

            session()->put('installing_steps_active_route','dashboard.install.logos-setup');
            return Response()->json([true, __('apps::dashboard.messages.created'),'url' => route('dashboard.install.logos-setup')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function logoSetup(Request $request)
    {
        if(session()->has('installing_steps_active_route') && session('installing_steps_active_route') == 'dashboard.install.logos-setup'){
            
            return view('apps::dashboard.install.logos-setup');
        }else{

            return redirect()->route(session('installing_steps_active_route') ?? 'dashboard.install.index');
        }
    }

    public function saveLogoSetup(SaveLogoRequest $request)
    {
        try {
            $this->setting->set($request);

            session()->put('installing_steps_active_route','dashboard.install.complated');

            return Response()->json([true, __('apps::dashboard.messages.created'),'url' => route('dashboard.install.complated')]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function complated(Request $request)
    {
        if(session()->has('installing_steps_active_route') && session('installing_steps_active_route') == 'dashboard.install.complated'){
            session()->forget('installing_steps_active_route');
            SettingModel::set('setting_installed', 1);
            return view('apps::dashboard.install.complete');
        }else{

            return redirect()->route(session('installing_steps_active_route') ?? 'dashboard.install.index');
        }
    }
}
