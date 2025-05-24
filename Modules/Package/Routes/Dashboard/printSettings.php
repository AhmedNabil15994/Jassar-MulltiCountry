<?php

Route::group(['prefix' => 'print-settings'], function () {
    Route::get('/', 'PrintSettingController@index')
        ->name('dashboard.print-settings.index');

    Route::get('datatable', 'PrintSettingController@datatable')
        ->name('dashboard.print-settings.datatable');

    Route::get('create', 'PrintSettingController@create')
        ->name('dashboard.print-settings.create');

    Route::post('/', 'PrintSettingController@store')
        ->name('dashboard.print-settings.store');

    Route::get('{id}/edit', 'PrintSettingController@edit')
        ->name('dashboard.print-settings.edit');

    Route::put('{id}', 'PrintSettingController@update')
        ->name('dashboard.print-settings.update');

    Route::delete('{id}', 'PrintSettingController@destroy')
        ->name('dashboard.print-settings.destroy');

    Route::get('deletes', 'PrintSettingController@deletes')
        ->name('dashboard.print-settings.deletes');

    Route::get('{id}', 'PrintSettingController@show')
        ->name('dashboard.print-settings.show');
});

Route::group(['prefix' => 'print'], function () {

  	Route::get('/' ,'PrintController@index')
  	->name('dashboard.print.index');

     Route::any('/render-print' ,'PrintController@renderPrint')
     ->name('dashboard.print.render.print');
});