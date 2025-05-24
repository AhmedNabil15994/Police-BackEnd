<?php

return [
    'payments' => [
        'create' => [
            'form' => [
                'code' => 'كود الدفع',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
            ],
            'title' => 'اضافة طرق الدفع',
        ],
        'datatable' => [
            'code' => 'كود الدفع',
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
        ],
        'index' => [
            'title' => 'طرق الدفع',
        ],
        'update' => [
            'form' => [
                'code' => 'كود الدفع',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
            ],
            'title' => 'تعديل طريقة الدفع',
        ],
        'validation' => [
            'code' => [
                'required' => 'من فضلك ادخل كود الدفع',
                'unique' => 'هذا الكود تم ادخالة من قبل',
            ],
            'image' => [
                'required' => 'من فضلك اختر الصورة',
            ],
        ],
    ],
    'sections' => [
        'create' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'info' => 'البيانات',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان القسم',
                'image' => 'صورة القسم',
            ],
            'title' => 'اضافة اقسام الفروع',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
        ],
        'index' => [
            'title' => 'اقسام الفروع',
        ],
        'update' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان القسم',
                'image' => 'صورة القسم',
            ],
            'title' => 'تعديل اقسام الفروع',
        ],
        'validation' => [
            'description' => [
                'required' => 'من فضلك ادخل وصف القسم',
            ],
            'title' => [
                'required' => 'من فضلك ادخل عنوان القسم',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
        ],
    ],
    'vendor_statuses' => [
        'create' => [
            'form' => [
                'accepted_orders' => 'حالة استقبال الطلبات',
                'info' => 'البيانات',
                'label_color' => 'لون العلامة',
                'title' => 'العنوان',
            ],
            'title' => 'اضافة حالات المطعم',
        ],
        'datatable' => [
            'accepted_orders' => 'حالة استقبال الطلبات',
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'label_color' => 'لون العلامة',
            'options' => 'الخيارات',
            'title' => 'العنوان',
        ],
        'index' => [
            'title' => 'حالات المطعم',
        ],
        'update' => [
            'form' => [
                'accepted_orders' => 'حالة استقبال الطلبات',
                'general' => 'بيانات عامة',
                'label_color' => 'لون العلامة',
                'title' => 'العنوان',
            ],
            'title' => 'تعديل حالات المطعم',
        ],
        'validation' => [
            'accepted_orders' => [
                'unique' => 'لا يمكن اكثر من حالة لستقبال الطلبات',
            ],
            'label_color' => [
                'required' => 'من فضلك اختر لون العلامة',
            ],
        ],
    ],
    'vendors' => [
        'create' => [
            'form' => [
                'commission' => 'نسبة الربح من الفرع',
                'description' => 'الوصف',
                'fixed_commission' => 'نسبة ربح ثابتة',
                'fixed_delivery' => 'سعر التوصيل الثابت',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'الحد الادنى للطلب',
                'other' => 'بيانات اخرى',
                'payments' => 'طرق الدفع المدعومة',
                'products' => 'تصدير المنتجات',
                'receive_prescription' => 'استقبال الوصفات الطبية',
                'receive_question' => 'استقبال الأسئلة',
                'sections' => 'قسم الفرع',
                'sellers' => 'بائعين الفرع',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'vendor_email' => 'البريد الالكتروني للفرع',
                'vendor_statuses' => 'حالة الفرع',
                'companies' => 'شركات التوصيل',
                'companies_and_states' => 'التوصيل',
                'states' => 'يرجى تحديد المناطق التي يتم التوصيل إليها',
                'restaurant' => 'المطعم التابع له',
                'is_main_branch' => 'فرع رئيسى',
            ],
            'title' => 'اضافة الفروع',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'restaurant' => 'المطعم',
            'products' => 'المنتجات',
            'no_products_data' => 'لا يوجد منتجات حالياً',
            'total' => 'الإجمالى',
            'per_page' => 'اجمالى الصفحة',
            'is_main_branch' => 'فرع رئيسى',
        ],
        'index' => [
            'sorting' => 'ترتيب الفروع',
            'title' => 'الفروع',
        ],
        'sorting' => [
            'title' => 'ترتيب الفروع',
        ],
        'update' => [
            'form' => [
                'commission' => 'نسبة الربح من الفرع',
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'الحد الادنى للطلب',
                'other' => 'بيانات اخرى',
                'payments' => 'طرق الدفع المدعومة',
                'products' => 'تصدير المنتجات',
                'receive_prescription' => 'استقبال الوصفات الطبية',
                'receive_question' => 'استقبال الاسالة',
                'sections' => 'قسم الفرع',
                'sellers' => 'بائعين الفرع',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'vendor_email' => 'البريد الالكتروني للفرع',
                'restaurant' => 'اختر المطعم',
                'is_main_branch' => 'فرع رئيسى',
            ],
            'title' => 'تعديل الفرع',
        ],
        'validation' => [
            'commission' => [
                'numeric' => 'من فضلك ادخل نسبه الربح ارقام انجليزية فقط',
                'required' => 'من فضلك ادخل نسبه الربح',
            ],
            'description' => [
                'required' => 'من فضلك ادخل الوصف',
            ],
            'fixed_delivery' => [
                'numeric' => 'من فضلك ادخل سعر التوصيل الثابت ارقام انجليزية فقط',
                'required' => 'من فضلك ادخل سعر التوصيل الثابت',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة الفرع',
            ],
            'months' => [
                'numeric' => 'من فضلك ادخل عدد شهور الباقة ارقام فقط',
                'required' => 'من فضلك ادخل عدد شهور الباقة',
            ],
            'order_limit' => [
                'numeric' => 'من فضلك ادخل الاحد الادنى كا ارقام انجليزية فقط : 5.000',
                'required' => 'من فضلك ادخل الحد الادنى للفرع : 5.000',
            ],
            'payments' => [
                'required' => 'من فضلك اختر طرق الدفع المدعومة من قبل هذا الفرع',
            ],
            'price' => [
                'numeric' => 'من فضلك ادخل سعر الباقة ارقام فقط',
                'required' => 'من فضلك ادخل سعر الباقة',
            ],
            'sections' => [
                'required' => 'من فضلك اختر قسم الفرع',
            ],
            'sellers' => [
                'required' => 'من فضلك اختر البائعين لهذا الفرع',
            ],
            'special_price' => [
                'numeric' => 'من فضلك ادخل السعر الخاص ارقام فقط',
            ],
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
            'products' => [
                'ids' => [
                    'required' => 'من فضلك اختر مصفوفة من الاختيارات او على الاقل اختيار واحد',
                ],
            ],
            'restaurant_id' => [
                'required' => 'من فضلك اختر المطعم لهذا الفرع',
            ],
            'is_main_branch' => [
                'required' => 'من فضلك اختر الفرع الرئيسى',
            ],
        ],
        'products' => [
            'title' => 'منتجات الفرع',
            'table' => [
                'title' => 'عنوان المنتج',
                'quantity' => 'الكمية',
                'price' => 'السعر',
                'status' => 'الحالة',
            ],
        ],
    ],
    'restaurants' => [
        'create' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'other' => 'بيانات اخرى',
                'products' => 'تصدير المنتجات',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'vendor_statuses' => 'حالة المطعم',
                'enable_delivery' => 'التوصيل للفرع الرئيسى',
                'enable_pickup' => 'الإستلام',
            ],
            'title' => 'اضافة المطاعم',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'image' => 'الصورة',
            'options' => 'الخيارات',
            'status' => 'الحالة',
            'title' => 'العنوان',
            'products' => 'المنتجات',
            'no_products_data' => 'لا يوجد منتجات حالياً',
            'total' => 'الإجمالى',
            'per_page' => 'اجمالى الصفحة',
        ],
        'index' => [
            'sorting' => 'ترتيب المطاعم',
            'title' => 'المطاعم',
        ],
        'sorting' => [
            'title' => 'ترتيب المطاعم',
        ],
        'update' => [
            'form' => [
                'description' => 'الوصف',
                'general' => 'بيانات عامة',
                'image' => 'الصورة',
                'info' => 'البيانات',
                'is_trusted' => 'صلاحيات الاضافة',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'other' => 'بيانات اخرى',
                'products' => 'تصدير المنتجات',
                'seo' => 'SEO',
                'status' => 'الحالة',
                'title' => 'عنوان',
                'is_main_branch' => 'اختر الفرع الرئيسى',
                'enable_delivery' => 'التوصيل للفرع الرئيسى',
                'enable_pickup' => 'الإستلام',
            ],
            'title' => 'تعديل المطعم',
        ],
        'validation' => [
            'description' => [
                'required' => 'من فضلك ادخل الوصف',
            ],
            'image' => [
                'required' => 'من فضلك اختر صورة المطعم',
            ],
            'title' => [
                'required' => 'من فضلك ادخل العنوان',
                'unique' => 'هذا العنوان تم ادخالة من قبل',
            ],
        ],
        'products' => [
            'title' => 'منتجات المطعم',
            'table' => [
                'title' => 'عنوان المنتج',
                'quantity' => 'الكمية',
                'price' => 'السعر',
                'status' => 'الحالة',
            ],
        ],
    ],
    'delivery_charges' => [
        'create' => [
            'form' => [
                'delivery' => 'قيمة التوصيل',
                'general' => 'بيانات عامة',
                'info' => 'البيانات',
                'state' => 'Meta Description',
                'vendor' => 'المطعم',
            ],
            'title' => 'اضافة قيم التوصيل',
        ],
        'datatable' => [
            'created_at' => 'تاريخ الآنشاء',
            'date_range' => 'البحث بالتواريخ',
            'delivery' => 'قيم التوصيل',
            'options' => 'الخيارات',
            'state' => 'عدد مناطق التوصيل',
            'company' => 'شركة الشحن',
            'vendor' => 'الفرع',
        ],
        'index' => [
            'title' => 'قيم التوصيل',
        ],
        'update' => [
            'charge' => 'قيمة التوصيل / دينار كويتي',
            'form' => [
                'delivery' => 'قيمة التوصيل',
                'general' => 'بيانات عامة',
                'state' => 'المنطقة',
                'vendor' => 'المطعم',
            ],
            'time' => 'وقت التوصيل / دقائق',
            'min_order_amount' => 'الحد الادنى للطلب',
            'title' => 'تعديل قيم التوصيل',
        ],
        'validation' => [
            'delivery' => [
                'numeric' => 'من فضلك ادخل قيمة التوصيل ارقام فقط',
                'required' => 'من فضلك ادخل قيمة التوصيل',
                'array' => 'قيمة التوصيل لابد ان تكون مصفوفة',
            ],
            'state' => [
                'numeric' => 'من فضلك اختر المنطقة ارقام فقط',
                'required' => 'من فضلك اختر المنطقة',
                'array' => 'منطقة التوصيل لابد ان تكون مصفوفة',
            ],
            'vendor' => [
                'numeric' => 'من فضلك اختر المطعم ارقام فقط',
                'required' => 'من فضلك اختر المطعم',
            ],
            'company' => [
                'numeric' => 'من فضلك اختر الشركة ارقام فقط',
                'required' => 'من فضلك اختر الشركة',
            ],
        ],
    ],
];
