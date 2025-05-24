<?php

use Illuminate\Support\Facades\Route;

    Route::prefix('packages')->group(function () {
        Route::get('/' ,'Dashboard\PackageController@index')
            ->name('dashboard.packages.index')
            ->middleware(['permission:show_packages']);

        Route::get('datatable'	,'Dashboard\PackageController@datatable')
            ->name('dashboard.packages.datatable')
            ->middleware(['permission:show_packages']);

        Route::get('create'		,'Dashboard\PackageController@create')
            ->name('dashboard.packages.create')
            ->middleware(['permission:add_packages']);

        Route::post('/'			,'Dashboard\PackageController@store')
            ->name('dashboard.packages.store')
            ->middleware(['permission:add_packages']);

        Route::get('{id}/edit'	,'Dashboard\PackageController@edit')
            ->name('dashboard.packages.edit')
            ->middleware(['permission:edit_packages']);

        Route::put('{id}'		,'Dashboard\PackageController@update')
            ->name('dashboard.packages.update')
            ->middleware(['permission:edit_packages']);

        Route::delete('{id}'	,'Dashboard\PackageController@destroy')
            ->name('dashboard.packages.destroy')
            ->middleware(['permission:delete_packages']);

        Route::get('deletes'	,'Dashboard\PackageController@deletes')
            ->name('dashboard.packages.deletes')
            ->middleware(['permission:delete_packages']);

        Route::get('{id}','Dashboard\PackageController@show')
            ->name('dashboard.packages.show')
            ->middleware(['permission:show_packages']);
    });

    Route::prefix('offers')->group(function () {
        Route::get('/' ,'Dashboard\OfferController@index')
            ->name('dashboard.offers.index')
            ->middleware(['permission:show_offers']);

        Route::get('datatable'	,'Dashboard\OfferController@datatable')
            ->name('dashboard.offers.datatable')
            ->middleware(['permission:show_offers']);

        Route::get('create'		,'Dashboard\OfferController@create')
            ->name('dashboard.offers.create')
            ->middleware(['permission:add_offers']);

        Route::post('/'			,'Dashboard\OfferController@store')
            ->name('dashboard.offers.store')
            ->middleware(['permission:add_offers']);

        Route::get('{id}/edit'	,'Dashboard\OfferController@edit')
            ->name('dashboard.offers.edit')
            ->middleware(['permission:edit_offers']);

        Route::put('{id}'		,'Dashboard\OfferController@update')
            ->name('dashboard.offers.update')
            ->middleware(['permission:edit_offers']);

        Route::delete('{id}'	,'Dashboard\OfferController@destroy')
            ->name('dashboard.offers.destroy')
            ->middleware(['permission:delete_offers']);

        Route::get('deletes'	,'Dashboard\OfferController@deletes')
            ->name('dashboard.offers.deletes')
            ->middleware(['permission:delete_offers']);

        Route::get('{id}','Dashboard\OfferController@show')
            ->name('dashboard.offers.show')
            ->middleware(['permission:show_offers']);
    });

    Route::prefix('subscriptions')->as('subscriptions.')->group(function () {

        Route::get('/' ,'Dashboard\SubscriptionController@index')
            ->name('dashboard.subscriptions.index')
            ->middleware(['permission:show_subscriptions']);

        Route::get('datatable'	,'Dashboard\SubscriptionController@datatable')
            ->name('dashboard.subscriptions.datatable')
            ->middleware(['permission:show_subscriptions']);

        Route::get('create'		,'Dashboard\SubscriptionController@create')
            ->name('dashboard.subscriptions.create')
            ->middleware(['permission:add_subscriptions']);

        Route::post('/'			,'Dashboard\SubscriptionController@store')
            ->name('dashboard.subscriptions.store')
            ->middleware(['permission:add_subscriptions']);

        Route::get('{id}/edit'	,'Dashboard\SubscriptionController@edit')
            ->name('dashboard.subscriptions.edit')
            ->middleware(['permission:edit_subscriptions']);

        Route::put('{id}'		,'Dashboard\SubscriptionController@update')
            ->name('dashboard.subscriptions.update')
            ->middleware(['permission:edit_subscriptions']);

        Route::delete('{id}'	,'Dashboard\SubscriptionController@destroy')
            ->name('dashboard.subscriptions.destroy')
            ->middleware(['permission:delete_subscriptions']);

        Route::get('deletes'	,'Dashboard\SubscriptionController@deletes')
            ->name('dashboard.subscriptions.deletes')
            ->middleware(['permission:delete_subscriptions']);

        Route::get('{id}','Dashboard\SubscriptionController@show')
            ->name('dashboard.subscriptions.show')
            ->middleware(['permission:show_subscriptions']);

        Route::get('/today-orders', 'Dashboard\SubscriptionController@todayOrders')
            ->name('dashboard.subscriptions.today_orders')
            ->middleware(['permission:show_subscriptions']);

        Route::get('/today-orders/datatable', 'Dashboard\SubscriptionController@toDayDatatable')
            ->name('dashboard.subscriptions.today_orders.datatable')
            ->middleware(['permission:show_subscriptions']);

        Route::get('/print', 'Dashboard\SubscriptionController@print')
            ->name('dashboard.subscriptions.print')
            ->middleware(['permission:show_subscriptions']);

        Route::get('/getSubscriptionById/{id}', 'Dashboard\SubscriptionController@getSubscriptionById')
            ->name('dashboard.subscriptions.getSubscriptionById')
            ->middleware(['permission:show_subscriptions']);
    });

    Route::prefix('suspensions')->as('suspensions.')->group(function () {
        Route::get('/' ,'Dashboard\SuspensionController@index')
            ->name('dashboard.suspensions.index')
            ->middleware(['permission:show_suspensions']);

        Route::get('datatable'	,'Dashboard\SuspensionController@datatable')
            ->name('dashboard.suspensions.datatable')
            ->middleware(['permission:show_suspensions']);

        Route::get('create'		,'Dashboard\SuspensionController@create')
            ->name('dashboard.suspensions.create')
            ->middleware(['permission:add_suspensions']);

        Route::post('/'			,'Dashboard\SuspensionController@store')
            ->name('dashboard.suspensions.store')
            ->middleware(['permission:add_suspensions']);

        Route::get('{id}/edit'	,'Dashboard\SuspensionController@edit')
            ->name('dashboard.suspensions.edit')
            ->middleware(['permission:edit_suspensions']);

        Route::put('{id}'		,'Dashboard\SuspensionController@update')
            ->name('dashboard.suspensions.update')
            ->middleware(['permission:edit_suspensions']);

        Route::delete('{id}'	,'Dashboard\SuspensionController@destroy')
            ->name('dashboard.suspensions.destroy')
            ->middleware(['permission:delete_suspensions']);

        Route::get('deletes'	,'Dashboard\SuspensionController@deletes')
            ->name('dashboard.suspensions.deletes')
            ->middleware(['permission:delete_suspensions']);

        Route::get('{id}','Dashboard\SuspensionController@show')
            ->name('dashboard.suspensions.show')
            ->middleware(['permission:show_suspensions']);
    });

