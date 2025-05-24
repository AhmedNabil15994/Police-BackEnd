<?php

Route::post('webhooks', 'FrontEnd\OrderController@webhooks')->name('frontend.orders.webhooks');
Route::get('/orders/details/{trackingOrderId}', 'FrontEnd\OrderController@orderWebhookDetails')->name('frontend.orders.order_details');

Route::group(['prefix' => 'orders'], function () {

    Route::get('callback', 'FrontEnd\OrderController@callback')
        ->name('frontend.orders.callback');

    Route::get('success', 'FrontEnd\OrderController@success')
        ->name('frontend.orders.success');

    Route::get('failed', 'FrontEnd\OrderController@failed')
        ->name('frontend.orders.failed');

    Route::get('/', 'FrontEnd\OrderController@index')
        ->name('frontend.orders.index');
//        ->middleware('auth');

    Route::get('reorder/{id}', 'FrontEnd\OrderController@reOrder')
        ->name('frontend.orders.reorder')
        ->middleware('auth');

    Route::get('{id}', 'FrontEnd\OrderController@invoice')
        ->name('frontend.orders.invoice');
//        ->middleware('auth');

    Route::get('guest/invoice', 'FrontEnd\OrderController@guestInvoice')
        ->name('frontend.orders.guest.invoice');

    Route::post('/', 'FrontEnd\OrderController@createOrder')
        ->name('frontend.orders.create_order')
        ->middleware('empty.cart');

});
