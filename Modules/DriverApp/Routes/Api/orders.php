<?php

Route::post('/location', 'WebService\Orders\OrderController@driverLocation')->name('api.driver.orders.driver_location');
Route::group(['prefix' => 'orders', 'middleware' => 'IsDriver', 'namespace' => 'WebService\Orders'], function () {
    Route::get('list', 'OrderController@index')->name('api.driver.orders.index');
    Route::get('list/{id}', 'OrderController@show')->name('api.driver.orders.show');
    Route::post('update-order-by-driver/{id}', 'OrderController@updateOrderByDriver')->name('api.driver.orders.update_order_by_driver');
    Route::post('/{orderId}/trackOrder', 'OrderController@trackOrder')->name('api.driver.orders.track_order');

    Route::group(['prefix' => 'status'], function () {
        Route::get('index', 'OrderStatusController@index')->name('api.driver.orders_statuses.index');
        Route::post('update/{id}', 'OrderController@updateOrderStatus')->name('api.driver.orders_statuses.update');
    });
});

