<?php

return [
    'offers' => [
        'routes'    => [
            'index'     => 'Offers',
            "create"    => "Create offer",
            "update"    => "Edit offer"
        ],
        'datatable' => [
            'title' => 'Title',
            'description' => 'Description',
            "status"  => "Status",
            "sort"    => "Order",
            'created_at' => 'Created At',
            'options' => 'Options',
        ],
        'form'      => [
            'title' => 'Title',
            'description' => 'Description',
            'is_free' => 'Is Free',
            'duration'    => 'meals count',
            'notes' => 'notes',
            "price"   => "Price",
            "status"  => "Status",
            'image' => 'image',
            'products'  => 'Products',
            'country'   => 'Country',
            "categories" => "Categories",
            "sort"    => "Order",
            "free_products" => 'Free Products',
            'choose_free' => 'Choose (:qty) For Free',
            'tabs'      => [
                'general'   => 'General Info.',
                'prices'   => 'Package Prices',
            ],
            'prices' => [
                'price' => 'Price',
                'qty'   => 'Quantity',
                'free_qty'   => 'Free Quantity when you get the offer',
                'offer_percentage' => 'Offer Percentage',
                'start_date'    => 'Start Date',
                'end_date'      => 'End Date',
                'days_count'    => 'Days Count',
                'limited'       => 'Limited?',
            ],
        ],

    ],
    'packages' => [
        'select_offer'  => 'Select Offer',
        'days'  => 'Days',
        'routes'    => [
            'index'     => 'Packages',
            "create"    => "Create package",
            "update"    => "Edit package"
        ],
        'datatable' => [
            'title' => 'Title',
            'description' => 'Description',
            'duration' => 'Duration',
            'is_free' => 'Is Free',
            "price"   => "Price",
            "status"  => "Status",
            "sort"    => "Order",

            'created_at' => 'Created At',
            'options' => 'Options',
        ],
        'form'      => [
            'title' => 'Title',
            'description' => 'Description',
            'is_free' => 'Is Free',
            'duration'    => 'meals count',
            'notes' => 'notes',
            "price"   => "Price",
            "status"  => "Status",
            'image' => 'image',
            'products'  => 'Products',
            'country'   => 'Country',
            "categories" => "Categories",
            "sort"    => "Order",
            'tabs'      => [
                'general'   => 'General Info.',
                'prices'   => 'Package Prices',
            ],
            'prices' => [
                'price' => 'Price',
                'qty'   => 'Quantity',
                'offer_percentage' => 'Offer Percentage',
                'start_date'    => 'Start Date',
                'end_date'      => 'End Date',
                'days_count'    => 'Days Count',
                'limited'       => 'Limited?',
            ],
        ],

    ],
    'subscriptions' => [
        'routes'    => [
            'index'     => 'subscriptions',
            "create"    => "Create subscription",
            "update"    => "Edit subscriptions"
        ],
        'datatable' => [
            'title'        => 'Title',
            'description'  => 'Description',
            'duration'     => 'Duration',
            'user'         => 'user',
            'package'      => 'package',
            'from_admin'   => 'from admin',
            'is_free'      => 'Is Free',
            'is_default'   => 'default package',
            'can_order_in' => 'can order in',

            "price"   => "Price",
            "start_at"   => "start_at",
            "end_at"   => "end_at",
            "status"  => "Status",
            "sort"    => "Order",
            'created_at' => 'Created At',
            'options' => 'Options',
            'note' => 'Note',
            'coupon' => 'Coupon',
            'no_coupon' => 'No Coupon',
            'pause' => 'Pause',
            'pause_active' => 'Paused now',
            'pause_stoped' => 'Not Paused',
        ],
        'form'      => [
            'title' => 'Title',
            'description' => 'Description',
            'duration'    => 'meals count',
            'is_free'     => 'Is Free',
            "price"       => "Price",
            "status"      => "Status",
            'image'       => 'image',

            "categories" => "Categories",
            "sort"    => "Order",
            'tabs'      => [
                'general'   => 'General Info.',
            ],
        ],

    ],
    'suspensions' => [
        'routes'    => [
            'index'     => 'suspensions',
            "create"    => "Create suspensions",
            "update"    => "Edit suspensions"
        ],
        'datatable' => [
            'title' => 'Title',
            'description' => 'Description',
            'duration'   => 'Duration',
            'user'       => 'user',
            'package' => 'package',
            'from_admin' => 'from admin',
            "start_at"   => "start at",
            "end_at"   => "end at",
            'is_free' => 'Is Free',
            "price"   => "Price",
            "start_at"   => "start at",
            "end_at"   => "end at",
            "status"  => "Status",
            "sort"    => "Order",

            'created_at' => 'Created At',
            'options' => 'Options',
        ],
        'form'      => [
            'title' => 'Title',
            'description' => 'Description',
            'duration' => 'Duration',
            'is_free' => 'Is Free',
            "price"   => "Price",
            'user' => 'user',
            "notes"   => "notes",
            "start_at"   => "start at",
            "end_at"   => "end at",
            "status"  => "Status",
            'image' => 'image',

            "categories" => "Categories",
            "sort"    => "Order",
            'tabs'      => [
                'general'   => 'General Info.',
            ],
        ],

    ],

    'print-settings'      => [
        'datatable' => [
            'created_at'    => 'Created At',
            'date_range'    => 'Search By Dates',
            'image'         => 'Image',
            'options'       => 'Options',
            'status'        => 'Status',
            'preview'         => 'preview',
            'name'         => 'Name',
            "description"         => "Description",
           "paper_width"        => "Paper Width"
        ],
        'form'      => [
            'status'        => 'Status',
            'name'         => 'Name',
            "description"         => "Description",
           "paper_width"        => "Paper Width",
           "is_continuous" => "Is continuous" ,
           "top_margin"    => "Top margin",
           "left_margin"   =>"Left Margin",
           "paper_width"=> "Paper width",
           "width"         =>"Width",
           "height"         =>"Height",
           "paper_width"   =>"Paper width",
           "paper_height"  =>"Paper height",
           "stickers_in_one_row"=>"Stickers in one row",
           "row_distance"=>"Row distance",
           "col_distance"  => "Col distance",
           "stickers_in_one_sheet"=>"Stickers in one sheet ",
            'tabs'              => [
                'general'       => 'General Info.',
                "input_lang"    =>"Data :lang"
            ],

        ],
        'routes'    => [

            'create'=> 'Create Print Setting',
            'index' => 'Print Setting',
            'update'=> 'Update Print Setting',
        ],

    ],
    'print' => [
        'datatable' => [
            "show_in" => "Information to show in Labels " ,
            "preview" =>"Preview"
        ],
        'form' => [

        ],
        'routes' => [
            'index' => 'Print',
        ],
        'validation' => [

        ],
    ],
];
