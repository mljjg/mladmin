<?php

namespace Ml\Providers;

use Illuminate\Support\ServiceProvider;
use Ml\Console\Commands\MlAdminCommand;
use Ml\Console\Commands\MlAdminCreateController;
use Ml\Console\Commands\MlAdminCreateMigration;
use Ml\Console\Commands\MlAdminCreateMVCCommand;
use Ml\Console\Commands\MlAdminCreateView;
use Ml\Console\Commands\MlCreateUserCommand;
use Ml\Console\Commands\MlInstallCommand;
use Ml\Console\Commands\MlResetPasswordCommand;

class MlServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        MlAdminCommand::class,
        MlCreateUserCommand::class,
        MlResetPasswordCommand::class,
        MlInstallCommand::class,
        MlAdminCreateController::class,
        MlAdminCreateView::class,
        MlAdminCreateMigration::class,
        MlAdminCreateMVCCommand::class
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        ## 加载路由
        if (file_exists($routes = $this->admin_route_path('admin.php'))) {
            $this->loadRoutesFrom($routes);
        }

        if ($this->app->runningInConsole()) {
            ## 静态资源
            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/ml-admin')], 'ml-admin-assets');

            ## views
            $this->publishes([__DIR__ . '/../resources/views' => resource_path('views')], 'ml-admin-views');

            ## 配置文件
            $this->publishes([__DIR__ . '/../config/admin.php' => config_path('admin.php')], 'ml-admin-config');
            $this->publishes([__DIR__ . '/../config/site.php' => config_path('site.php')], 'ml-admin-config');
            $this->publishes([__DIR__ . '/../config/translate.php' => config_path('translate.php')], 'ml-admin-config');

            ## database migration
            $dirMigrations = __DIR__ . '/../database/migrations';
            $migrations = glob($dirMigrations . DIRECTORY_SEPARATOR . '*.stub');
            foreach ($migrations as $migration) {
                $fileName = pathinfo($migration, PATHINFO_FILENAME);
                $this->publishes([$migration => database_path('migrations').DIRECTORY_SEPARATOR."{$fileName}"], 'ml-admin-migrations');
            }
        }

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //注册命令
        $this->commands($this->commands);
    }

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')) . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }

    /**
     * 路由存储的文件路径
     * @param $path
     * @return string
     */
    function admin_route_path($path)
    {
        return config('admin.dir_route') . ($path ? DIRECTORY_SEPARATOR . $path : $path);

    }
}
