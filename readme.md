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

##### 修改 auth 的 users model

config/auth.php
```
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],
    
```


重写异常类的 unauthenticated 方法：app/Exceptions/Handler.php （mladmin:install 若未选择替换，则需要手动配置）
```php
use Illuminate\Auth\AuthenticationException;

/**
     * Render an exception into an HTTP response.
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return redirect()->guest(route('admin.permission-denied'));
        }

        return parent::render($request, $exception);
    }
    
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


#### 依赖 spatie/laravel-permission 实现用户权限控制 (已自动引入，不需要操作)
```
https://github.com/spatie/laravel-permission

composer require spatie/laravel-permission

php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"

```

## 文件上传 （需要配置）
config/filesystems.php

追加配置：

```
    // 配置的允许大小不能超过 PHP.ini 限制. 默认PHP POST 请求允许最大8MB，File Upload 最大 2MB
    'uploader' => [

        'folder' => ['avatar'],

        // 图片
        'image' => [
            'size_limit' => 5242880, // 单位：字节，默认：5MB
            'allowed_ext' => ["png", "jpg", "gif", 'jpeg'],
        ],

        // 附件
        'annex' => [
            'size_limit' => 204857600000, // 单位：字节，默认：5MB (5242880 B)  // 104857600
            'allowed_ext' => ['zip','rar','7z','gz'],
        ],

        // 文件
        'file' => [
            'size_limit' => 5242880, // 单位：字节，默认：5MB
            'allowed_ext' => ['pdf','doc','docx','xls','xlsx','ppt','pptx'],
        ],

        // 音频
        'voice' => [
            'size_limit' => 5242880, // 单位：字节，默认：5MB
            'allowed_ext' => ['mp3','wmv'],
        ],

        // 视频
        'video' => [
            'size_limit' => 5242880, // 单位：字节，默认：5MB
            'allowed_ext' => ['mp4'],
        ],

    ],
```

## 获取当前-Laravel Active 
### Get the current controller class
https://www.hieule.info/products/laravel-active-version-3-released