<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-27
 * Time: 15:16
 */

return [
    // 后台的 URI
    'uri' => 'admin',//administrator

    // 后台专属域名，没有的话可以留空
//    'domain' => '',

    'paginate' => [
        'limit' => 10,
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-admin route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the admin page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => 'admin',

        'namespace' => 'App\\Admin\\Controllers',

        'middleware' => ['web','auth'],//admin
    ],

    /*
    |--------------------------------------------------------------------------
    | Ml-admin install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the administration page. The default is `app/Admin`, which must
    | be set before running `artisan admin::install` to take effect.
    |
    */
    'directory' => app_path('Admin'),

    /*
   |--------------------------------------------------------------------------
   | Laravel-admin database settings
   |--------------------------------------------------------------------------
   |
   | Here are database settings for laravel-admin builtin model & tables.
   |
   */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'admin_users',
        'users_model' => \Ml\Models\User::class,
    ],


    //顶部导航栏
    'menu_top'=>[
        [
            "id" => "dashboard",
            "text" => "控制台",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "admin.dashboard",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
        [
            "id" => "dashboard",
            "text" => "系统设置",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "users",
                    "text" => "用户管理",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.users",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "users",
                    "text" => "权限管理",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.users",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "users",
                    "text" => "角色管理",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.users",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],

            ],
        ],
    ],

    //左侧导航栏
    'menu_left'=>[
        [
            "id" => "dashboard",
            "text" => "所有商品",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "admin.dashboard",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "dashboard",
                    "text" => "列表一",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "dashboard",
                    "text" => "列表二",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "dashboard",
                    "text" => "列表三",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
            ],
        ],
        [
            "id" => "dashboard",
            "text" => "解决方案",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "admin.dashboard",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "dashboard",
                    "text" => "解决一",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "dashboard",
                    "text" => "解决二",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "dashboard",
                    "text" => "解决三",
                    "permission" => function(){ return true; },
                    "icon" => "",
                    "route" => "admin.dashboard",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
            ],
        ],

        [
            "id" => "dashboard",
            "text" => "云市场",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "admin.dashboard",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
        [
            "id" => "dashboard",
            "text" => "发布商品",
            "permission" => function(){ return true; },
            "icon" => "",
            "route" => "admin.dashboard",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [],
        ],
    ],
];