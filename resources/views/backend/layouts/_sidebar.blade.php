<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree"  lay-filter="test">
            @foreach(config('admin.menu_left') as $k=>$menu)
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

            {{--<li class="layui-nav-item layui-nav-itemed">--}}
                {{--<a class="" href="javascript:;">所有商品</a>--}}
                {{--<dl class="layui-nav-child">--}}
                    {{--<dd><a href="javascript:;">列表一</a></dd>--}}
                    {{--<dd><a href="javascript:;">列表二</a></dd>--}}
                    {{--<dd><a href="javascript:;">列表三</a></dd>--}}
                    {{--<dd><a href="">超链接</a></dd>--}}
                {{--</dl>--}}
            {{--</li>--}}
            {{--<li class="layui-nav-item">--}}
                {{--<a href="javascript:;">解决方案</a>--}}
                {{--<dl class="layui-nav-child">--}}
                    {{--<dd><a href="javascript:;">列表一</a></dd>--}}
                    {{--<dd><a href="javascript:;">列表二</a></dd>--}}
                    {{--<dd><a href="">超链接</a></dd>--}}
                {{--</dl>--}}
            {{--</li>--}}
            {{--<li class="layui-nav-item"><a href="">云市场</a></li>--}}
            {{--<li class="layui-nav-item"><a href="">发布商品</a></li>--}}
        </ul>
    </div>
</div>