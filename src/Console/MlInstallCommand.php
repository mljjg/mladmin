<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MlInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan mlAdmin:install
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
        $this->initDatabase();
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

        ## 创建模型
        $this->createModelFiles();

        ## 创建 控制器
        $this->createControllerFiles();

        ## 创建用户的 策略类
        $this->createPolicyFiles();

        ## 路由文件目录
        $this->createRoutesFile();

        ## 重写异常类
        $this->rewriteExceptionHandler();

    }

    /**
     * 创建 models
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createModelFiles()
    {
        ## 创建 Models 目录
        $dir = app_path('Models');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        ## 创建基础策略类
        $fileModels = [
            'BaseModel' => '基础模型类',
            'User' => '用户模型类',
        ];

        foreach ($fileModels as $fileName => $fileTitle) {
            $file = $dir . "/{$fileName}.php";
            if (is_file($file)) {
                $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
                continue;
            }

            $contents = $this->getStub("Models/{$fileName}");
            $this->filesystem->put($file, $contents);

            $this->line('<info>' . $fileName . '(' . $fileTitle . ') file was created:</info> ' . str_replace(base_path(), '', $file));
        }

    }

    /**
     * 创建 控制器
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createControllerFiles()
    {

        ## 创建控制器目录
        $dir = config('admin.dir_controller');//app_path('Http/Controllers/Admin');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        ## 创建基础策略类
        $fileModels = [
            'BaseController' => '基础控制器类',
            'WelcomeController' => '欢迎控制器类',
            'LoginController' => '登录控制器类',
            'UsersController' => '用户控制器类',
            'PermissionsController' => '权限控制器类',
            'RolesController' => '角色控制器类',
        ];

        foreach ($fileModels as $fileName => $fileTitle) {
            $file = $dir . "/{$fileName}.php";
            if (is_file($file)) {
                $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
                continue;
            }

            $contents = $this->getStub("Controllers/{$fileName}");
            $this->filesystem->put(
                $file,
                str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
            );

            $this->line('<info>' . $fileName . '(' . $fileTitle . ') file was created:</info> ' . str_replace(base_path(), '', $file));
        }
    }

    /**
     * Create routes file.
     *
     * @return bool
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createRoutesFile()
    {
        $dir = config('admin.dir_route');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        $file = $dir . '/admin.php';
        if (is_file($file)) {
            $this->error('<info>Routes file was Existed:</info> ' . str_replace(base_path(), '', $file));
            return false;
        }

        $contents = $this->getStub('routes');
        $this->filesystem->put($file, $contents);
        $this->line('<info>Routes file was created:</info> ' . str_replace(base_path(), '', $file));
    }

    /**
     * 创建 策略类
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function createPolicyFiles()
    {
        ## 创建策略类目录
        $dir = app_path('Policies');//app/Policies
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        ## 创建基础策略类
        $policies = [
            'Policy' => '基础策略类',
            'PermissionPolicy' => '权限策略类',
            'RolePolicy' => '角色策略类',
            'UserPolicy' => '用户策略类',
        ];

        foreach ($policies as $fileName => $fileTitle) {
            $file = $dir . "/{$fileName}.php";
            if (is_file($file)) {
                $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
                continue;
            }
            $contents = $this->getStub("Policies/{$fileName}");
            $this->filesystem->put($file, $contents);

            $this->line('<info>' . $fileName . '(' . $fileTitle . ') file was created:</info> ' . str_replace(base_path(), '', $file));

        }

    }

    /**
     * 重写 异常处理类
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function rewriteExceptionHandler()
    {
        ## 创建策略类目录
        $dir = app_path('Exceptions');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        $file = $dir . '/Handler.php';
        $answer = 'y';
        if (is_file($file)) {
            $answer = $this->ask('<info>Handler file was Existed:</info> ' . str_replace(base_path(), '', $file) . ', <info>Rewrite it</info>[y/n]?');
        }

        if (strtoupper($answer[0]) === 'Y') {
            $contents = $this->getStub("Handler");
            $this->filesystem->put($file, $contents);
            if (is_file($file))
                $this->line('<info>Exceptions/Handler file was rewrite:</info> ' . str_replace(base_path(), '', $file));
            else
                $this->line('<info>Exceptions/Handler file was created:</info> ' . str_replace(base_path(), '', $file));

        } else {
            $this->line('<info>Exceptions/Handler file was give up:</info> ' . str_replace(base_path(), '', $file));

        }

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
        $realPath = __DIR__ . "/stubs/{$name}.stub";
        if (!is_file($realPath)) {
            $this->info('File is not exist');
        }

        return $this->filesystem->get($realPath);
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
