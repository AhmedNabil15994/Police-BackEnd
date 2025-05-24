<?php

// Dashboard ViewComposr
view()->composer([
    'catalog::dashboard.categories.*',
    'catalog::dashboard.products.*',
    'coupon::dashboard.*',
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
    'setting::dashboard.tabs.products',
], \Modules\Catalog\ViewComposers\Dashboard\CategoryComposer::class);

// Dashboard ViewComposr
view()->composer([
    'vendor::dashboard.vendors.*',
    'advertising::dashboard.advertising.*',
    'notification::dashboard.notifications.*',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);


view()->composer([
    'coupon::dashboard.*',
], \Modules\Catalog\ViewComposers\Dashboard\ProductComposer::class);

// Vendor Dashboard ViewComposr
view()->composer([
    'catalog::vendor.categories.*',
    'catalog::vendor.products.create',
    'catalog::vendor.products.clone',
    'catalog::vendor.products.edit',
    'catalog::vendor.products.index',
], \Modules\Catalog\ViewComposers\Vendor\CategoryComposer::class);

// FrontEnd ViewComposer
view()->composer([
    'apps::frontend.layouts.*',
], \Modules\Catalog\ViewComposers\FrontEnd\CategoryComposer::class);


// FrontEnd ViewComposer
view()->composer([
    'core::frontend.shared.pickup-delivery-section',
], \Modules\Catalog\ViewComposers\FrontEnd\CheckDeliveryAndMinOrderComposer::class);

view()->composer([
    'catalog::dashboard.addon_options.*',
    'catalog::dashboard.products.addons',
], \Modules\Catalog\ViewComposers\Dashboard\AddonCategoryComposer::class);
