@php
    $activeNavId = app('active')->getController()::$activeNavId;
@endphp

<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <div title="菜单缩放" class="kit-side-fold" style="text-align: center"><i class="layui-icon layui-icon-shrink-right"></i></div>
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            @foreach(config('admin.menu_left') as $k=>$menu)
                <li class="layui-nav-item @if(isset($activeNavId)&&$activeNavId == $menu['id']) layui-nav-itemed @endif">
                    @if(empty($menu['children']))

                        <a href="@if(!empty($menu['link'])) {{ $menu['link'] }} @elseif(!empty($menu['route'])) {{route($menu['route'], $menu['params'])}} @if(!empty($menu['query']))?{{implode('&',$menu['query'])}}@endif @else javascript:; @endif">
                            <i class="{{ $menu['icon'] }}"></i> {{ $menu['text'] }}
                        </a>
                    @else
                        <a href="javascript:;"><i class="{{ $menu['icon'] }}"></i> {{ $menu['text'] }}</a>
                        <dl class="layui-nav-child">
                            @foreach($menu['children'] as $kc=>$item)
                                <dd>
                                    <a href="@if(!empty($item['link'])) {{ $item['link'] }} @elseif(!empty($item['route'])) {{route($item['route'], $item['params'])}} @if(!empty($item['query']))?{{implode('&',$item['query'])}}@endif @else javascript:; @endif">
                                        <i class="{{ $item['icon'] }}"></i> {{ $item['text'] }}
                                    </a>
                                </dd>
                            @endforeach
                        </dl>
                    @endif
                </li>
            @endforeach

        </ul>
    </div>
</div>
@push('scripts')
    <script>
        var isShow = true;  //定义一个标志位
        $('.kit-side-fold').click(function(){
            //选择出所有的span，并判断是不是hidden
            $('.layui-nav-item span').each(function(){
                if($(this).is(':hidden')){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
            //判断isshow的状态
            if(isShow){
                $(".kit-side-fold").html('<i class="layui-icon layui-icon-spread-left"></i>');

                $('.layui-side.layui-bg-black').width(50); //设置宽度
                $('.kit-side-fold i').css('margin-right', '70%');  //修改图标的位置
                //将footer和body的宽度修改
                $('.layui-body').css('left', 60+'px');
                $('.layui-footer').css('left', 60+'px');
                //将二级导航栏隐藏
                $('dd span').each(function(){
                    $(this).hide();
                });
                //修改标志位
                isShow =false;
            }else{
                $(".kit-side-fold").html('<i class="layui-icon layui-icon-shrink-right"></i>');
                $('.layui-side.layui-bg-black').width(200);
                $('.kit-side-fold i').css('margin-right', '10%');
                $('.layui-body').css('left', 200+'px');
                $('.layui-footer').css('left', 200+'px');
                $('dd span').each(function(){
                    $(this).show();
                });
                isShow =true;
            }
        });

    </script>
@endpush
