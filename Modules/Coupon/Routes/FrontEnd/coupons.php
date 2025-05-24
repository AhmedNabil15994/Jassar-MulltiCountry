<?php

Route::group(['prefix' => 'coupons'], function () {

    Route::post('/check_coupon', 'FrontEnd\CouponController@checkCoupon')
        ->name('frontend.check_coupon');

    Route::post('/remove-coupon', 'FrontEnd\CouponController@removeCoupon')
        ->name('frontend.coupon.remove');


});
