@if (count($errors) > 0)
    <blockquote class="layui-elem-quote" style="border-left: 5px solid red">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </blockquote>
@endif
