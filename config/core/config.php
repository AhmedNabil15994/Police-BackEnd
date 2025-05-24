<?php

$ConstProjectName = 'Steak_Police';

return [
    'name' => 'Core',
    'image_mimes' => 'jpeg,png,jpg,gif,svg',
    'image_max' => '2048',
    'special_images' => ['default.png', 'user.png'],
    'product_img_path' => 'uploads/products',
    'adverts_img_path' => 'uploads/adverts',
    'addon_img_path' => 'uploads/addons',
    'constants' => [
        'PICKUP_DELIVERY' => $ConstProjectName . '_PICKUP_DELIVERY',
        'SHIPPING_BRANCH' => $ConstProjectName . '_SHIPPING_BRANCH',
        'DELIVERY_CHARGE' => $ConstProjectName . '_DELIVERY_CHARGE',
        'DASHBOARD_CHANNEL' => $ConstProjectName . '_DASHBOARD_CHANNEL',
        'DASHBOARD_ACTIVITY_LOG' => $ConstProjectName . '_DASHBOARD_ACTIVITY_LOG',
        'VENDOR_DASHBOARD_CHANNEL' => $ConstProjectName . '_VENDOR_DASHBOARD_CHANNEL',
        'VENDOR_DASHBOARD_ACTIVITY_LOG' => $ConstProjectName . '_VENDOR_DASHBOARD_ACTIVITY_LOG',
        'DRIVER_DASHBOARD_CHANNEL' => $ConstProjectName . '_DRIVER_DASHBOARD_CHANNEL',
        'DRIVER_DASHBOARD_ACTIVITY_LOG' => $ConstProjectName . '_DRIVER_DASHBOARD_ACTIVITY_LOG',
        'CART_KEY' => $ConstProjectName . '_CART_KEY',
        'ORDERS_IDS' => $ConstProjectName . '_ORDERS_IDS',
        'CONTACT_INFO' => $ConstProjectName . '_CONTACT_INFO',
        'ORDER_STATE_ID' => $ConstProjectName . '_ORDER_STATE_ID',
        'ORDER_STATE_NAME' => $ConstProjectName . '_ORDER_STATE_NAME',
        'ORDER_DELIVERY_TIME' => $ConstProjectName . '_ORDER_DELIVERY_TIME',
        'ORDER_DELIVERY_PRICE' => $ConstProjectName . '_ORDER_DELIVERY_PRICE',
    ],
];
