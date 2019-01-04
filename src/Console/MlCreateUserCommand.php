<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;

class MlCreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a admin user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $userModel = config('admin.database.users_model');
        $username = $this->ask('Please enter a username to login');
        $email = $this->ask('Please enter a email to login');
        $password = bcrypt($this->secret('Please enter a password to login'));
        $name = $this->ask('Please enter a name to display');
        $bool_admin = 1;## 是后台用户
        $status = 1;# 启用

        ## 创建用户
        $user = new $userModel(compact('username', 'email', 'password', 'name', 'bool_admin', 'status'));
        $user->save();

        $this->info('[' . date('Y/m/d H:i:s') . '] ' . "User [$name] created successfully.");
    }
}
