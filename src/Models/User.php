<?php

namespace Ml\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    //
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'sex', 'login_at', 'login_ip', 'username', 'bool_admin', 'avatar'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //    const DELETED_AT='deleted_at';
//    const UPDATED_AT = 'updated_sec';
//    const CREATED_AT = 'created_sec';

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

    /**
     * 自动设置密码加密
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    /**
     * 返回完整的头像地址
     *
     * @return mixed|string
     */
    public function getAvatar()
    {

        if (!Str::startsWith($this->avatar, 'http')) {
            // 拼接完整的 URL
            $this->avatar = !empty($this->avatar) ? \Illuminate\Support\Facades\Storage::url($this->avatar) : 'http://t.cn/RCzsdCq';

        }

        return $this->avatar;
    }

    /**
     * 判定为 超级管理员
     * @return bool
     */
    public function isSuperAdmin()
    {

        return $this->bool_admin == 1;
    }
}
