<?php

use Illuminate\Support\Facades\Route;

Route::name('dashboard.install.')->namespace('Dashboard')->prefix('install')->group( function () {
    Route::middleware('dashboard.installed')->group( function () {
        //general info
        Route::get('/'	,'InstallController@index')->name('index');
        Route::post('/save-general-info'	,'InstallController@saveGeneralInfo')->name('save-general-info');

        //country-setup
        Route::get('/country-setup'	,'InstallController@countrySetup')->name('country-setup');
        Route::post('/country-setup'	,'InstallController@saveCountrySetup')->name('save-country-setup');

        //logos-setup
        Route::get('/logos-setup'	,'InstallController@logoSetup')->name('logos-setup');
        Route::post('/logos-setup'	,'InstallController@saveLogoSetup')->name('save-logos-setup');
    });

    //complated
    Route::get('/complated'	,'InstallController@complated')->name('complated');

});