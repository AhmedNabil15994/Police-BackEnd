<?php

return [
    'brands' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
        ],
        'form' => [
            'image' => 'Image',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'status' => 'Status',
            'tabs' => [
                'general' => 'General Info.',
                'seo' => 'SEO',
            ],
            'title' => 'Title',
        ],
        'routes' => [
            'create' => 'Create Brands',
            'index' => 'Brands',
            'update' => 'Update Brand',
        ],
        'validation' => [
            'image' => [
                'required' => 'Please select image',
            ],
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
        ],
    ],
    'categories' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
        ],
        'form' => [
            'image' => 'Image',
            'main_category' => 'Main Category',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'status' => 'Status',
            'show_in_home' => 'Show In Home',
            'tabs' => [
                'category_level' => 'Categories Tree',
                'general' => 'General Info.',
                'seo' => 'SEO',
            ],
            'title' => 'Title',
            'color' => 'Color',
            'sort' => 'Sort',
            'color_hint' => 'Hex Color - example: FFFFFF',
        ],
        'routes' => [
            'create' => 'Create Categories',
            'index' => 'Categories',
            'update' => 'Update Category',
        ],
        'validation' => [
            'category_id' => [
                'required' => 'Please select category level',
            ],
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
            'color' => [
                'required_if' => 'Please enter a color for the main category',
            ],
            'image' => [
                'required' => 'Pleas select image',
                'image' => 'Image file should be an image',
                'mimes' => 'Image must be in',
                'max' => 'The image size should not be more than',
            ],
        ],
    ],
    'products' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'vendor' => 'Vendor',
            'price' => 'Price',
            'qty' => 'Qty',
        ],
        'form' => [
            'arrival_end_at' => 'New Arrival End At',
            'arrival_start_at' => 'New Arrival Start At',
            'arrival_status' => 'New Arrival Status',
            'brands' => 'Product Brand',
            'cost_price' => 'Cost Price',
            'description' => 'Description',
            'short_description' => 'Short Description',
            'end_at' => 'Offer End At',
            "new_add" => "New Add",
            "empty_options" => "Empty Options",
            'image' => 'Image',
            'meta_description' => 'Meta Description',
            'meta_keywords' => 'Meta Keywords',
            'offer' => 'Product Offer',
            'offer_price' => 'Offer Price',
            'offer_status' => 'Offer Status',
            "width" => "Width",
            "height" => "Height",
            "weight" => "Weight",
            "length" => "Length",
            "shipment" => "Shipment",
            "tags" => "Product Tags",
            "add_variations" => "Add Variations",

            'options' => 'Options',
            'percentage' => 'Percentage',
            'price' => 'Price',
            'qty' => 'Qty',
            'sku' => 'SKU',
            'start_at' => 'Offer Start At',
            'main_products' => 'Product',
            'status' => 'Status',
            'featured' => 'Featured',
            'browse_image' => 'Browse',
            'btn_add_more' => 'Add More',
            'vendor' => 'Vendor',
            'created_at' => 'Created At',
            'pending_for_approval' => 'Product approval',
            'restaurants' => 'Restaurants',
            'branches' => 'Branches',

            'tabs' => [
                'export' => 'Export Products',
                'categories' => 'Product Categories',
                'gallery' => 'Image Gallery',
                'general' => 'General Info.',
                'new_arrival' => 'New Arrival',
                'seo' => 'SEO',
                'stock' => 'Stock & Price',
                'variations' => 'Variations',
                'add_ons' => 'Add Ons',
                'edit_add_ons' => 'Edit AddOns',
                "shipment" => "Extra Information",
                "input_lang" => "Data :lang",
                "images" => "Product Images",
                "tags" => "Product Tags",
                "restaurants_branches" => "Restaurants And Branches",
                "ordering_times" => "Ordering Times",
            ],

            'title' => 'Title',
            'vendors' => 'Product Vendor',

            'add_ons' => [
                'title' => 'Addon',
                'product' => 'Product',
                'name' => 'Name',
                'type' => 'Type',
                'single' => 'Single Select',
                'multiple' => 'Multi Select',
                'option' => 'Option',
                'price' => 'Price',
                'qty' => 'Qty',
                'image' => 'Image',
                'default' => 'Default',
                'add_more' => 'Add More',
                'save_options' => 'Save',
                'add_ons_name' => 'Add Ons Name',
                'show' => 'Show',
                'delete' => 'Delete',
                'reset_form' => 'Reset Form',
                'customer_can_select_exactly' => 'CUSTOMER CAN SELECT EXACTLY',
                'options_count' => 'Options Count',
                'min_options_count' => 'Min Options',
                'max_options_count' => 'Max Options',
                'created_at' => 'Created At',
                'operations' => 'Operations',
                'clear_defaults' => 'Clear Defaults',
                'confirm_msg' => 'Are you sure ?',
                'at_least_one_field' => 'At least one field is required',
                'options_count_greater_than_rows' => 'The number of customer choices should be less than the total choices',
                'loading' => 'Loading ...',
            ],

            'unlimited' => 'Unlimited Quantity',
            'limited' => 'Limited Quantity',

            'customize_ordering_time' => 'Customize ordering time',
        ],
        'routes' => [
            'clone' => 'Clone & Create Product',
            'create' => 'Create Products',
            'index' => 'Products',
            'update' => 'Update Product',
            'add_ons' => 'Add Ons',
            'review_products' => 'Review Products',
            'show' => 'Product Details',
        ],
        'validation' => [
            'select_option_values' => 'Please, Select option values',
            'arrival_end_at' => [
                'date' => 'Please enter end at ( new arrival ) as date',
                'required' => 'Please enter end at ( new arrival )',
            ],
            'arrival_start_at' => [
                'date' => 'Please enter start at ( new arrival ) as date',
                'required' => 'Please enter end at ( new arrival )',
            ],
            'brand_id' => [
                'required' => 'Please select the brand',
            ],
            "width" => [
                'required' => 'Please select the width',
                'numeric' => 'Please enter the width as numeric only',
            ],
            "length" => [
                'required' => 'Please select the length',
                'numeric' => 'Please enter the length as numeric only',
            ],
            "weight" => [
                'required' => 'Please select the weight',
                'numeric' => 'Please enter the weight as numeric only',
            ],
            "height" => [
                'required' => 'Please select the height',
                'numeric' => 'Please enter the height as numeric only',
            ],
            'category_id' => [
                'required' => 'Please select at least one category',
            ],
            'cost_price' => [
                'numeric' => 'Please enter the cost price as numeric only',
                'required' => 'Please enter the cost price',
            ],
            'end_at' => [
                'date' => 'Please enter end at ( offer ) as date',
                'required' => 'Please enter end at ( offer )',
            ],
            'offer_price' => [
                'numeric' => 'Please enter the offer price as numeric only',
                'required' => 'Please enter the offer price',
            ],
            'price' => [
                'numeric' => 'Please enter the price as numeric only',
                'min' => 'Please enter the price greater than zero',
                'required' => 'Please enter the price',
            ],
            'qty' => [
                'numeric' => 'Please enter the quantity as numeric only',
                'min' => 'Please enter the quantity as numeric greater than zero',
                'required' => 'Please enter the quantity',
            ],
            'sku' => [
                'required' => 'Please enter the SKU',
            ],
            'start_at' => [
                'date' => 'Please enter start at ( offer ) as date',
                'required' => 'Please enter start at ( offer )',
            ],
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
            'variation_price' => [
                'required' => 'Please add price of variants',
            ],
            'variation_qty' => [
                'required' => 'Please add Quantity of variants',
            ],
            'variation_sku' => [
                'required' => 'Please add SKU of variants',
            ],
            'variation_status' => [
                'required' => 'Please select status of variants',
            ],
            'vendor_id' => [
                'required' => 'Please select the vendor',
            ],
            'image' => [
                'required' => 'Pleas select image',
                'image' => 'Image file should be an image',
                'mimes' => 'Image must be in',
                'max' => 'The image size should not be more than',
            ],
            'add_ons' => [
                'option_name' => [
                    'required' => 'Please enter add ons name',
                ],
                'add_ons_type' => [
                    'required' => 'Please select add ons type',
                    'in' => 'Add ons type in',
                ],
                'price' => [
                    'required' => 'Please enter add ons options price',
                    'array' => 'Add ons price should be array',
                ],
                'rowId' => [
                    'required' => 'Please enter all add ons options ids',
                    'array' => 'Add ons Row IDs should be array',
                ],
                'option' => [
                    'required' => 'Please enter all add ons option\'s name',
                    'array' => 'Add ons options should be array',
                    'min' => 'At least one add ons option',
                ],
            ],
            'images' => [
                'mimes' => 'File is not supported as image type',
                'max' => 'Image size is greater than 1 Mg',
            ],
            'restaurants' => [
                'required' => 'Please, select restaurant',
                'array' => 'Restaurants should be array',
                'min' => 'At least one restaurant',
            ],
        ],
    ],
    'addon_categories' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'status' => 'Status',
            'title' => 'Title',
            'type' => 'Type',
            'sort' => 'Sort',
        ],
        'form' => [
            'image' => 'Image',
            'status' => 'Status',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'title' => 'Title',
            'color' => 'Color',
            'sort' => 'Sort',
            'color_hint' => 'Hex Color - example: FFFFFF',
        ],
        'routes' => [
            'create' => 'Create Addon Categories',
            'index' => 'Addon Categories',
            'update' => 'Update Addon Category',
        ],
        'validation' => [
            'image' => [
                'required' => 'Please select image',
            ],
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
            'color' => [
                'required_if' => 'Please enter a color for the main category',
            ],
        ],
    ],
    'addon_options' => [
        'datatable' => [
            'created_at' => 'Created At',
            'date_range' => 'Search By Dates',
            'image' => 'Image',
            'options' => 'Options',
            'price' => 'Price',
            'title' => 'Title',
            'type' => 'Type',
            'addon_category' => 'Addon Category',
        ],
        'form' => [
            'image' => 'Image',
            'status' => 'Status',
            'price' => 'Price',
            'qty' => 'Quantity',
            'tabs' => [
                'general' => 'General Info.',
            ],
            'title' => 'Title',
            'color' => 'Color',
            'sort' => 'Sort',
            'unlimited' => 'Unlimited Quantity',
            'limited' => 'Limited Quantity',
            'addon_category_id' => 'Addon Category',
        ],
        'alert' => [
            'select_addon_category' => 'Select Addon Category',
        ],
        'routes' => [
            'create' => 'Create Addons',
            'index' => 'Addons',
            'update' => 'Update Addons',
        ],
        'validation' => [
            'image' => [
                'required' => 'Please select image',
            ],
            'title' => [
                'required' => 'Please enter the title',
                'unique' => 'This title is taken before',
            ],
            'price' => [
                'numeric' => 'Please enter the price as numeric only',
                'min' => 'Please enter the price greater than zero',
                'required' => 'Please enter the price',
            ],
            'qty' => [
                'numeric' => 'Please enter the quantity as numeric only',
                'min' => 'Please enter the quantity as numeric greater than zero',
                'required' => 'Please enter the quantity',
            ],
            'addon_category_id' => [
                'required' => 'Please select Addon Category',
                'exists' => 'This category is not exist.',
            ],
            'addon_options' => [
                'required' => 'Please select Category Addons',
                'array' => 'Category addons must be of an array type',
                'min' => 'Category addons must contain at least one element',
            ],
        ],
    ],
];
