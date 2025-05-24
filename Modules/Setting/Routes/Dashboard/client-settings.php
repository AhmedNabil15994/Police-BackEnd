<?php

Route::group(['prefix' => 'client-setting'], function () {

    // Show Settings Form
    Route::get('/', 'Dashboard\ClientSettingController@index')
        ->name('dashboard.client.setting.index')
        ->middleware(['permission:show_client_settings']);

    // Update Settings
    Route::post('/', 'Dashboard\ClientSettingController@update')
        ->name('dashboard.client.setting.update')
        ->middleware(['permission:show_client_settings']);

});
