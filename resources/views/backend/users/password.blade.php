@extends('backend.layouts.app')

@section('title', $title = $user->id?'修改密码-'.$user->name:'修改密码')

@section('breadcrumb')
    <a>系统设置</a>
    <a href="{{ route('admin.users') }}">用户列表</a>
    <a href="{{route('admin.users.password.edit',$user->id)}}"><cite>{{ $title }}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;">

        <div class="layui-row">
            <div class="layui-col-xs12 layui-col-sm8 layui-col-md6">
                <div class="layui-form">
                    {{--防跨越--}}
                    {{ csrf_field() }}

                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" required lay-verify="required" placeholder="请输入邮箱"
                                   autocomplete="off" class="layui-input" value="{{ old('email',$user->email) }}"
                                   disabled>
                        </div>
                        <div class="layui-form-mid layui-word-aux">
                            <span style="color: red">*</span>（唯一）
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">新密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" required lay-verify="required" placeholder="请输入旧密码"
                                   autocomplete="off" class="layui-input" value="{{ old('old_password') }}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">确认密码</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password_confirmation" required lay-verify="required"
                                   placeholder="请输入确认密码"
                                   autocomplete="off" class="layui-input" value="{{ old('old_password') }}">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit lay-filter="formUser">修改密码</button>
                            {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
                        </div>
                    </div>
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

                let url = "{{ route('admin.users.password.update',$user->id) }}";
                $.ajax({
                    type: 'POST',
                    url: url,//发送请求
                    data: data.field,
                    dataType: "JSON",
                    success: function (result) {
                        let msg = result.message;

                        if (!result.success) {

                            layer.msg(msg);
                            // layer.open({
                            //     type: 1,
                            //     anim: 0,
                            //     title: msg,
                            //     area: ['50%', '70%'],
                            //     btn: ['关闭'],
                            //     content: JSON.stringify(result)
                            // });

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
