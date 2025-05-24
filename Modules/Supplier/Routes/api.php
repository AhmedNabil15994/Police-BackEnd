<?php


Route::group(['prefix' => 'supplier'], function () {

    Route::get('/', 'WebService\SupplierController@index')->name('api.supplier.index');

});
