<?php

Route::prefix('{countryPrefix}')->group(function (){

    /*
    |================================================================================
    |                             Back-END ROUTES
    |================================================================================
    */
    Route::prefix('dashboard')->middleware(['dashboard.auth','dashboard.install', 'permission:dashboard_access'])->group(function () {

        /*foreach (File::allFiles(module_path('Authorization', 'Routes/Dashboard')) as $file) {
            require($file->getPathname());
        }*/

        foreach (["permissions.php", "roles.php"] as $value) {
            require(module_path('Authorization', 'Routes/Dashboard/' . $value));
        }

    });
});
