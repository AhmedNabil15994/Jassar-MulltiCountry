<?php
if((setting('other.selling_on_site') ?? 1) == 1){

    Route::group(['prefix' => 'checkout'], function () {



        Route::get('/', 'FrontEnd\CheckoutController@index')
            ->name('frontend.checkout.index')
            ->middleware(['empty.cart']);

        Route::post('save-checkout-information', 'FrontEnd\CheckoutController@saveCheckoutInformation')
            ->name('frontend.checkout.save_checkout_information')
            ->middleware(['empty.cart']);

        Route::post('get-state-delivery-price', 'FrontEnd\CheckoutController@getStateDeliveryPrice')
            ->name('frontend.checkout.get_state_delivery_price')
            ->middleware(['empty.cart']);


        Route::post('/', 'FrontEnd\CheckoutController@createAddress')
            ->name('frontend.checkout.createAddress')
            ->middleware(['empty.cart']);

        Route::get('/payment', 'FrontEnd\CheckoutController@complete')
            ->name('frontend.checkout.complete')
            ->middleware(['empty.cart']);


        Route::get('/getCities/{countryId}', 'FrontEnd\CheckoutController@getCities')
            ->name('frontend.checkout.getCities')
            ->middleware(['empty.cart']);
    });
}
