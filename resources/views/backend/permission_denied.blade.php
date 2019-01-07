<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>无权限访问 - {{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{ asset('vendor/ml-admin/layui/css/layui.css') }}">

</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 顶部导航栏 -->
    <div class="layui-body">
        <div class="layui-row">
            <div class="layui-col-md8 layui-col-md-offset2">
                <!-- 内容主体区域 -->
                <div class="alone-banner">
                    <div class="layui-main">
                        @if (Auth::check())
                            <h1>无权限操作</h1>
                            <p class="layui-hide-xs">如需继续操作，请联系管理员授权后再继续操作。</p>
                        @else
                            <h1>未登录系统</h1>
                            <p class="layui-hide-xs">如需继续操作，请先登录后再继续操作。</p>
                        @endif
                    </div>
                </div>

                <div class="alone-preview">
                    <p class="alone-download-btn">
                        @if (Auth::check())
                            <a href="{{ route('admin.dashboard')  }}" class="layui-btn">控制台</a>
                        @else
                            <a href="{{ route('admin.login')  }}" class="layui-btn">登录</a>
                        @endif
                        <a href="{{ url()->previous() }}" class="layui-btn layui-btn-primary alone-download-right">返回</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-footer" style="background-color: white">
        <!-- 底部固定区域 -->
        @include('backend.layouts._footer');
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('vendor/ml-admin/layui/layui.all.js') }}"></script>
<script src="{{ asset('vendor/ml-admin/jQuery/jquery-2.1.4.min.js') }}"></script>
<script>
    //文档：https://www.layui.com/doc/base/infrastructure.html
    //JavaScript代码区域
    layui.use('element', function () {
        var element = layui.element;
        // console.log('出错了')
    });
    // layui.hint().error('出错啦121');

</script>



</body>
</html>

