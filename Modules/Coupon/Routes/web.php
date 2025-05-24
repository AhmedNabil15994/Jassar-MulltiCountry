<?php

Route::prefix('{countryPrefix}')->group(function (){

    /*
    |================================================================================
    |                             Back-END ROUTES
    |================================================================================
    */
    Route::prefix('dashboard')->middleware(['dashboard.auth','dashboard.install', 'permission:dashboard_access'])->group(function () {

        /*foreach (File::allFiles(module_path('Coupon', 'Routes/Dashboard')) as $file) {
            require($file->getPathname());
        }*/

        foreach (["coupons.php"] as $value) {
            require(module_path('Coupon', 'Routes/Dashboard/' . $value));
        }

    });

    /*
    |================================================================================
    |                             FRONT-END ROUTES
    |================================================================================
    */
    Route::prefix('/')->group(function () {

        /*foreach (File::allFiles(module_path('Coupon', 'Routes/FrontEnd')) as $file) {
            require($file->getPathname());
        }*/

        foreach (["coupons.php"] as $value) {
            require(module_path('Coupon', 'Routes/FrontEnd/' . $value));
        }

    });
});
