<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndAlertFieldForUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });

        ## 修改字段:确保将 doctrine/dbal 依赖添加到 composer.json
        # https://laravel-china.org/docs/laravel/5.5/migrations/1329#3a73db
        # composer require doctrine/dbal
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->comment('用户登录账号')->change();
            $table->string('username')->nullable()->comment('后台用户登录账号')->unique();
            $table->integer('created_sec')->comment('创建时间');
            $table->integer('updated_sec')->comment('更新时间');
            $table->tinyInteger('sex')->comment('性别:0-女 1-男')->default(0);
            $table->integer('login_at')->comment('上次登录时间')->nullable();
            $table->string('login_ip', 64)->comment('上次登录IP:127.255.255.255')->nullable();
            $table->string('avatar')->comment('头像链接或者存储路径')->nullable();
            $table->tinyInteger('bool_admin')->comment('是否后台用户，1:是，0:否')->default(0);
            $table->tinyInteger('status')->comment('状态，2:已停用 1:启用，0:未激活')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('sex');
            $table->dropColumn('status');
            $table->dropColumn('login_at');
            $table->dropColumn('login_ip');
            $table->dropColumn('avatar');
            $table->dropColumn('created_sec');
            $table->dropColumn('updated_sec');
        });
    }
}
