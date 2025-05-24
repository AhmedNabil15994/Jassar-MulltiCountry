<?php

Route::prefix('{countryPrefix}')->group(function (){

    /*
    |================================================================================
    |                             Back-END ROUTES
    |================================================================================
    */
    Route::prefix('dashboard')->middleware(['dashboard.auth','dashboard.install', 'permission:dashboard_access'])->group(function () {

        /*foreach (File::allFiles(module_path('Vendor', 'Routes/Dashboard')) as $file) {
            require($file->getPathname());
        }*/

        foreach (["payments.php", "sections.php", "vendor_statuses.php", "vendors.php", "categories.php", "delivery-charges.php"] as $value) {
            require(module_path('Vendor', 'Routes/Dashboard/' . $value));
        }
    });

    /*
    |================================================================================
    |                             FRONT-END ROUTES
    |================================================================================
    */
    Route::prefix('/')->group(function () {

        /*foreach (File::allFiles(module_path('Vendor', 'Routes/FrontEnd')) as $file) {
            require($file->getPathname());
        }*/

        foreach (["vendors.php"] as $value) {
            require(module_path('Vendor', 'Routes/FrontEnd/' . $value));
        }
    });
});
