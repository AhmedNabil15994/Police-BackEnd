<?php

return [
    'slider' => [
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'الرابط',
            'options' => 'الخيارات',
            'start_at' => 'يبدا في',
            'status' => 'الحاله',
        ],
        'form' => [
            'end_at' => 'الانتهاء في',
            'image' => 'الصورة',
            'link' => 'رابط السلايدر',
            'start_at' => 'يبدا في',
            'status' => 'الحاله',
            'title' => 'العنوان',
            'short_description' => 'الوصف المختصر',
            'tabs' => [
                'general' => 'بيانات عامة',
            ],
        ],
        'routes' => [
            'create' => 'اضافة صور السلايدر',
            'index' => 'صور السلايدر',
            'update' => 'تعديل السلايدر',
        ],
        'validation' => [
            'start_at' => [
                'required' => 'من فضلك اختر تاريخ البدء',
                'date' => 'من فضلك ادخل قيمة صحيحة لتاريخ البدء',
            ],
            'end_at' => [
                'required' => 'من فضلك اختر تاريخ الانتهاء',
                'date' => 'من فضلك ادخل قيمة صحيحة لتاريخ الانتهاء',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة السلايدر',
            ],
            'link' => [
                'required' => 'من فضلك ادخل رابط السلايدر',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان السلايدر',
            ],
        ],
    ],
];
