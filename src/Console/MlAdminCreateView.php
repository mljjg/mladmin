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
                if (is_file($file)) {
                    $this->error('<info>' . $fileName . ' file was Existed:</info> ' . str_replace(base_path(), '', $file));
                    $ask = $this->ask('Need rewrite it[Y/N] ?');
                    if (strtoupper($ask[0]) !== 'Y') {
                        $this->info('give up rewrite ' . $fileName);
                        continue;
                    }
                }
            }

            $content = $this->realContent($fileName, $model, $folder, $title);

            $this->info($file);
            $this->filesystem->put(
                $file,
                $content
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
    public function realContent($fileName, $model, $folder, $title)
    {
        ##用于替换 {TableFields} 的值
        $columns = $this->getModelFields($model);
        $templateCols = '';
        $templateFormItem = '';
        $modelVar = lcfirst($model);
        $excludeFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
//        $excludeFields = ['id' => 'ID', 'created_at' => '创建时间', 'updated_at' => '更新时间', 'deleted_at' => '删除时间'];

        foreach ($columns as $columnMap) {
            $column = $columnMap['column'];
            $columnName = $columnMap['columnComment'];

            if (!in_array($column, $excludeFields)) {
                $templateCols .= "                    , {field: '{$column}', title: '{$columnName}', width: 150}" . PHP_EOL;

                $templateFormItem .= <<<Item
             <div class="layui-form-item">
                    <label class="layui-form-label">{$columnName}</label>
                    <div class="layui-input-block">
                        <input type="text" name="{$column}" required lay-verify="required" placeholder="请输入{$columnName}" autocomplete="off" class="layui-input" value="{{ old('{$column}',$==modelVar==->{$column}) }}">
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
            '==TemplateCols==', '==item==', '==modelVar==', '==folder==', '==Title=='
        ];

        $replace = [
            $templateCols, $templateFormItem, $modelVar, $folder, $title
        ];

        return str_replace($search, $replace, $contents);
    }

    /**
     * 返回模型的所有字段
     * @param string $model
     * @param string $prefix
     * @return array
     */
    function getModelFields(string $model, $prefix = 'App\\Models\\')
    {
        $result = [];
        try {

            $modelNew = app($prefix . $model);
            $table = $modelNew->getTable();
            // 获取整张表的详细信息
            $columns = \DB::getDoctrineSchemaManager()->listTableDetails($table);
            $columnFields = Schema::getColumnListing($table);
            if (is_array($columnFields)) {
                foreach ($columnFields as $column) {
                    // 获取注释
                    $columnComment = $columns->getColumn($column)->getComment();
                    if (empty($columnComment)) {
                        //则使用翻译
                        $columnComment = $this->fieldMeans($column);
                    }

                    $result[] = ['column' => $column, 'columnComment' => $columnComment];

                }
            }

        } catch (\Exception $exception) {
            //mysql5.7 字段类型 enum,set等会不支持，采用翻译
            $this->error($exception->getMessage());
            $this->info('采用翻译获取字段含义');
        }


        //结果集为空，翻译字段含义
        if (empty($result)) {
            $columnFields = Schema::getColumnListing($table);
            if (is_array($columnFields)) {
                foreach ($columnFields as $column) {
                    //字段（列）含义
                    $columnName = $this->fieldMeans($column);
                    //组装数据
                    $result[] = ['column' => $column, 'columnComment' => $columnName];

                }
            }
        }
        return $result;
    }

    /**
     * 字段含义
     * @param $column
     * @return mixed|string
     */
    public function fieldMeans($column)
    {
        $columnName = strtoupper($column);
        try {
            $columnName = $this->translateEnToZh($column);
        } catch (\Exception $exception) {
            if ($exception instanceof TranslateException) {
                $columnName = strtoupper($column);
            }
        }
        return $columnName;
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
