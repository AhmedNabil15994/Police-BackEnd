<?php

Route::group(['prefix' => '/'], function () {

    Route::get('home', 'WebService\CatalogController@getHomeData')/* ->middleware('cacheResponse') */->name('api.home');
});

Route::group(['prefix' => 'catalog'], function () {
    Route::get('categories', 'WebService\CatalogController@getCategoriesTreeWithProducts')->middleware('doNotCacheResponse'/* , 'cacheResponse:categoriesTag' */)->name('api.categories.list');
    Route::get('products', 'WebService\CatalogController@getAllProductsByBranch')->middleware('doNotCacheResponse', /* 'cacheResponse:productsTag' */)->name('api.products_by_category');
    Route::get('v2/products', 'WebService\CatalogController@getAllProductsByBranchV2')->middleware('doNotCacheResponse')->name('api.products_by_category.v2');
    Route::get('search-products', 'WebService\CatalogController@searchProducts')/* ->middleware('cacheResponse') */->name('api.search_products');
    Route::get('offers-products', 'WebService\CatalogController@getOffersProducts')/* ->middleware('cacheResponse') */->name('api.offers_products');
    Route::get('product/{id}/details', 'WebService\CatalogController@getProductDetails')/* ->middleware('cacheResponse') */;
    Route::get('complete-your-meal', 'WebService\CatalogController@getProductsByCategoryWithoutAddons')/* ->middleware('cacheResponse') */;
});
