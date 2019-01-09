<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SeedRolesAndPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'manage_system', 'remarks' => '系统设置']);
        Permission::create(['name' => 'manage_users', 'remarks' => '用户管理']);
        Permission::create(['name' => 'manage_permissions', 'remarks' => '权限管理']);
        Permission::create(['name' => 'manage_roles', 'remarks' => '角色管理']);


        // 创建管理角色，并赋予权限
        $admin = Role::create(['name' => 'Admin', 'remarks' => '管理员']);
        $admin->givePermissionTo('manage_system', 'manage_users', 'manage_permissions', 'manage_roles');

        $founder = Role::create(['name' => 'Founder', 'remarks' => '站长']);
        $founder->givePermissionTo('manage_system', 'manage_users');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
