@extends('backend.layouts.app')

@section('title', $title = '仪表盘')

@section('breadcrumb')

    <a href="{{route('admin.dashboard')}}">首页</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;">
        <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
            <legend>仪表盘</legend>
        </fieldset>

        <div style="padding: 20px; background-color: #F2F2F2;">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">产品信息</div>
                        <div class="layui-card-body">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>产品名称</td>
                                    <td>Laravel/LayUI</td>
                                </tr>
                                <tr>
                                    <td>核心框架</td>
                                    <td><a href="https://github.com/laravel/laravel" target="_blank"></a>Laravel/{{app()->version()}}</td>
                                </tr>
                                <tr>
                                    <td>前端框架</td>
                                    <td><a href="https://www.layui.com" target="_blank">LayUI</a></td>
                                </tr>
                                <tr>
                                    <td>开发作者</td>
                                    <td>JJG</td>
                                </tr>
                                <tr>
                                    <td>系统时区</td>
                                    <td>{{config('app.timezone')}}</td>
                                </tr>
                                <tr>
                                    <td>语言环境</td>
                                    <td>{{config('app.locale')}}</td>
                                </tr>
                                <tr>
                                    <td>系统模式</td>
                                    <td>{{ config('app.env') }}</td>
                                </tr>
                                <tr>
                                    <td>系统URL</td>
                                    <td>{{ config('app.url') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">服务器信息</div>
                        <div class="layui-card-body">
                            <table class="layui-table">
                                <colgroup>
                                    <col width="150">
                                    <col>
                                </colgroup>
                                <tbody>
                                <tr>
                                    <td>操作系统</td>
                                    <td>{{ php_uname() }}</td>
                                </tr>
                                <tr>
                                    <td>运行环境</td>
                                    <td>{{ array_get($_SERVER, 'SERVER_SOFTWARE') }}</td>
                                </tr>
                                <tr>
                                    <td>PHP版本</td>
                                    <td>PHP / {{PHP_VERSION}}</td>
                                </tr>
                                <tr>
                                    <td>缓存驱动</td>
                                    <td>{{config('cache.default')}}</td>
                                </tr>
                                <tr>
                                    <td>会话驱动</td>
                                    <td>{{config('session.driver')}}</td>
                                </tr>
                                <tr>
                                    <td>队列驱动</td>
                                    <td>{{ config('queue.default') }}</td>
                                </tr>
                                <tr>
                                    <td>系统URL</td>
                                    <td>{{ config('filesystems.default') }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
