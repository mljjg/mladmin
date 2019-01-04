<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - 后台</title>
    <link rel="stylesheet" href="{{ asset('vendor/ml-admin/layui/css/layui.css') }}">

</head>
<body class="layui-layout-body">
<div class="layui-layout layui-layout-admin">
    <!-- 顶部导航栏 -->
@include('backend.layouts._top')

<!-- 侧边导航栏 -->
    @include('backend.layouts._sidebar')


    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <div class="content-header">
                @includeWhen($breadcrumb ?? true, 'backend.layouts._breadcrumb')
            </div>
            @yield('content')
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

{{--在此处加载scripts--}}
@stack('scripts')


</body>
</html>

