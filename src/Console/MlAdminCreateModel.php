<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use ShaoZeMing\Translate\Exceptions\TranslateException;
use Translate;

class MlAdminCreateModel extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan create:my-controller
     * @var string
     */
    protected $signature = 'mlAdmin:create-model {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建自定义模型,继承BaseModel';

    /**
     * 控制器名称
     * @var string
     */
    protected $name;

    /**
     * 模型
     * @var string
     */
    protected $modelName;

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
        //

        $this->name = $this->argument('name') ? $this->argument('name') : null;

        ## 创建控制器目录
        $dir = config('admin.dir_model') ?? app_path('Models');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        $fileName = ucfirst($this->name);
        //strpos(strtolower($this->name), 'controller') ? ucfirst($this->name) : ucfirst(str_plural($this->name)) . 'Controller';
        $model = $fileName;

        ## 存储目录
        $file = $dir . "/{$fileName}.php";
        if (is_file($file)) {
            $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
            $ask = $this->ask('Need rewrite it[Y/N] ?');
            if (strtoupper($ask[0]) !== 'Y') {
                exit('give up rewrite !');
            }
        }


        //App\Models
        $this->filesystem->put(
            $file,
            $this->realContent($model)
        );

        $this->line('<info>' . $fileName . ' file was created:</info> ' . str_replace(base_path(), '', $file));

    }


    /**
     * 获取真实需要的文本数据
     *
     * @param $model
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function realContent($model)
    {
        ## 读取模板内容
        $tmpPath = __DIR__ . "/stubs/Templates/model/tmp.stub";
        if (!is_file($tmpPath)) {
            $this->error('File is not exist');
            exit();
        }

        ## 读取模版内容
        $contents = $this->filesystem->get($tmpPath);

        ## 替换内容 生成控制器
        $search = [
            'DummyClass'
        ];

        $replace = [
            $model
        ];

        return str_replace($search, $replace, $contents);
    }

}
