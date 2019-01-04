@extends('backend.layouts.app')

@section('title', $title = '仪表盘')

@section('breadcrumb')

    <a href="{{route('admin.dashboard')}}">首页</a>
    <a><cite>{{$title}}</cite></a>
@endsection

@section('content')
    <div style="padding: 15px;">
        仪表盘
    </div>
@endsection
