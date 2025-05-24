<?php

return [
/* you can add your own middleware here */
	
   'middleware' => [],

/* you can set your own table prefix here */
    'table_prefix' => 'admin_',

/* you can set your own table names */
    'table_name_menus' => 'menus',

    'table_name_items' => 'menu_items',

/* you can set your route path*/
    'route_path' => '/tocaan/',

/* here you can make menu items visible to specific roles */
    'use_roles' => false,

/* If use_roles = true above then must set the table name, primary key and title field to get roles details */
    'roles_table' => 'roles',

    'roles_pk' => 'id', // primary key of the roles table

    'roles_title_field' => 'name', // display name (field) of the roles table

    'controller_name' => '\Modules\Apps\Http\Controllers\Dashboard\HeadermenuController',
    
    'add_items_forms' => [
        'custom_link' => [
            'view_path' => 'wmenu::components.additems.custom_link',
            'validation' => [
                'type'  => 'required',
                'label.*' => 'required',
                'url.*' => 'required',
                'menu' => 'required',
            ]
        ],
        'category' => [
            'view_path' => 'wmenu::components.additems.categories',
            'validation' => [
                'category_id'  => 'required|exists:categories,id',
                'menu' => 'required',
            ]
        ],
        'category_list' => [
            'view_path' => 'wmenu::components.additems.category-list',
            'validation' => [
                'label.*' => 'required',
                'category_id'  => 'required',
                'menu' => 'required',
                'menu_type' => 'required',
            ]
        ],
        'page' => [
            'view_path' => 'wmenu::components.additems.page',
            'validation' => [
                'page_id'  => 'required',
                'menu' => 'required',
                'label.*' => 'required',
            ]
        ],
    ],
];
