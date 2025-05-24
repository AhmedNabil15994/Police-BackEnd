<?php

return [
    'cart' => [
        'product' => [
            'not_found' => 'هذا المنتج غير متاح حالياً :',
            'select_single_addons' => 'من فضلك اختر من الاضافات الفردية للمنتج',
        ],
    ],
    'validations' => [
        'cart' => [
            'vendor_not_match' => 'هذا المنتج غير متطابق مع منتجات المطعم الاخر ، من فضلك احذف السلة و حاول مره اخرى',
        ],
        'user_token' => [
            'required' => 'ادخل رقم المستخدم',
        ],
        'state_id' => [
            'required' => 'ادخل رقم المنطقة',
            'exists' => 'رقم المنطقة غير موجود',
        ],
        'branch_id' => [
            'required' => 'ادخل رقم الفرع',
            'exists' => 'رقم الفرع غير موجود',
        ],
        'address_id' => [
            'required' => 'ادخل رقم العنوان',
            'exists' => 'رقم العنوان غير موجود',
        ],
        'pickup_delivery_type' => [
            'required' => 'اختر نوع التوصيل',
            'in' => 'نوع التوصيل يجب ان يكون ضمن: ',
        ],
        'addons' => [
            'selected_options_greater_than_options_count' => 'الإضافات المحددة اكبر من العدد المتاح للإضافة',
            'selected_options_less_than_options_count' => 'الإضافات المحددة اقل من العدد المتاح المفترض إختياره للإضافة',
            'addons_not_found' => 'هذه الإضافة غير موجوده',
            'option_not_found' => 'هذا الإختيار غير موجود',
            'addons_number' => 'رقم',
        ],
    ],
];
