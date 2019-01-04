<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MlInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the admin package,initData';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * 文件系统操作工具类
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->filesystem = new Filesystem();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        //初始化数据库
//        $this->initDatabase();
        //初始化 admin 目录
        $this->initAdminDirectory();

    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate');//执行php artisan migrate

        $userModel = config('admin.database.users_model');

        if ($userModel::count() == 0) {
            //无用户填充用户
            $this->call('db:seed', ['--class' => \Ml\Auth\DataBase\Seed\AdminTableSeeder::class]);
        }
    }


    /**
     * Initialize the admAin directory.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function initAdminDirectory()
    {

        $this->directory = config('admin.directory');

//        if (is_dir($this->directory)) {
//            $this->line("<error>{$this->directory} directory already exists !</error> ");
//
//            return;
//        }

        $this->makeDir('/');

        $this->line('<info>Admin directory was created:</info> ' . str_replace(base_path(), '', $this->directory));

        $this->makeDir('Controllers');

        ## 创建 控制器
        $this->createBaseController();
        $this->createWelcomeController();
        $this->createLoginController();
        $this->createUsersController();

//        $this->createAuthController();
//        $this->createExampleController();
//
//        $this->createBootstrapFile();

        ## 路由文件目录
        $this->createRoutesFile();
    }


    /**
     * Create BaseController
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createBaseController()
    {
        $controllerFile = $this->directory . '/Controllers/BaseController.php';
        if (is_file($controllerFile)) {
            $this->error('<info>Exist BaseController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));
            return false;
        }

        $contents = $this->getStub('BaseController');

        $this->filesystem->put(
            $controllerFile,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>BaseController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));

    }

    /**
     * Create WelcomeController
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createWelcomeController()
    {

        $controllerFile = $this->directory . '/Controllers/WelcomeController.php';
        if (is_file($controllerFile)) {
            $this->error('<info>Exist WelcomeController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));
            return false;
        }
        $contents = $this->getStub('WelcomeController');

        $this->filesystem->put(
            $controllerFile,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>WelcomeController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));

    }

    /**
     * Create LoginController
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createLoginController()
    {

        $controllerFile = $this->directory . '/Controllers/LoginController.php';
        if (is_file($controllerFile)) {
            $this->error('<info>Exist LoginController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));
            return false;
        }
        $contents = $this->getStub('LoginController');

        $this->filesystem->put(
            $controllerFile,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>LoginController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));

    }

    /**
     * Create UsersController
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createUsersController()
    {

        $controllerFile = $this->directory . '/Controllers/UsersController.php';
        if (is_file($controllerFile)) {
            $this->error('<info>Exist UsersController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));
            return false;
        }

        $contents = $this->getStub('UsersController');

        $this->filesystem->put(
            $controllerFile,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>UsersController file was created:</info> ' . str_replace(base_path(), '', $controllerFile));

    }


    /**
     * Create routes file.
     *
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function createRoutesFile()
    {
        $file = $this->directory . '/routes.php';
        if (is_file($file)) {
            $this->error('<info>Exist Routes file was created:</info> ' . str_replace(base_path(), '', $file));
            return false;
        }

        $contents = $this->getStub('routes');
//        $this->laravel['files']->put($file, str_replace('DummyNamespace', config('admin.route.namespace'), $contents));
        $this->filesystem->put($file, str_replace('DummyNamespace', config('admin.route.namespace'), $contents));
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }


    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function getStub($name)
    {
        return $this->filesystem->get(__DIR__ . "/stubs/{$name}.stub");
//        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     * @return bool
     */
    protected function makeDir($path = '')
    {
        $dir = "{$this->directory}/$path";
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        return $dir;

//        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }

}
