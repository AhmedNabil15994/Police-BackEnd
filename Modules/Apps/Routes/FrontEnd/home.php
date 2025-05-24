<?php

Route::get('/', 'FrontEnd\HomeController@index')/* ->middleware('cacheResponse') */->name('frontend.home');
Route::get('get-branches-by-state', 'FrontEnd\HomeController@getBranchesByState')->middleware('doNotCacheResponse')->name('frontend.get_branches_by_state');
