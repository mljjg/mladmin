<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use ShaoZeMing\Translate\Exceptions\TranslateException;
use Translate;

class MlAdminCreateView extends Command
{
    /**
     * The name and signature of the console command.
     * php artisan create:my-view Model
     * @var string
     */
    protected $signature = 'mlAdmin:create-view {model} {title?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '根据model 创建视图文件，例如 mlAdmin:create-view File 文件列表';

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
        $model = $this->argument('model') ? ucfirst($this->argument('model')) : '';
        $title = $this->argument('title') ? $this->argument('title') : '资料';
        $folder = str_plural(lcfirst($model));

        ## 创建控制器目录
        $dir = base_path('resources/views/backend/' . $folder);//app_path('Http/Controllers/Admin');
        if (!is_dir($dir))
            $this->filesystem->makeDirectory($dir, 0755, true, true);

        $files = [
            'index' => '列表 blade文件',
            'create_edit' => '添加和编辑 blade文件',
        ];

        foreach ($files as $fileName => $fileTitle) {
            $file = $dir . "/{$fileName}.blade.php";
            if (is_file($file)) {
                $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
                continue;
            }

            $this->info($file);
            $this->filesystem->put(
                $file,
                $this->realContent($fileName, $model, $folder,$title)
            );

            $this->line('<info>' . $fileName . '(' . $fileTitle . ') file was created:</info> ' . str_replace(base_path(), '', $file));
        }

    }

    /**
     * 获取真实需要的文本数据
     *
     * @param $fileName
     * @param $model
     * @param $folder
     * @param $title
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function realContent($fileName, $model, $folder,$title)
    {
        ##用于替换 {TableFields} 的值
        $columns = $this->getModelFields($model);
        $templateCols = '';
        $templateFormItem = '';
        $modelVar = lcfirst($model);
        $excludeFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
        foreach ($columns as $column) {
            $columnName = strtoupper($column);
            //翻译字段
            try {
                $columnName = $this->translateEnToZh($column);
            } catch (\Exception $exception) {
                if ($exception instanceof TranslateException) {
                    $columnName = strtoupper($column);
                }
            }

            if (!in_array($column, $excludeFields)) {
                $templateCols .= "                    , {field: '{$column}', title: '{$columnName}', width: 150}" . PHP_EOL;

                $templateFormItem .= <<<Item
             <div class="layui-form-item">
                    <label class="layui-form-label">{$columnName}</label>
                    <div class="layui-input-block">
                        <input type="text" name="{$column}" required lay-verify="required" placeholder="请输入 {$column}" autocomplete="off" class="layui-input" value="{{ old('{$column}',$==modelVar==->{$column}) }}">
                    </div>
                </div>
Item;

            }

        }

        ## 读取模板内容
        $tmpPath = __DIR__ . "/stubs/Templates/views/{$fileName}.stub";
        if (!is_file($tmpPath)) {
            $this->error('File is not exist');
            exit();
        }

        ## 读取模版内容
        $contents = $this->filesystem->get($tmpPath);

        ## 替换内容 生成控制器
        $search = [
            '==TemplateCols==', '==item==', '==modelVar==', '==folder==','==Title=='
        ];

        $replace = [
            $templateCols, $templateFormItem, $modelVar, $folder,$title
        ];

        return str_replace($search, $replace, $contents);
    }

    /**
     * 返回模型的所有字段
     *
     * @param string $model
     * @param string $prefix
     * @return array|string
     */
    function getModelFields(string $model, $prefix = 'App\\Models\\')
    {
        try {
            $modelNew = app($prefix . $model);
            $columns = $modelNew->getFillable();
            if (!count($columns)) {
                $table = $modelNew->getTable();
                $columns = Schema::getColumnListing($table);
            }

        } catch (\Exception $exception) {
            return $exception->getMessage();
        }

        return $columns;
    }

    /**
     * @param string $words
     * @return mixed
     * @throws \ShaoZeMing\Translate\Exceptions\TranslateException
     */
    public function translateEnToZh(string $words)
    {
        // 更换翻译语言 可选语言请看配置文件中可定义的几种
        $from = "en";
        $to = "zh";
        $result = Translate::setFromAndTo($from, $to)->translate($words);
        $this->info(__FUNCTION__ . '：' . $words . ' --> ' . $result);
        return $result;
    }
}
