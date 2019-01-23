<?php

namespace Ml\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MlAdminCreateMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mlAdmin:create-migration {name : The name of the table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create migration which you can input fields in console!';

    /**
     * @var Composer
     */
    private $composer;

    /**
     * 文件系统操作工具类
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var
     */
    private $cache_key = 'jjg.admin.table.columns';

    /**
     * Create a new command instance.
     *
     * @param Composer $composer
     * @param Filesystem $filesystem
     */
    public function __construct(Composer $composer, Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->composer = $composer;
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
        $table = $this->argument('name');
        $table = str_plural($table);
        ## migration 的类名
        $dummyClass = 'Create' . ucfirst($table) . 'Table';
        ## migration 的文件名
        $name = str::snake($dummyClass);

        ## 操作的数据库的表名
        if (empty($table)) {
            if (preg_match('/^create_(\w+)_table$/', $name, $matches)) {
                $table = $matches[1];
                $this->info('table:' . $table);
            }
        }

        ## 缓存常用字段
        $columns = $this->getCacheCols();


        $table_fields = [];

        ## 选择操作类型
        SelectOption:
        $option = $this->choice('Please select option in ', ['ADD_FIELD', 'DELETE_FIELD', 'SET_FIELD_PROPERTY', 'SHOW_FIELD_PROPERTY', 'BUILD']);

        switch ($option) {
            case 'ADD_FIELD':
                ## 新增字段
                ADD_FIELD:
                $field = $this->anticipate('enter field name', $columns);
                if (!in_array($field, $columns)) {
                    $columns[] = $field;
                    $this->setCacheCol($columns);
                }
                ##记录字段名
                $table_fields[$field]['field'] = $field;
                ## 选择步骤
                $choose = $this->choice('Choose step,default', ['Continue', 'GoBack'], 0);
                if ($choose == 'Continue') {
                    goto ADD_FIELD;
                } else {
                    goto  SelectOption;
                }
                break;
            case 'SET_FIELD_PROPERTY':
                ## 设置字段属性
                SET_FIELD_PROPERTY:

                ## 已有的字段
                $fields_added = array_column($table_fields, 'field');
                $field_choose = $this->choice('Choose edit field ', $fields_added);

                ##已选字段：直接调整到此处 （$field_choose）
                SET_FIELD_PROPERTY_SELECTED:

                $item = $table_fields[$field_choose];
                $property_choose = $this->choice('Choose property', ['Type', 'Length', 'Unsigned', 'AllowNull', 'Default', 'Comment']);
                switch ($property_choose) {
                    case 'Type':
                        $types = ['string', 'integer', 'bigInteger', 'tinyInteger', 'smallInteger', 'mediumInteger', 'char',
                            'text', 'longText', 'timestamp', 'decimal', 'json'];

                        $table_fields[$field_choose]['type'] = $this->choice('Choose field type', $types);
                        break;
                    case 'Length':
                        $table_fields[$field_choose]['length'] = $this->ask('Enter the length of field "' . $field_choose);
                        break;
                    case 'Unsigned':
                        $table_fields[$field_choose]['unsigned'] = $this->choice('Choose the sign of field "' . $field_choose, ['unsigned', 'signed']);
                        break;
                    case 'AllowNull':
                        $table_fields[$field_choose]['allow_null'] = $this->choice('Choose field null allow or not ', ['allow_null', 'not_allow_null']);

                        break;
                    case 'Default':
                        $table_fields[$field_choose]['default'] = $this->ask('Enter field default value ');

                        break;
                    case 'Comment':
                        $table_fields[$field_choose]['comment'] = $this->ask('Enter field Comment');
                        break;
                }

                ## 选择步骤
                $choose = $this->choice('Choose step,default', ['Continue', 'GoBack'], 0);
                if ($choose == 'Continue') {
                    goto SET_FIELD_PROPERTY;
                } else {
                    goto  SelectOption;
                }
                break;
            case 'DELETE_FIELD':
                ## 删除字段
                DELETE_FIELD:
                ## 已有的字段
                $fields_added = array_column($table_fields, 'field');
                $field_choose = $this->choice('choose edit field ', $fields_added);
                ##删除字段记录
                if (isset($table_fields[$field_choose])) {
                    unset($table_fields[$field_choose]);

                }
                ## 选择步骤
                $choose = $this->choice('Choose step,default', ['Continue', 'GoBack'], 0);
                if ($choose == 'Continue') {
                    goto DELETE_FIELD;
                } else {
                    goto  SelectOption;
                }
                break;
            case 'SHOW_FIELD_PROPERTY':
                SHOW_FIELD_PROPERTY:

                $this->info(var_export($table_fields, true));
                $choose = $this->choice('Choose step,default', ['Continue', 'GoBack'], 0);
                if ($choose == 'Continue') {
                    goto SHOW_FIELD_PROPERTY;
                } else {
                    goto  SelectOption;
                }
                break;

        }

        ## 拼装 建表字符串
        $DummyFields = '';//
        if ($table_fields && count($table_fields)) {
            $field_tpl = '$table->%s("%s",%d)->comment("%s")';//->default(%s)
            foreach ($table_fields as $item) {
                $field = $item['field'];

                if (!isset($item['type'])) {

                    $this->error('no type,field:' . $field);
                    goto SelectOption;
                }
                $type = $item['type'];

                $length = isset($item['length']) ? $item['length'] : 0;//可能不是数值，例如 decimal(price,19,2)

                $comment = empty($item['comment']) ? $item['comment'] : $field;//默认为字段名
                if ($length) {
                    $tpl = sprintf($field_tpl, $type, $field, $length, $comment);
                } else {
                    $tpl = sprintf('$table->%s("%s")->comment("%s")', $type, $field, $comment);
                }

                if (!empty($nullable) && $nullable == 'null') {
                    $tpl .= '->nullable()';
                }
                if (isset($set_default) && $set_default == 'yes') {
                    if (isset($default)) {
                        if (is_numeric($default)) {
                            $tpl .= '->default(' . $default . ')';
                        } else {
                            $tpl .= '->default("' . $default . '")';
                        }
                    }
                }

                $DummyFields .= $tpl . ';' . PHP_EOL . "\t\t";
            }

        }

        $this->info($DummyFields);


        $filePath = $this->getPath($name, $this->getMigrationPath());

        $contents = $this->tpl('create');

        ## 替换内容 生成控制器
        $search = ['DummyClass', 'DummyTable', 'DummyFields'];

        $replace = [$dummyClass, $table, $DummyFields];

        $fileContent = str_replace($search, $replace, $contents);
        ## 写入文件
        $this->filesystem->put(
            $filePath,
            $fileContent
        );


        $this->composer->dumpAutoloads();

    }

