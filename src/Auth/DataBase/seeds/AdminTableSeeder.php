<?php

namespace Ml\Auth\DataBase\Seed;

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ## 创建管理员用户
        $userModel = config('admin.database.users_model');
        $username = 'admin';
        $password = bcrypt('ml123456');
        $name = 'Admin';
        $bool_admin = 1;
        $status = 1;
        $user = new $userModel(compact('username', 'password', 'name', 'bool_admin', 'status'));
        $user->save();

        ## 创建 角色

        ## 给管理员绑定角色

    }
}
