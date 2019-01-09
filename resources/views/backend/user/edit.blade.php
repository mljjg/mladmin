@extends('backend.layouts.app')

@section('title', $title = $user->id ? '基本信息' : '异常' )

@section('breadcrumb')
    <a>个人设置</a>
    <a><cite>{{$title}}</cite></a>
    <a href="{{ route('user.password.edit',Auth::user()->id) }}">修改密码</a>
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
                           autocomplete="off" class="layui-input" value="{{ old('username',$user->username) }}"
                           disabled="disabled">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">*</span>（不能修改）
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">邮箱</label>
                <div class="layui-input-inline">
                    <input type="text" name="email" required lay-verify="required" placeholder="请输入邮箱"
                           autocomplete="off" class="layui-input" value="{{ old('email',$user->email) }}" disabled>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span style="color: red">*</span>（不能修改）
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
                <label class="layui-form-label">头像</label>
                <div class="layui-input-block">
                    <div class="layui-upload">
                        <button type="button" class="layui-btn" id="upload_btn">上传图片</button>
                        <div class="layui-upload-list">
                            <input type="hidden" name="avatar" id="form_avatar" value="{{ old('avatar',$user->avatar) }}" />
                            <img src="{{ $user->getAvatar() }}" id="image_avatar" class="img-rounded" width="200px"
                                 height="200px" alt="">
                            <p id="errorText"></p>
                        </div>
                    </div>
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
                let url = "{{$user->id?route('user.update',$user->id):null}}";
                if (url) {
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
                }
            });
        });


        //文档： https://www.layui.com/doc/modules/upload.html
        layui.use('upload', function () {
            var $ = layui.jquery
                , upload = layui.upload;

            let _token = "{{csrf_token()}}";

            let upload_url = '{{ route('uploader') }}?file_type=image&folder=avatar&_token=' + _token;
            //普通图片上传
            let uploadInst = upload.render({
                elem: '#upload_btn'
                , url: upload_url
                , size: 5120 //限制文件大小，单位 KB
                // ,auto: false ////选完文件后不自动上传
                , field: 'upload_file'//设定文件域的字段名 默认file
                , accept: 'image' //普通文件
                , acceptMime: 'image/*' //（只显示图片文件）
                // , headers: {_token:_token}
                , before: function (obj) {
                    //预读本地文件示例，不支持ie8
                    obj.preview(function (index, file, result) {
                        $('#image_avatar').attr('src', result); //图片链接（base64）
                    });
                }
                , done: function (res) {
                    //如果上传失败
                    if (res.code > 0) {
                        return layer.msg('上传失败');
                    }
                    //上传成功
                    $("#form_avatar").val(res.path);
                    $("#form_avatar").attr(res.path);
                }
                , error: function () {
                    //演示失败状态，并实现重传
                    var demoText = $('#errorText');
                    demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                    demoText.find('.demo-reload').on('click', function () {
                        uploadInst.upload();
                    });
                }
            });
        });
    </script>
@endpush