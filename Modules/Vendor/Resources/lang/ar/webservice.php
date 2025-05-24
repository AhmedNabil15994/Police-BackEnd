<?php

return [
    'rates'   => [
        'user_rate_before'          => 'تم التقييم من قبل',
        'user_not_have_order'       => 'هذا الطلب غير تابع للمستخدم',
        'rated_successfully'        => 'تم تقييم الصيدلية بنجاح',
        'btnClose'                  => 'خروج',
        'your_rate'                 => 'تقييمك',
        'rate_now'                  => 'قيم الآن',
        'ratings'                   => 'تقييمات',
        'validation'  => [
            'order_id'    => [
                'required' => 'رقم الطلب مطلوب',
                'exists' => 'رقم الطلب غير موجود فى جدول الطلبات',
            ],
            'rating'    => [
                'required' => 'التقييم مطلوب',
                'integer' => 'قيمة التقييم لابد ان تكون رقمية',
                'between' => 'قيمة التقييم لابد ان تكون بين 1 و 5',
            ],
            'comment'    => [
                'string' => 'التعليق لابد ان يكون قيمة نصية',
                'max' => 'التعليق يجب ألا يتجاوز 1000 حرف',
            ],
        ],
    ],
    'companies' =>  [
        'vendor_not_found_with_this_state'      =>  'المنطقة غير موجوده مع المطعم',
    ],
];
