@extends('backend.layouts.app')

@section('title', $title = $user->id ? '编辑用户' : '添加用户' )

@section('breadcrumb')
    <a>系统设置</a>
    <a href="{{ route('admin.users') }}">用户列表</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;">
        <div class="layui-form">
            {{--防跨越--}}
            {{ csrf_field() }}
            <div class="layui-form-item">
                <label class="layui-form-label">昵称</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" required lay-verify="required" placeholder="请输入昵称"
                           autocomplete="off" class="layui-input" value="{{ old('name',$user->name) }}">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">账号</label>
                <div class="layui-input-inline">
                    <input type="text" name="username" required lay-verify="required" placeholder="请输入用户名"
                           autocomplete="off" class="layui-input" value="{{ old('username',$user->username) }}">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">*</span>（唯一）
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" name="email" required lay-verify="required" placeholder="请输入邮箱"
                           autocomplete="off" class="layui-input" value="{{ old('email',$user->email) }}">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">*</span>（唯一）
                </div>
            </div>
            @if($user->id?false:true)
                <div class="layui-form-item">
                    <label class="layui-form-label">密码</label>
                    <div class="layui-input-inline">
                        <input type="password" name="password" required lay-verify="required" placeholder="请输入密码"
                               autocomplete="off" class="layui-input" value="{{ old('old_password') }}">
                    </div>
                    <div class="layui-form-mid layui-word-aux">（要求6-16个字符）</div>
                </div>
            @endif

            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="status" lay-skin="switch" lay-text="开启|关闭" value="1"
                           @if(old('status',$user->status) == 1) checked="checked" @endif>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-block">
                    <input type="radio" name="sex" value="1" title="男"
                           @if(old('sex',$user->sex) == 1) checked="checked" @endif>
                    <input type="radio" name="sex" value="0" title="女"
                           @if(old('sex',$user->sex) == 0) checked="checked" @endif>
                    {{--<input type="radio" name="sex" value="" title="中性" disabled>--}}
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">超级管理员</label>
                <div class="layui-input-block">
                    <input type="radio" name="bool_admin" value="1" title="是"
                           @if(old('bool_admin',$user->bool_admin) == 1) checked="checked" @endif>
                    <input type="radio" name="bool_admin" value="0" title="否"
                           @if(old('bool_admin',$user->bool_admin) == 0) checked="checked" @endif>
                    {{--<input type="radio" name="sex" value="" title="中性" disabled>--}}
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">角色</label>
                <div class="layui-input-block">
                    @foreach($roles as $key => $val)
                        <input type="checkbox" name="roles[]" value="{{ $val }}" title="{{ $key }}"
                               @if(in_array($val,$userRoles) || in_array($val, old('roles',[]))) checked="checked"
                               @endif required>

                    @endforeach
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="formUser">立即提交</button>
                    {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        //Demo
        layui.use('form', function () {
            let form = layui.form;

            //监听提交
            form.on('submit(formUser)', function (data) {
                let url = "{{$user->id?route('admin.users.update',$user->id):route('admin.users.store')}}";

                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: data.field,
                    dataType: "JSON",
                    success: function (result) {
                        let msg = result.message;

                        if (!result.success) {

                            layer.msg(msg);
                            layer.open({
                                type: 1,
                                anim: 0,
                                title: msg,
                                area: ['50%', '70%'],
                                btn: ['关闭'],
                                content: JSON.stringify(result)
                            });

                        } else {
                            // layer.msg(msg);
                            layer.alert(msg, function (index) {
                                layer.close(index);

                                let url = "{{route('admin.users')}}";
                                window.open(url, '_self');

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush