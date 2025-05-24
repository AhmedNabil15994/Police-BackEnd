<?php

return [
    'notifications' => [
        'title' => 'Add general notifications to users',
        'send_btn' => 'Send',
      'form'  => [
          'description'       => 'Description',
          'meta_description'  => 'Meta Description',
          'meta_keywords'     => 'Meta Keywords',
          'status'            => 'Status',
          'title'             => 'Title',
          'tabs'  => [
            'general'           => 'General Info.',
            'seo'               => 'SEO',
          ],
          'name' => 'Send Notifications',
          'msg_title' => 'Message Title',
          'msg_title_placeholder' => 'Ex: view new products',
          'msg_body' => 'Message Content',
      ],
      'datatable' => [
          'created_at'    => 'Created At',
          'date_range'    => 'Search By Dates',
          'options'       => 'Options',
          'status'        => 'Status',
          'title'         => 'Title',
      ],
      'routes'     => [
          'create' => 'Create Notifications',
          'index' => 'General Notifications',
          'update' => 'Update Notification',
      ],
      'validation'=> [
          'description'   => [
              'required'  => 'Please enter the description of notification',
          ],
          'title'         => [
              'required'  => 'Please enter the title of notification',
              'unique'    => 'This title notification is taken before',
          ],
      ],
        'general' => [
            'message_sent_success' => 'Notification Sent Successfully',
            'no_tokens' => 'Tokens not found',
        ],
    ],
];