    /**
     * @param $columns
     */
    public function setCacheCol($columns)
    {
        Cache::forever($this->cache_key, $columns);
    }

    /**
     * @return mixed
     */
    public function getCacheCols()
    {
        return Cache::get($this->cache_key) ?? [];
    }

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        return database_path('migrations');
    }

    /**
     * Get the full path to the migration.
     *
     * @param  string $name
     * @param  string $path
     * @return string
     */
    protected function getPath($name, $path)
    {
        return $path . '/' . $this->getDatePrefix() . '_' . $name . '.php';
    }

    /**
     * Get the date prefix for the migration.
     *
     * @return string
     */
    protected function getDatePrefix()
    {
        return date('Y_m_d_His');
    }

    /**
     * 读取模板
     * @param $tpl
     * @return bool|string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function tpl($tpl)
    {
        $migrations = ['blank', 'create', 'update'];
        if (!in_array($tpl, $migrations)) {
            $this->error('migration  tpl:' . $tpl . ' is must in[' . implode(',', $migrations) . ']');
            return false;
        }

        ## 读取模板内容 /stubs/Migrations/
        $tmpPath = __DIR__ . "/stubs/Migrations/{$tpl}.stub";
        if (!is_file($tmpPath)) {
            $this->error('File is not exist');
            exit();
        }

        ## 读取模版内容
        return $this->filesystem->get($tmpPath);

    }
}
