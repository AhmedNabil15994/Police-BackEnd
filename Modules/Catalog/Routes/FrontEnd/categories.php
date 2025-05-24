<?php

Route::get('categories', 'FrontEnd\CategoryController@index')
    ->name('frontend.categories.index')/* ->middleware('cacheResponse') */;

Route::get('category/{slug?}', 'FrontEnd\CategoryController@productsCategory')
    ->name('frontend.categories.products')/* ->middleware('cacheResponse') */;
