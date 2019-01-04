<?php

namespace Ml\Http\Controllers\Admin;

use Illuminate\Http\Request;


class WelcomeController extends BaseController
{
    //
    public function dashboard()
    {
        return view('backend.dashboard');
    }
}
