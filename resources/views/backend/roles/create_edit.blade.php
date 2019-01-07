@extends('backend.layouts.app')

@section('title', $title = $role->id ? '编辑角色' : '添加角色' )

@section('breadcrumb')
    <a>系统设置</a>
    <a href="{{ route('admin.roles') }}">角色列表</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;margin-top: 10px;">
        <div class="layui-col-md8 layui-col-md-offset2">
            <div class="layui-form">
                {{--防跨越--}}
                {{ csrf_field() }}
                <div class="layui-form-item">
                    <label class="layui-form-label">角色名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="name" required lay-verify="required" placeholder="请输入权限名称"
                               autocomplete="off" class="layui-input" value="{{ old('name',$role->name) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">角色备注</label>
                    <div class="layui-input-block">
                        <input type="text" name="remarks" required lay-verify="required" placeholder="权限备注"
                               autocomplete="off" class="layui-input" value="{{ old('remarks',$role->remarks) }}">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">拥有权限</label>
                    <div class="layui-input-block">
                        @foreach($permissions as $key => $val)

                            <input type="checkbox" name="permission[]" title=" {{$key}}" value="{{ $val }}"
                                   title="{{ $key }}"
                                   @if(in_array($val,$rolePermissions) || in_array($val, old('permission',[]))) checked="checked"
                                   @endif required>

                        @endforeach
                    </div>
                </div>

                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="formCommit">立即提交</button>
                        {{--<button type="reset" class="layui-btn layui-btn-primary">重置</button>--}}
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
            form.on('submit(formCommit)', function (data) {
                let url = "{{$role->id?route('admin.roles.update',$role->id):route('admin.roles.store')}}";

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

                                let url = "{{route('admin.roles')}}";
                                window.open(url, '_self');

                            });

                        }

                    }
                });


            });
        });
    </script>
@endpush