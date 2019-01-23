<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;

class MlAdminCreateMVCCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin:create-mvc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建一个模型（model）控制器(controller) 视图（view）的脚本';

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
        //可选的值
        $choices = [];
        $model = $this->ask('please enter model name: like "User"');
        $this->line('The model name : ' . $model);
        $choices[] = $model;

        ## 创建 migration
        if ($this->confirm('would you need create migration?[y/n]')) {
            $this->call('mlAdmin:create-migration', ['name' => $model]);

            ## 创建表
            if ($this->confirm('would you need exec migrate to build table in database ?[y/n]'))
                $this->call('migrate');//执行php artisan migrate
        } else {
            $this->error("give up create migration");
        }

        ## 创建模型
        if ($this->confirm('would you sure create  model [' . $model . ']? [y/n]')) {

            $modelFolder = $this->choice('What model folder ? default: Models ', ['Models', ''], 'Models');
            $this->line('the folder you choice : ' . $modelFolder);
            $this->call('make:model', ['name' => empty($modelFolder) ? ucfirst($model) : $modelFolder . '/' . ucfirst($model)]);

        } else {
            $this->error("give up create model");
        }


        ## 创建 控制器 mlAdmin:create-controller
        if ($this->confirm('would you need create a controller?[y/n]')) {
            if (!empty($model)) {
                if ($this->confirm('use model name as controller name. model:' . $model.'?[y/n]')) {
                    $controller = $model;
                }
            }

            ## 若没有model 或 未选择使用model名，则提示输入控制器名
            if (empty($controller)) {
                $controller = $this->ask('please enter controller name:like "User" or "UserController"');
                if (in_array($controller, $choices))
                    $choices[] = $controller;
            }

            ## 创建控制器 命令
            $this->call('mlAdmin:create-controller', ['name' => $controller]);
        } else {
            $this->error('give up create controller ');
        }

        ## 创建视图
        if ($this->confirm('would you need create a view?[y/n]')) {
            $view = $this->choice('choose view name:', $choices);
            if ($view) {
                ## 创建视图 mlAdmin:create-view
                $this->call('mlAdmin:create-view', ['model' => $view]);
            }
        } else {
            $this->error('give up create view');
        }

    }
}
