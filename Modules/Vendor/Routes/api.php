<?php


Route::group(['prefix' => 'vendors', 'namespace' => 'WebService'], function () {

    Route::get('sections', 'VendorController@getSections')->name('api.vendors.sections');
    Route::get('list', 'VendorController@getVendors')->name('api.vendors.list');
    Route::get('categories', 'VendorController@getCategories')->name('api.vendors.categories');
    Route::get('/{id}', 'VendorController@getVendorById')->name('api.vendors.details');
    Route::get('vendor/delivery-times', 'VendorController@getVendorDeliveryTimes')->name('api.get_vendor_delivery_times');

    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {
        Route::post('rate', 'VendorController@vendorRate')->name('api.vendors.rate');
    });
});
