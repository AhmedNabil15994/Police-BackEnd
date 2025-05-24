<?php

return [
    'advertising' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'options' => 'Options',
            'start_at' => 'Start at',
            'status' => 'Status',
            'advertising_group' => 'Advertising Group',
        ],
        'form' => [
            'type' => 'Type',
            'end_at' => 'End at',
            'image' => 'Image',
            'image_ar' => 'Image - Arabic',
            'image_en' => 'Image - English',
            'link' => 'Link',
            'start_at' => 'Start at',
            'status' => 'Status',
            'sort' => 'Sort',
            'products' => 'Products',
            'categories' => 'Categories',
            'groups' => 'Advert Groups',
            'advert_time_status' => 'Advert Duration Status',
            'link_type' => [
                'label' => 'Link Type',
                'external' => 'External',
                'product' => 'Product',
                'category' => 'Category',
            ],
            'tabs' => [
                'general' => 'General Info.',
            ],
        ],
        'routes' => [
            'create' => 'Create Advertisements',
            'create_advert' => 'Create Advert',
            'index' => 'Advertisements',
            'update' => 'Update Advertisements',
            'all_adverts' => 'Show Advertisements',
        ],
        'alert' => [
            'select_position' => 'Choose Position',
            'select_products' => 'Choose Product',
            'select_categories' => 'Choose Category',
            'select_groups' => 'Choose Advert Group',
        ],
        'validation' => [
            'end_at' => [
                'required' => 'Please select ad ent at',
            ],
            'image' => [
                'required' => 'Please select image of the ad',
            ],
            'link' => [
                'required_if' => 'Please add the link of ad',
            ],
            'product_id' => [
                'required' => 'Please choose product',
                'exists' => 'The product of ad is not found',
            ],
            'category_id' => [
                'required' => 'Please choose category',
                'exists' => 'The category of ad is not found',
            ],
            'start_at' => [
                'required' => 'Please select the date of started ad',
            ],
            'link_type' => [
                'required' => 'Please choose the link type of ad',
                'in' => 'The link type should be in: external,product,category',
            ],
            'group_id' => [
                'required' => 'Please choose the group of ad',
                'exists' => 'The group of ad is not found',
            ],
            'image_ar' => [
                'required' => 'Please select arabic image of the ad',
                'image' => 'Arabic image file should be an image',
                'mimes' => 'Arabic image must be in',
                'max' => 'Arabic image size should not be more than',
            ],
            'image_en' => [
                'required' => 'Please select english image of the ad',
                'image' => 'English image file should be an image',
                'mimes' => 'English image must be in',
                'max' => 'English image size should not be more than',
            ],
        ],
    ],
    'advertising_groups' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'position' => 'Position',
        ],
        'form' => [
            'title' => 'Title',
            'status' => 'Status',
            'sort' => 'Sort',
            'home' => 'Home',
            'categories' => 'Categories',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'position' => [
                'label' => 'Position',
                'home' => 'Home',
                'categories' => 'Categories',
            ],
            'item_style' => [
                'label' => 'Item Style',
                'vertical' => 'Vertical',
                'horizontal' => 'Horizontal',
            ],
        ],
        'routes' => [
            'create' => 'Create Advert Group',
            'index' => 'Advert Groups',
            'update' => 'Update Advert Group',
        ],
        'alert' => [
            'select_position' => 'Choose Position',
            'select_item_style' => 'Choose Item Style',
            'no_ad_groups_now' => 'There are no ad groups at the moment',
        ],
        'validation' => [
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
            'position' => [
                'required' => 'Please choose the position of ad',
                'in' => 'The position should be in: home,categories',
            ],
            'item_style' => [
                'required' => 'Please choose the item style',
                'in' => 'The item style should be in',
            ],
        ],
    ],
];
