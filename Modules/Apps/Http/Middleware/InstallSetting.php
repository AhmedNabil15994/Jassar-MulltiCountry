<?php

namespace Modules\Apps\Http\Middleware;

use Closure;
use Setting;

class InstallSetting
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
        // if(!Setting::get('setting_installed')){
        //     return redirect()->route('dashboard.install.index');
        // }
        return $next($request);
    }
}
