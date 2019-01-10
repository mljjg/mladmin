<div class="layui-header">
    <div class="layui-logo">
      <i class="layui-icon layui-icon-home"></i>  {{ config('app.name', 'Laravel') }}
    </div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
        @foreach(config('admin.menu_top') as $k=>$menu)
            @if(call_user_func($menu['permission']))
                <li class="layui-nav-item">
                    @if(empty($menu['children']))
                        <a href="@if(!empty($menu['link'])) {{ $menu['link'] }} @elseif(!empty($menu['route'])) {{route($menu['route'], $menu['params'])}} @if(!empty($menu['query']))?{{implode('&',$menu['query'])}}@endif @else javascript:; @endif">
                            <i class="{{ $menu['icon'] }}"></i> {{ $menu['text'] }}
                        </a>
                    @else
                        <a href="javascript:;"><i class="{{ $menu['icon'] }}"></i>{{ $menu['text'] }}</a>
                        <dl class="layui-nav-child">
                            @foreach($menu['children'] as $kc=>$item)
                                @if(call_user_func($item['permission']))
                                    <dd>
                                        <a href="@if(!empty($item['link'])) {{ $item['link'] }} @elseif(!empty($item['route'])) {{route($item['route'], $item['params'])}} @if(!empty($item['query']))?{{implode('&',$item['query'])}}@endif @else javascript:; @endif">
                                            <i class="{{ $item['icon'] }}"></i>   {{ $item['text'] }}
                                        </a>
                                    </dd>
                                @endif
                            @endforeach
                        </dl>
                    @endif
                </li>
            @endif

        @endforeach

        {{--<li class="layui-nav-item"><a href="">控制台</a></li>--}}
        {{--<li class="layui-nav-item"><a href="">商品管理</a></li>--}}
        {{--<li class="layui-nav-item"><a href="">用户</a></li>--}}
        {{--<li class="layui-nav-item">--}}
        {{--<a href="javascript:;">其它系统</a>--}}
        {{--<dl class="layui-nav-child">--}}
        {{--<dd><a href="">邮件管理</a></dd>--}}
        {{--<dd><a href="">消息管理</a></dd>--}}
        {{--<dd><a href="">授权管理</a></dd>--}}
        {{--</dl>--}}
        {{--</li>--}}
    </ul>

    <ul class="layui-nav layui-layout-right">
        @guest
            <li class="layui-nav-item">登录</li>
        @else
            <li class="layui-nav-item">
                <a href="javascript:;">

                    <img src="{{ Auth::user()->getAvatar() }}" class="layui-nav-img" alt="{{ Auth::user()->name }}">
                    {{ Auth::user()->name }}
                </a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ route('user.edit',Auth::user()->id) }}"><i class="layui-icon layui-icon-set-sm"></i>基本资料</a></dd>
                    <dd><a href="{{ route('user.password.edit',Auth::user()->id) }}"><i class="layui-icon layui-icon-password"></i>修改密码</a></dd>
                    {{--                    <dd><a href="{{route('admin.permission-denied')}}">无权限页面</a></dd>--}}
                    <dd>
                        <a href=""
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                            <i class="layui-icon layui-icon-return"></i>退出
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </dd>
                </dl>
            </li>
        @endguest

    </ul>
</div>