<?php

return [
    'contact' => [
        'first_name' => [
            'label' => 'First Name',
            'type' => 'textfield',
            'name' => 'first_name',
            'required' => true,
            'column' => 6
        ],
        'last_name' => [
            'label' => 'Last Name',
            'type' => 'textfield',
            'name' => 'last_name',
            'required' => true,
            'column' => 6
        ],
        'phone' => [
            'label' => 'Phone Number',
            'type' => 'textfield',
            'input_type' => 'tel',
            'name' => 'phone',
            'required' => false,
            'column' => 6
        ],
        'birthdate' => [
            'label' => 'Birthdate',
            'type' => 'datefield',
            'name' => 'birthdate',
            'required' => false,
            'column' => 6
        ],
        'city' => [
            'label' => 'City',
            'type' => 'textfield',
            'name' => 'city',
            'required' => false,
            'column' => 12
        ],
    ],

    'department' => [
        'name' => [
            'label' => 'Department Name',
            'type' => 'textfield',
            'name' => 'name',
            'required' => true,
            'column' => 12
        ],
    ],

    'user' => [
        'name' => [
            'label' => 'Full Name',
            'type' => 'textfield',
            'name' => 'name',
            'required' => true,
            'column' => 12
        ],
        'email' => [
            'label' => 'Email Address',
            'type' => 'textfield',
            'input_type' => 'email',
            'name' => 'email',
            'required' => true,
            'column' => 12
        ],
        'password' => [
            'label' => 'Password',
            'type' => 'textfield',
            'input_type' => 'password',
            'name' => 'password',
            'required' => true,
            'column' => 6
        ],
        'password_confirmation' => [
            'label' => 'Confirm Password',
            'type' => 'textfield',
            'input_type' => 'password',
            'name' => 'password_confirmation',
            'required' => true,
            'column' => 6
        ],
    ],
];
