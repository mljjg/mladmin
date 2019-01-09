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

        'namespace' => 'App\\Http\\Controllers\\Admin',

        'middleware' => ['web', 'auth'],//admin
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
    | 路由存储目录
    |--------------------------------------------------------------------------
    */
    'dir_route' => base_path('routes'),

    /*
   |--------------------------------------------------------------------------
   | 控制器存储目录
   |--------------------------------------------------------------------------
   */
    'dir_controller' => app_path('Http/Controllers/Admin'),


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
        'users_table' => 'users',
        'users_model' => \App\Models\User::class,

        // Role table and model.
        'roles_table' => 'roles',
        'roles_model' => \Spatie\Permission\Models\Role::class,

        // Permission table and model.
        'permissions_table' => 'permissions',
        'permissions_model' => \Spatie\Permission\Models\Permission::class,

    ],


    //顶部导航栏
    'menu_top' => [
        [
            "id" => "dashboard",
            "text" => "仪表盘",
            "permission" => function () {
                return true;
            },
            "icon" => "",
            "route" => "admin.dashboard",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
        [
            "id" => "system",
            "text" => "系统设置",
            "permission" => function(){ return Auth::user()->can('manage_system'); },
            "icon" => "",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "system.users",
                    "text" => "用户管理",
                    "permission" => function(){ return Auth::user()->can('manage_users'); },
                    "icon" => "",
                    "route" => "admin.users",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "system.permissions",
                    "text" => "权限管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_permissions');
                    },
                    "icon" => "",
                    "route" => "admin.permissions",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "system.roles",
                    "text" => "角色管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_roles');
                    },
                    "icon" => "",
                    "route" => "admin.roles",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],

            ],
        ],
        [
            "id" => "logs",
            "text" => "日志",
            "permission" => function () {
                return Auth::user()->can('manage_menu_logs');
            },
            "icon" => "",
            "route" => "admin.logs",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
    ],

    //左侧导航栏
    'menu_left' => [
        [
            "id" => "products",
            "text" => "淘客商品",
            "permission" => function () {
                return Auth::user()->can('manage_menu_tk');
            },
            "icon" => "",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "home.banner",
                    "text" => "轮播图",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_tk_carousel');
                    },
                    "icon" => "",
                    "route" => "admin.banners",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "home.activities",
                    "text" => "活动图",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_tk_activity');
                    },
                    "icon" => "",
                    "route" => "admin.activities",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "home.navs",
                    "text" => "商品分类",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_tk_category');
                    },
                    "icon" => "",
                    "route" => "admin.navs",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],
                [
                    "id" => "product.dtk",
                    "text" => "淘客商品",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_tk_product');
                    },
                    "icon" => "",
                    "route" => "admin.dtk",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],

            ],
        ],
    ],
];