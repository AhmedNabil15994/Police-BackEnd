<?php

return [
    'supplier' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
        ],
        'form' => [
            'image' => 'Image',
            'status' => 'Status',
            'tabs' => [
                'general' => 'General Info.',
            ],
        ],
        'routes' => [
            'create' => 'Create supplier images',
            'index' => 'Supplier Images',
            'update' => 'Update supplier images',
        ],
        'validation' => [
            'image' => [
                'required' => 'Please select image of the supplier image',
            ],
        ],
    ],
];
