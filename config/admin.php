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
    | ORM存储目录
    |--------------------------------------------------------------------------
    */
    'dir_model' => app_path('Models'),

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
            "icon" => "layui-icon layui-icon-console",
            "route" => "admin.dashboard",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [],
        ],
        [
            "id" => "system",
            "text" => "系统设置",
            "permission" => function () {
                return Auth::user()->can('manage_system');
            },
            "icon" => "layui-icon layui-icon-set",
            "route" => "",
            "params" => [],
            "query" => [],
            "link" => "",
            "children" => [
                [
                    "id" => "system.users",
                    "text" => "用户管理",
                    "permission" => function () {
                        return Auth::user()->can('manage_users');
                    },
                    "icon" => "layui-icon layui-icon-user",
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
                    "icon" => "layui-icon layui-icon-auz",
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
                    "icon" => "layui-icon layui-icon-group",
                    "route" => "admin.roles",
                    "params" => [],
                    "query" => [],
                    "link" => "",
                ],

            ],
        ],
        [
            "id" => "other",
            "text" => "其他功能",
            "permission" => function () {
                return Auth::user()->can('manage_menu_other');
            },
            "icon" => "layui-icon layui-icon-util",
            "route" => "admin.logs",//优先级第二
            "params" => [],
            "query" => [],//优先级第三
            "link" => "",//优先级第一
            "children" => [
                [
                    "id" => "logs",
                    "text" => "系统日志",
                    "permission" => function () {
                        return Auth::user()->can('manage_menu_logs');
                    },
                    "icon" => "layui-icon layui-icon-file",
                    "route" => "admin.logs",//优先级第二
                    "params" => [],
                    "query" => [],//优先级第三
                    "link" => "",//优先级第一
                ]
            ],
        ],

    ],

    //左侧导航栏
    'menu_left' => [

    ],
];