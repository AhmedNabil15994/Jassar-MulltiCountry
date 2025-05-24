<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/status', function (Request $request) {
    $tenant = app('currentTenant');

    return [
        'tenant_id' => $tenant->id,
        'website_url' => url('/'),
        'api_base_url' => (App::environment('local') ? 'http' : 'https')
            . '://' . $request->getHost() . '/api/',
            // . "://{$tenant->subdomain}.toucart.wip/api",
        'app_type' => $tenant->accountType->slug,
        'app_version' => App::version(),
        'db_batch' => DB::table('migrations')->latest('id')->first()->batch,
        'installed' => $tenant->installed(),
    ];
});
