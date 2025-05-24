<?php

Route::prefix('/packages')->name('frontend.packages.')->group(function () {
    Route::get('/', 'Frontend\PackageController@index')->name('index');

    Route::post('renew-subscribe', 'Frontend\PackageController@renew')->name('renew');
    Route::post('pause-subscription', 'Frontend\PackageController@pauseSubscription')->name('pause.subscription');

    Route::get('{slug}', 'Frontend\PackageController@show')->name('show');


    Route::get('{package}/subscribeForm', 'Frontend\PackageController@subscribeForm')->name('subscribeForm');
    Route::post('{package}/subscribe', 'Frontend\PackageController@subscribe')->name('subscribe');
});

Route::prefix('/offers')->name('frontend.offers.')->group(function () {
    Route::get('/', 'Frontend\OfferController@index')->name('index');
    Route::get('{slug}', 'Frontend\OfferController@show')->name('show');
});

//Route::group(function () {
//    Route::get('success', 'AfterPaidController@success')->name('frontend.subscriptions.success');
//    Route::get('failed', 'AfterPaidController@failed')->name('frontend.subscriptions.failed');
//    Route::get('notify', 'updatePaidStatus')->name('frontend.subscriptions.notify');
//});
