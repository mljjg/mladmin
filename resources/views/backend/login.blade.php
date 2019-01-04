<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - 后台登录</title>
    <link rel="stylesheet" href="{{ asset('vendor/ml-admin/layui/css/layui.css') }}">
    <style>


        .login-container {
            /*box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);*/
            -webkit-border-radius: 5px;
            border-radius: 5px;
            -moz-border-radius: 5px;
            background-clip: padding-box;
            margin: 10% auto 0 auto;
            width: 450px;
            padding: 35px 35px 15px 35px;
            background: #fff;
            border: 1px solid #eaeaea;
            box-shadow: 0 0 25px #cac6c6;
        }

        .title {
            margin: 0px auto 40px auto;
            text-align: center;
            color: #505458;
        }

        .remember {
            margin: 0px 0px 35px 0px;
        }

    </style>
</head>
<body class="layui-layout-body">
<div class="layui-container">
    <div class="layui-row" style="margin: auto">
        <div class="layui-col-md6 layui-col-md-offset3">
            <!-- 内容主体区域 -->
            <div class="login-container">
                <div>
                    <h2>后台登录</h2>
                </div>
                <hr>
                <form class="layui-form"  action="{{url('admin/login')}}" method="POST">
                    {{--防跨越--}}
                    {{ csrf_field() }}
                    {{--<div class="layui-form-item">--}}
                        {{--<label class="layui-form-label">用户</label>--}}
                        {{--<div class="layui-input-block">--}}
                            {{--<input type="text" name="name" required lay-verify="required" placeholder="请输入用户名"--}}
                                   {{--autocomplete="off" class="layui-input">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input type="text" name="email" required lay-verify="required" placeholder="请输入邮箱"
                                   autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                                   autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-form-mid layui-word-aux">（要求6-16个字符）</div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formLogin">登录</button>
                            {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

</div>


<div  style="position: fixed;z-index: 1000;bottom: 0;right: 0;left: 0">
    <!-- 底部固定区域 -->
    @include('backend.layouts._footer')
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
</script>
<script>
    //Demo
    layui.use('form', function () {
        let form = layui.form;

        //监听提交
        form.on('submit(formLogin)', function (data) {
            layer.msg('登录检验中， 请稍等……');
        });
    });
</script>

</body>
</html>

