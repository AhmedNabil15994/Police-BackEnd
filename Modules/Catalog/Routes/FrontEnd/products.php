<?php

Route::get('products/{slug}', 'FrontEnd\ProductController@index')
    ->name('frontend.products.index');/*->middleware('cacheResponse');*/

Route::get('{category}/products/{slug}', 'FrontEnd\ProductController@index')
    ->name('frontend.category_products.index')/* ->middleware('cacheResponse') */;

Route::get('get-prd-variation-info', 'FrontEnd\ProductController@getPrdVariationInfo')
    ->name('frontend.get_prd_variation_info');
