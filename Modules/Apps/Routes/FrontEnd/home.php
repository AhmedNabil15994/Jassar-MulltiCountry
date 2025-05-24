<?php

Route::get('/', 'FrontEnd\HomeController@index')->name('frontend.home');
Route::post('update-currency', 'FrontEnd\HomeController@updateCurrency')->name('frontend.update.currency');
Route::post('update-country', 'FrontEnd\HomeController@updateCountry')->name('frontend.update.country');
Route::get('/landing', 'FrontEnd\HomeController@landing')->name('frontend.landing');
// Route::get('/landing', 'FrontEnd\HomeController@landing')->name('frontend.landing');
Route::get('products/autocomplete', 'FrontEnd\HomeController@autocompleteProducts')->name('frontend.home.filter');
