<?php

Route::prefix('{countryPrefix}')->group(function (){

    /*
    |================================================================================
    |                             Back-END ROUTES
    |================================================================================
    */
    Route::prefix('dashboard')->middleware(['dashboard.auth','dashboard.install','permission:dashboard_access'])->group(function () {

        foreach (File::allFiles(module_path('Shipping', 'Routes/Dashboard')) as $file) {
            require_once($file->getPathname());
        }

    });

    // /*
    // |================================================================================
    // |                             FRONT-END ROUTES
    // |================================================================================
    // */
    Route::prefix('/')->group(function () {

        foreach (File::allFiles(module_path('Shipping', 'Routes/FrontEnd')) as $file) {
            require_once($file->getPathname());
        }

    });
});
