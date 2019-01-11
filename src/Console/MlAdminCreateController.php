<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;

class MlAdminCreateController extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan create:my-controller
     * @var string
     */
    protected $signature = 'mlAdmin:create-controller {controller} {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建自定义控制器,生存默认模版';

    /**
     * 控制器名称
     * @var string
     */
    protected $controllerName;

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

        $this->controllerName = $this->argument('controller') ? $this->argument('controller') : null;
        $this->modelName = $this->argument('model') ? $this->argument('model') : '';

        ## 创建控制器目录
        $dir = config('admin.dir_controller');//app_path('Http/Controllers/Admin');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        $fileName = strpos(strtolower($this->controllerName), 'controller') ? ucfirst($this->controllerName) : ucfirst(str_plural($this->controllerName)) . 'Controller';
        $pos = strpos(strtolower($this->controllerName), 'controller');

        ## 获取model的字符串
        $model = $pos === false
            ? ucfirst(str_plural($this->controllerName, 1))
            : ucfirst(str_plural(substr($this->controllerName, 0, $pos), 1));


        ## 存储目录
        $file = $dir . "/{$fileName}.php";
        if (is_file($file)) {
            $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
            $ask = $this->ask('File is not exist ! need rewrite it[Y/N] ?');
            if (strtoupper($ask[0]) !== 'Y') {
                exit('give up rewrite !');
            }
        }


        $this->filesystem->put(
            $file,
            $this->realContent($fileName, $model)
        );

        $this->line('<info>' . $fileName . ' file was created:</info> ' . str_replace(base_path(), '', $file));

    }

    /**
     * 根据 model 的名称字符串获取字段集合的字符串
     *
     * @param string $model
     * @return string
     */
    public function getModelFields(string $model)
    {
        $columnsStr = '[]';
        try {
            $modelNew = app('App\\Models\\' . $model);
            $columns = $modelNew->getFillable();
            if (!count($columns)) {
                $table = $modelNew->getTable();
                $columns = Schema::getColumnListing($table);
            }
            $excludeFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
            $needFields = [];//需要的字段
            if (is_array($columns)) {
                foreach ($columns as $field) {
                    if (!in_array($field, $excludeFields)) {
                        $needFields[] = $field;
                    }
                }
            }


            $columnsStr = count($columns) > 0 ? "['" . implode("','", $needFields) . "']" : "[]";

        } catch (\Exception $exception) {
            $this->info($exception->getMessage());
        }

        return $columnsStr;
    }

    /**
     * 获取真实需要的文本数据
     *
     * @param $fileName
     * @param $model
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function realContent($fileName, $model)
    {
        ##用于替换 {TableFields} 的值
        $columnsStr = $this->getModelFields($model);

        ## 读取模板内容
        $tmpPath = __DIR__ . "/stubs/Templates/controller/tmp.stub";
        if (!is_file($tmpPath)) {
            $this->error('File is not exist');
            exit();
        }

        ## 读取模版内容
        $contents = $this->filesystem->get($tmpPath);

        ## 替换内容 生成控制器
        $search = [
            '{ControllerName}', '{Model}', '{modelVar}', '{folder}', '{TableFields}'
        ];

        $replace = [
            $fileName, ucfirst($model), lcfirst($model), str_plural(lcfirst($model)), $columnsStr
        ];

        return str_replace($search, $replace, $contents);
    }
}
