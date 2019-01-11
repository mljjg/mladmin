<?php

namespace Ml\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use ShaoZeMing\Translate\Exceptions\TranslateException;
use Translate;

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
            $ask = $this->ask('Need rewrite it[Y/N] ?');
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
     * 字段数据
     * @param $columns
     * @return array
     */
    public function getModelFieldsMapStr($columns)
    {

        $excludeFields = ['id' => 'ID', 'created_at' => '创建时间', 'updated_at' => '更新时间', 'deleted_at' => '删除时间'];
        $needFields = [];//需要的字段(一般指可手动更新字段)
        $mapFields = [];//全部字段的字典
        if (is_array($columns)) {
            foreach ($columns as $column) {
                $field = $column['column'];
                $columnComment = $column['columnComment'];

                if (!isset($excludeFields[$field])) {
                    $needFields[] = $field;
                } else {
                    $columnComment = $excludeFields[$field];//若是指定的字段，直接采用设置的含义
                }

                $mapFields[] = '"' . $field . '"=>"' . $columnComment . '"';
            }
        }

        $columnsStr = count($needFields) > 0 ? "['" . implode("','", $needFields) . "']" : "[]";
        $columnsMapStr = count($mapFields) > 0 ? "[" . implode(",", $mapFields) . "]" : "[]";


        return ['fieldsStr' => $columnsStr, 'fieldsMpaStr' => $columnsMapStr];
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
        $columns = $this->getModelFields($model);

        ##
        $fieldsResult = $this->getModelFieldsMapStr($columns);
        $fieldsStr = $fieldsResult['fieldsStr'];
        $fieldsMpaStr = $fieldsResult['fieldsMpaStr'];

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
            '==ControllerName==', '==Model==', '==modelVar==', '==folder==', '==Fields==', '==FieldsMap=='
        ];

        $replace = [
            $fileName, ucfirst($model), lcfirst($model), str_plural(lcfirst($model)), $fieldsStr, $fieldsMpaStr
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
