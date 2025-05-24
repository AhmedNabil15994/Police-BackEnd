<?php

return [
    'addresses' => [
        'btn' => [
            'edit' => 'Edit Address',
            'store' => 'Save Address',
        ],
        'edit' => [
            'title' => 'Edit Address',
        ],
        'create' => [
            'title' => 'Create Address',
        ],
        'form' => [
            'address_details' => 'Address Details',
            'block' => 'Block Number',
            'building' => 'Building Number',
            'email' => 'E-mail address',
            'optional_email' => 'Optional E-mail address',
            'kuwait' => 'Kuwait',
            'mobile' => 'Mobile',
            'states' => 'Chose State',
            'state_name' => 'State',
            'street' => 'Street',
            'username' => 'Name',
            'civil_id' => 'Civil ID',
            'district' => 'Avenue',
            'flat' => 'Flat',
            'floor' => 'Floor',
            'governorate' => 'Governorate',
            'additions' => 'Additional Directions',
            'additional_instructions' => 'Additional Instructions',
            'car_type' => 'Car Type',
            'car_number' => 'Car Number',
            'car_color' => 'Car Color',
        ],
        'index' => [
            'alert' => [
                'error' => 'Oops! something happened try again later',
                'success' => 'Address updates successfully',
                'success_' => 'Address Added Successfully',
                'delete' => 'Address Deleted Successfully',
                'no_addresses' => 'There are no registered addresses',
            ],
            'btn' => [
                'add' => 'Add New Address',
                'delete' => 'Delete',
                'edit' => 'Edit',
            ],
            'title' => 'My Addresses',
        ],
        'validations' => [
            'address' => [
                'min' => 'Please add more details , must be more than 10 characters',
                'required' => 'Please add address details',
                'string' => 'Please add address details as string only',
            ],
            'block' => [
                'required' => 'Please enter the block',
                'string' => 'You must add only characters or numbers in block',
            ],
            'building' => [
                'required' => 'Please enter the building number / name',
                'string' => 'You must add only characters or numbers in building',
            ],
            'email' => [
                'email' => 'Email must be email format',
                'required' => 'Please add your email',
            ],
            'mobile' => [
                'digits_between' => 'You must enter mobile number with 8 digits',
                'min' => 'You must enter mobile number with 8 digits',
                'max' => 'You must enter mobile number with 8 digits',
                'numeric' => 'Please add mobile number as numbers only',
                'required' => 'Please add mobile number',
                'string' => 'Please add mobile as string only',
            ],
            'state' => [
                'numeric' => 'Please chose state',
                'required' => 'Please chose state',
            ],
            'state_id' => [
                'numeric' => 'Please chose state',
                'required' => 'Please chose state',
            ],
            'street' => [
                'required' => 'Please enter the street name / number',
                'string' => 'You must add only characters or numbers in street',
            ],
            'username' => [
                'min' => 'Name must be more than 2 characters',
                'required' => 'Please add name',
                'string' => 'Please add name as string only',
            ],
        ],
    ],
    'profile' => [
        'index' => [
            'addresses' => 'Addresses',
            'my_orders' => 'My Orders',
            'favourites' => 'Favourites',
            'my_account' => 'My Account',
            'btn_add_new_address' => 'Add Address',
            'alert' => [
                'error' => 'Opss! something wrong please try again later',
                'success' => 'Your profile updated succesfully',
            ],
            'form' => [
                'form_title' => 'Edit My Info',
                'btn' => [
                    'update' => 'Update',
                ],
                'current_password' => 'Current Password',
                'email' => 'Email Address',
                'mobile' => 'Mobile',
                'name' => 'Name',
                'password' => 'New Password',
                'password_confirmation' => 'Password Confirmation',
            ],
            'logout' => 'Logout',
            'orders' => 'My Orders',
            'title' => 'Profile',
            'update' => 'Update Profile',
            'validation' => [
                'current_password' => [
                    'not_match' => 'Current password not matched with the saved password',
                    'required_with' => 'Please enter your current password',
                ],
                'email' => [
                    'email' => 'Please enter correct email format',
                    'required' => 'The email field is required',
                    'unique' => 'The email has already been taken',
                ],
                'mobile' => [
                    'numeric' => 'The mobile must be a number',
                    'required' => 'The mobile field is required',
                    'unique' => 'The mobile has already been taken',
                ],
                'name' => [
                    'required' => 'The name field is required',
                ],
                'password' => [
                    'confirmed' => 'Password not match with the cnofirmation',
                    'min' => 'Password must be more than 6 characters',
                    'required' => 'The password field is required',
                    'required_with' => 'Please enter your new password',
                ],
            ],
        ],
    ],
    'favourites' => [
        'index' => [
            'alert' => [
                'error' => 'Oops! something happened try again later',
                'success' => 'Product added to favourites successfully',
                'delete' => 'Product deleted from favourites successfully',
                'exist' => 'This product already existed in your favorites list',
                'not_found' => 'This product is not found',
            ],
        ],
    ],
];
