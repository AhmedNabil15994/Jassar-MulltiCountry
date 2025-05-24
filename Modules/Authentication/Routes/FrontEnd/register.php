<?php

Route::group(['prefix' => 'signup'], function () {
        // Show Register Form
        Route::get('/', 'FrontEnd\RegisterController@show')
        ->name('frontend.register')
        ->middleware('guest');

        // Submit Register
        Route::post('/', 'FrontEnd\RegisterController@register')
        ->name('frontend.post.register');
});
