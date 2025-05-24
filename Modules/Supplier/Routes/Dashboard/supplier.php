<?php

Route::group(['prefix' => 'supplier'], function () {

    Route::get('/', 'Dashboard\SupplierController@index')
        ->name('dashboard.supplier.index')
        ->middleware(['permission:show_supplier']);

    Route::get('datatable', 'Dashboard\SupplierController@datatable')
        ->name('dashboard.supplier.datatable')
        ->middleware(['permission:show_supplier']);

    Route::get('create', 'Dashboard\SupplierController@create')
        ->name('dashboard.supplier.create')
        ->middleware(['permission:add_supplier']);

    Route::post('/', 'Dashboard\SupplierController@store')
        ->name('dashboard.supplier.store')
        ->middleware(['permission:add_supplier']);

    Route::get('{id}/edit', 'Dashboard\SupplierController@edit')
        ->name('dashboard.supplier.edit')
        ->middleware(['permission:edit_supplier']);

    Route::put('{id}', 'Dashboard\SupplierController@update')
        ->name('dashboard.supplier.update')
        ->middleware(['permission:edit_supplier']);

    Route::delete('{id}', 'Dashboard\SupplierController@destroy')
        ->name('dashboard.supplier.destroy')
        ->middleware(['permission:delete_supplier']);

    Route::get('deletes', 'Dashboard\SupplierController@deletes')
        ->name('dashboard.supplier.deletes')
        ->middleware(['permission:delete_supplier']);

    Route::get('{id}', 'Dashboard\SupplierController@show')
        ->name('dashboard.supplier.show')
        ->middleware(['permission:show_supplier']);

});
