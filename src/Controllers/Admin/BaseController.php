<?php

namespace Ml\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class BaseController extends Controller
{
    //
    /**
     * 后台路由
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function backend_view($name)
    {
        $args = func_get_args();
        $args[0] = 'backend.' . $name;

        return view(...$args);
    }
}
