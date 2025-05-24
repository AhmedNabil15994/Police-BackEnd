<?php


Route::group(['prefix' => 'areas'], function () {

    Route::get('cities-with-states', 'WebService\AreaController@citiesWithStates')->name('api.areas.cities_with_states');
    Route::get('countries', 'WebService\AreaController@countries')->name('api.areas.countries.index');
    Route::get('cities/{id}', 'WebService\AreaController@cities')->name('api.areas.cities.index');
    Route::get('states/{id}', 'WebService\AreaController@states')->name('api.areas.cities.index');

    /*Route::get('cities', 'WebService\AreaController@cities')->name('api.areas.cities.index');
    Route::get('states', 'WebService\AreaController@states')->name('api.areas.cities.index');*/

});

Route::get('countries', 'WebService\CountryController@index');

