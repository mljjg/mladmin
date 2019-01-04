## Laravel 后台开发扩展包

Installation
------------

> This package requires PHP 7+ and Laravel 5.5

First, install laravel 5.5, and make sure that the database connection settings are correct.

```
composer require jjg/admin
```

Then run these commands to publish assets and config：

```
php artisan vendor:publish --provider="Ml\Providers\MlServiceProvider"
```

After run command you can find config file in `config/admin.php`, in this file you can change the install directory,db connection or table names.

At last run following command to finish install.
```
php artisan mlAdmin:install
```

重写异常类的 unauthenticated 方法：app/Exceptions/Handler.php
```php
use Illuminate\Auth\AuthenticationException;

 /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // 自定义登录跳转-通过路由前缀判断
        $action = $request->route()->getAction();

        $redirectUrl = (isset($action['prefix']) && (config('admin.route.prefix') == $action['prefix'] || '/' . config('admin.route.prefix') == $action['prefix'])) ?
            route('admin.login') : route('login');

        return $request->expectsJson()
            ? response()->json(['message' => $exception->getMessage()], 401)
            : redirect()->guest($redirectUrl);

//        return $request->expectsJson()
//            ? response()->json(['message' => $exception->getMessage()], 401)
//            : redirect()->guest(route('login'));
    }

```

User修改：

```php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends \Ml\Models\User
{
//    use Notifiable;
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array
//     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
//
//    /**
//     * The attributes that should be hidden for arrays.
//     *
//     * @var array
//     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];
}

```
