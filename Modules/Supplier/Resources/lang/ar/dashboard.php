<?php

return [
    'supplier' => [
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
            'status' => 'الحاله',
        ],
        'form' => [
            'image' => 'الصورة',
            'status' => 'الحاله',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
        ],
        'routes' => [
            'create' => 'اضافة صور المورد',
            'index' => 'صور المورد',
            'update' => 'تعديل المورد',
        ],
        'validation' => [
            'image' => [
                'required' => 'من فضلك اختر صورة المورد',
            ],
        ],
    ],
];
