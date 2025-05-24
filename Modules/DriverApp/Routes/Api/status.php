<?php


Route::group(['prefix' => 'status', 'middleware' => ['IsDriver' , 'auth:api'], 'namespace' => 'WebService'], function () {


    Route::get('/vendor-status', 'StatusController@index')->name('api.driver.status.index');
    Route::get('/list', 'StatusController@list')->name('api.driver.status.list');
    Route::post('/update/{id}', 'StatusController@update')->name('api.driver.status.update');
});
