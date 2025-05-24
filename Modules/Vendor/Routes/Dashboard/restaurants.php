<?php

Route::group(['prefix' => 'restaurants'], function () {

    /* Route::get('sorting', 'Dashboard\RestaurantController@sorting')
        ->name('dashboard.restaurants.sorting')
        ->middleware(['permission:show_restaurants']);

    Route::get('store/sorting', 'Dashboard\RestaurantController@storeSorting')
        ->name('dashboard.restaurants.store.sorting')
        ->middleware(['permission:show_restaurants']); */

    Route::get('/', 'Dashboard\RestaurantController@index')
        ->name('dashboard.restaurants.index')
        ->middleware(['permission:show_restaurants']);

    Route::get('datatable', 'Dashboard\RestaurantController@datatable')
        ->name('dashboard.restaurants.datatable')
        ->middleware(['permission:show_restaurants']);

    Route::get('create', 'Dashboard\RestaurantController@create')
        ->name('dashboard.restaurants.create')
        ->middleware(['permission:add_restaurants']);

    Route::post('/', 'Dashboard\RestaurantController@store')
        ->name('dashboard.restaurants.store')
        ->middleware(['permission:add_restaurants']);

    Route::get('{id}/edit', 'Dashboard\RestaurantController@edit')
        ->name('dashboard.restaurants.edit')
        ->middleware(['permission:edit_restaurants']);

    Route::put('{id}', 'Dashboard\RestaurantController@update')
        ->name('dashboard.restaurants.update')
        ->middleware(['permission:edit_restaurants']);

    Route::delete('{id}', 'Dashboard\RestaurantController@destroy')
        ->name('dashboard.restaurants.destroy')
        ->middleware(['permission:delete_restaurants']);

    Route::get('deletes', 'Dashboard\RestaurantController@deletes')
        ->name('dashboard.restaurants.deletes')
        ->middleware(['permission:delete_restaurants']);

    Route::get('{id}', 'Dashboard\RestaurantController@show')
        ->name('dashboard.restaurants.show')
        ->middleware(['permission:show_restaurants']);

    Route::get('{id}/products', 'Dashboard\RestaurantController@getAssignedProducts')
        ->name('dashboard.restaurants.get_assigned_products')
        ->middleware(['permission:add_restaurants']);

    Route::post('{id}/assign-products', 'Dashboard\RestaurantController@assignProducts')
        ->name('dashboard.restaurants.assign_products')
        ->middleware(['permission:add_restaurants']);

    Route::get('active/restaurants', 'Dashboard\RestaurantController@getAllActiverestaurants')
        ->name('dashboard.restaurants.get_all_active_restaurants');
});
