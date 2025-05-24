<?php

return [
    'payments' => [
        'create' => [
            'form' => [
                'code' => 'Payment Code',
                'general' => 'General Info.',
                'image' => 'Image',
                'info' => 'Info.',
            ],
            'title' => 'Create Payments Methods',
        ],
        'datatable' => [
            'code' => 'Code',
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
        ],
        'index' => [
            'title' => 'Payments Methods',
        ],
        'update' => [
            'form' => [
                'code' => 'Payment Code',
                'general' => 'General info.',
                'image' => 'Image',
            ],
            'title' => 'Update Payment Method',
        ],
        'validation' => [
            'code' => [
                'required' => 'Please enter the code of payment method',
                'unique' => 'This code of payment is taken before',
            ],
            'image' => [
                'required' => 'Please enter the image of payment method',
            ],
        ],
    ],
    'sections' => [
        'create' => [
            'form' => [
                'description' => 'Description',
                'general' => 'General Info.',
                'info' => 'Info.',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'image' => 'Image',
            ],
            'title' => 'Create Vendors Sections',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
        ],
        'index' => [
            'title' => 'Vendors Sections',
        ],
        'update' => [
            'form' => [
                'description' => 'Description',
                'general' => 'General info.',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'image' => 'Image',
            ],
            'title' => 'Update Vendor Section',
        ],
        'validation' => [
            'description' => [
                'required' => 'Please enter the description of section',
            ],
            'title' => [
                'required' => 'Please enter the title of section',
                'unique' => 'This title section is taken before',
            ],
        ],
    ],
    'vendor_statuses' => [
        'create' => [
            'form' => [
                'accepted_orders' => 'Accpeting orders',
                'info' => 'Info.',
                'label_color' => 'Label Color',
                'title' => 'Title',
            ],
            'title' => 'Create Vendor Status',
        ],
        'datatable' => [
            'accepted_orders' => 'Accpeting orders',
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'label_color' => 'Label Color',
            'options' => 'Options',
            'title' => 'Title',
        ],
        'index' => [
            'title' => 'Vendor Status',
        ],
        'update' => [
            'form' => [
                'accepted_orders' => 'Accpeting orders',
                'general' => 'General info.',
                'label_color' => 'Label Color',
                'title' => 'Title',
            ],
            'title' => 'Update Vendor Status',
        ],
        'validation' => [
            'accepted_orders' => [
                'unique' => 'only one status can be accepted orders',
            ],
            'label_color' => [
                'required' => 'Please select the label color',
            ],
        ],
    ],
    'vendors' => [
        'create' => [
            'form' => [
                'commission' => 'Commission from vendor',
                'description' => 'Description',
                'fixed_commission' => 'Fixed Commission',
                'fixed_delivery' => 'Fixed Delivery Fees',
                'general' => 'General Info.',
                'image' => 'Image',
                'info' => 'Info.',
                'is_trusted' => 'Is Trusted',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'Order Limit',
                'other' => 'Other Info.',
                'payments' => 'Allowed Payments',
                'products' => 'Exporting Products',
                'receive_prescription' => 'Receiving Prescriptions',
                'receive_question' => 'Receiving Questions',
                'sections' => 'Vendor Section',
                'sellers' => 'Vendor sellers',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'vendor_email' => 'Vendor Email',
                'vendor_statuses' => 'Vendor Status',
                'companies' => 'Shipping Companies',
                'companies_and_states' => 'Shipping',
                'states' => 'Please select the areas to be delivered to',
                'restaurant' => 'Choose Restaurant',
                'is_main_branch' => 'Main Branch',
            ],
            'title' => 'Create Vendors',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'restaurant' => 'Restaurant',
            'products' => 'Products',
            'no_products_data' => 'There are no products currently',
            'total' => 'Total',
            'per_page' => 'Total Per Page',
            'is_main_branch' => 'Main Branch',
        ],
        'index' => [
            'sorting' => 'Sorting Vendors',
            'title' => 'Vendors',
        ],
        'sorting' => [
            'title' => 'Sorting Vendors',
        ],
        'update' => [
            'form' => [
                'commission' => 'Commission from vendor',
                'description' => 'Description',
                'general' => 'General info.',
                'image' => 'Image',
                'info' => 'Info.',
                'is_trusted' => 'Is Trusted',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'order_limit' => 'Order Limit',
                'other' => 'Other Info.',
                'payments' => 'Allowed Payments',
                'products' => 'Exporting Products',
                'receive_prescription' => 'Receiving Prescriptions',
                'receive_question' => 'Receiving Questions',
                'sections' => 'Vendor Section',
                'sellers' => 'Vendor Sellers',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'vendor_email' => 'Vendor Email',
                'restaurant' => 'Restaurant Of Branch',
                'is_main_branch' => 'Main Branch',
            ],
            'title' => 'Update Vendor',
        ],
        'validation' => [
            'commission' => [
                'numeric' => 'Please add commission as numeric only',
                'required' => 'Please add commission from vendor',
            ],
            'description' => [
                'required' => 'Please enter the description of vendor',
            ],
            'fixed_delivery' => [
                'numeric' => 'Please enter the fixed delivery fees as numbers only',
                'required' => 'Please enter the fixed delivery fees.',
            ],
            'image' => [
                'required' => 'Please select vendor profile image',
            ],
            'months' => [
                'numeric' => 'Please enter the months as numbers only',
                'required' => 'Please enter the months of the package',
            ],
            'order_limit' => [
                'numeric' => 'Please enter the order limit numeric only - ex : 5.000',
                'required' => 'Please enter the order limit for this vendro ex : 5.000',
            ],
            'payments' => [
                'required' => 'Please select the allowed payments methods for this vendor',
            ],
            'price' => [
                'numeric' => 'Please enter the price numbers only',
                'required' => 'Please enter the price of package',
            ],
            'sections' => [
                'required' => 'Please select the section of vendor',
            ],
            'sellers' => [
                'required' => 'Please select the sellers of this vendor',
            ],
            'special_price' => [
                'numeric' => 'Please enter the special price numbers only',
            ],
            'title' => [
                'required' => 'Please enter the title of vendor',
                'unique' => 'This title vendor is taken before',
            ],
            'products' => [
                'ids' => [
                    'required' => 'Please select a list of options or at least select one',
                ],
            ],
            'restaurant_id' => [
                'required' => 'Please select the restaurant of branch',
            ],
            'is_main_branch' => [
                'required' => 'Please select the main branch',
            ],
        ],
        'products' => [
            'title' => 'Vendor Products',
            'table' => [
                'title' => 'Product Title',
                'quantity' => 'Quantity',
                'price' => 'Price',
                'status' => 'Status',
            ],
        ],
    ],
    'restaurants' => [
        'create' => [
            'form' => [
                'description' => 'Description',
                'general' => 'General Info.',
                'image' => 'Image',
                'info' => 'Info.',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'products' => 'Exporting Products',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'enable_delivery' => 'Delivery',
                'enable_pickup' => 'Pick-Up',
            ],
            'title' => 'Create Restaurants',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'restaurant' => 'Restaurant',
            'products' => 'Products',
            'no_products_data' => 'There are no products currently',
            'total' => 'Total',
            'per_page' => 'Total Per Page',
        ],
        'index' => [
            'sorting' => 'Sorting Restaurants',
            'title' => 'Restaurants',
        ],
        'sorting' => [
            'title' => 'Sorting Restaurants',
        ],
        'update' => [
            'form' => [
                'description' => 'Description',
                'general' => 'General info.',
                'image' => 'Image',
                'info' => 'Info.',
                'meta_description' => 'Meta Description',
                'meta_keywords' => 'Meta Keywords',
                'other' => 'Other Info.',
                'products' => 'Exporting Products',
                'seo' => 'SEO',
                'status' => 'Status',
                'title' => 'Title',
                'is_main_branch' => 'Select Main Branch',
                'enable_delivery' => 'Delivery',
                'enable_pickup' => 'Pick-Up',
            ],
            'title' => 'Update Restaurant',
        ],
        'validation' => [
            'description' => [
                'required' => 'Please enter the description of restaurant',
            ],
            'fixed_delivery' => [
                'numeric' => 'Please enter the fixed delivery fees as numbers only',
                'required' => 'Please enter the fixed delivery fees.',
            ],
            'image' => [
                'required' => 'Please select restaurant profile image',
            ],
            'title' => [
                'required' => 'Please enter the title of restaurant',
                'unique' => 'This title restaurant is taken before',
            ],
            'products' => [
                'ids' => [
                    'required' => 'Please select a list of options or at least select one',
                ],
            ],
        ],
        'products' => [
            'title' => 'Restaurant Products',
            'table' => [
                'title' => 'Product Title',
                'quantity' => 'Quantity',
                'price' => 'Price',
                'status' => 'Status',
            ],
        ],
    ],
    'delivery_charges' => [
        'create' => [
            'form' => [
                'delivery' => 'Charge',
                'general' => 'General Info.',
                'info' => 'Info.',
                'state' => 'State',
                'company' => 'Company',
            ],
            'title' => 'Create Delivery Charges',
        ],
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'delivery' => 'Charge',
            'options' => 'Options',
            'state' => 'State',
            'vendor' => 'Vendor',
        ],
        'index' => [
            'title' => 'Delivery Charges',
        ],
        'update' => [
            'charge' => 'Delivery Charge / KWD',
            'form' => [
                'delivery' => 'Charge',
                'general' => 'General info.',
                'state' => 'State',
                'vendor' => 'Vendor',
            ],
            'time' => 'Delivery Time / Minutes',
            'min_order_amount' => 'Min Order Amount',
            'title' => 'Update Delivery Charges',
        ],
        'validation' => [
            'delivery' => [
                'numeric' => 'Please enter the delivery charge numbers only',
                'required' => 'Please enter the delivery charge',
                'array' => 'Delivery price should be array',
            ],
            'state' => [
                'numeric' => 'Please select the state numbers only',
                'required' => 'Please select the state',
                'array' => 'State should be array',
            ],
            'vendor' => [
                'numeric' => 'Please select the vendor numbers only',
                'required' => 'Please select the vendor',
            ],
            'company' => [
                'numeric' => 'Please select the company numbers only',
                'required' => 'Please select the company',
            ],
        ],
    ],
];
