<?php


Route::group(['prefix' => 'vendors'], function () {

    Route::get('delivery-charge', 'WebService\VendorController@deliveryCharge');
    Route::get('sections', 'WebService\VendorController@sections');
    Route::get('/', 'WebService\VendorController@vendors');
    Route::get('/{id}', 'WebService\VendorController@getVendorById')->name('get_one_vendor');

    Route::post('{vendor}/prescription', 'WebService\VendorController@sendPrescription');
    Route::post('{vendor}/ask-us', 'WebService\VendorController@sendAsk');

    Route::group(['prefix' => 'pickup'], function () {
        Route::get('supported-branches', 'WebService\VendorController@getPickupVendors')->name('api.vendors.supported_pickup_branches');
    });


    Route::group(['prefix' => '/', 'middleware' => 'auth:api'], function () {

        Route::post('rate', 'WebService\VendorController@vendorRate')->name('api.vendors.rate');

    });

    /*Route::group(['prefix' => 'delivery-companies'], function () {

        Route::get('{id}', 'WebService\VendorController@getVendorDeliveryCompanies');

    });*/

});
