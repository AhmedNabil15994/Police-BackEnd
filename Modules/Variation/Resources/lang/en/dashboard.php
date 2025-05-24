<?php

return [
    'options' => [
        'form' => [
            'status' => 'Status',
            'title' => 'Title',
            'tabs' => [
                'general' => 'General Info.',
                'option_values' => 'Option Values',
            ]
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
        ],
        'routes' => [
            'create' => 'Create Options Products',
            'index' => 'Options Products',
            'update' => 'Update Option Products',
        ],
        'validation' => [
            'option_have_product_options' => 'Sorry, this option is included with other products',
            'title' => [
                'required' => 'Please enter the title of option',
                'unique' => 'This title option is taken before',
            ],
        ],
    ],
];
