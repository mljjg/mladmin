<?php

namespace Ml\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    //

    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'status', 'sex', 'login_at', 'login_ip', 'username', 'bool_admin'
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
    const UPDATED_AT = 'updated_sec';
    const CREATED_AT = 'created_sec';

    /**
     * 指定时间字符
     *
     * @param  DateTime|int $value
     * @return string
     */
    public function fromDateTime($value)
    {
        return strtotime(parent::fromDateTime($value));
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
            $this->avatar = !empty($path) ? Storage::url($path) : 'http://t.cn/RCzsdCq';

        }

        return $this->avatar;
    }
}
