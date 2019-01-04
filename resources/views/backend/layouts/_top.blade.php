<script>

</script>
<div class="layui-header">
    <div class="layui-logo">
        {{ config('app.name', 'Laravel') }}
    </div>
    <!-- 头部区域（可配合layui已有的水平导航） -->
    <ul class="layui-nav layui-layout-left">
        @foreach(config('admin.menu_top') as $k=>$menu)
            <li class="layui-nav-item">
                @if(empty($menu['children']))
                    <a href="@if(!empty($menu['link'])) {{ $menu['link'] }} @elseif(!empty($menu['route'])) {{route($menu['route'], $menu['params'])}} @if(!empty($menu['query']))?{{implode('&',$menu['query'])}}@endif @else javascript:; @endif">
                        {{ $menu['text'] }}
                    </a>
                @else
                    <a href="javascript:;">{{ $menu['text'] }}</a>
                    <dl class="layui-nav-child">
                        @foreach($menu['children'] as $kc=>$item)
                            <dd>
                                <a href="@if(!empty($item['link'])) {{ $item['link'] }} @elseif(!empty($item['route'])) {{route($item['route'], $item['params'])}} @if(!empty($item['query']))?{{implode('&',$item['query'])}}@endif @else javascript:; @endif">
                                    {{ $item['text'] }}
                                </a>
                            </dd>
                        @endforeach
                    </dl>
                @endif
            </li>
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
                    <dd><a href="">基本资料</a></dd>
                    <dd><a href="">安全设置</a></dd>
                    <dd>
                        <a href=""
                           onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出</a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </dd>
                </dl>
            </li>
        @endguest

    </ul>
</div>