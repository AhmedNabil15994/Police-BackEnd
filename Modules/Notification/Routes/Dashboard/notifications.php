<?php

Route::group(['prefix' => 'notifications'/*, 'middleware' => 'CheckToggleGeneralNotifications'*/], function () {

    Route::get('create', 'Dashboard\NotificationController@notifyForm')
        ->name('dashboard.notifications.create')
        ->middleware(['permission:add_notifications']);

    Route::post('send', 'Dashboard\NotificationController@push_notification')
        ->name('dashboard.notifications.store')
        ->middleware(['permission:add_notifications']);

});
