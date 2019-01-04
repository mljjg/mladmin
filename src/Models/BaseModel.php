<?php
/**
 * Created by PhpStorm.
 * User: jjg
 * Date: 2018-12-18
 * Time: 13:41
 */

namespace Ml\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    protected $guarded = [];

    //    const DELETED_AT='deleted_at';
//    const UPDATED_AT = 'updated_at';
//    const CREATED_AT = 'created_at';

    /**
     * 指定时间字符
     *
     * @param  DateTime|int $value
     * @return string
     */
//    public function fromDateTime($value)
//    {
//        return strtotime(parent::fromDateTime($value));
//    }

}