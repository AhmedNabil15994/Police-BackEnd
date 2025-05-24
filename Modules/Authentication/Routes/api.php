<?php

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', 'WebService\LoginController@postLogin')->name('api.auth.login');
    Route::post('register', 'WebService\RegisterController@register')->name('api.auth.register');
    Route::post('forget-password', 'WebService\ForgotPasswordController@forgetPassword');

    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

        Route::post('logout', 'WebService\LoginController@logout')->name('api.auth.logout');

    });

});
