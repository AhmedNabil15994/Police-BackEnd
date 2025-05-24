<?php

return [
    'slider' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'options' => 'Options',
            'start_at' => 'Start at',
            'status' => 'Status',
        ],
        'form' => [
            'end_at' => 'End at',
            'image' => 'Image',
            'link' => 'Link',
            'start_at' => 'Start at',
            'status' => 'Status',
            'title' => 'Title',
            'short_description' => 'Short Description',
            'tabs' => [
                'general' => 'General Info.',
            ],
        ],
        'routes' => [
            'create' => 'Create slider images',
            'index' => 'slider images',
            'update' => 'Update slider images',
        ],
        'validation' => [
            'start_at' => [
                'required' => 'Please select the date of started slider image',
                'date' => 'Please enter the valid start date',
            ],
            'end_at' => [
                'required' => 'Please select slider image ent at',
                'date' => 'Please enter the valid end date',
            ],
            'image' => [
                'required' => 'Please select image of the slider image',
            ],
            'link' => [
                'required' => 'Please add the link of slider image',
            ],
            'title' => [
                'required' => 'Please add the title of slider',
            ],
        ],
    ],
];
