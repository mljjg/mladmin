<?php

namespace Ml\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Ml\Models\User;
use Ml\Requests\Ajax\UserRequest;
use Ml\Response\Result;

class UsersController extends BaseController
{
    //
    public function index()
    {
        return $this->backend_view('users.index');
    }

    /**
     *
     * @param Request $request
     * @param Result $result
     * @return array
     */
    public function list(Request $request, Result $result)
    {
        $query = User::query();
        $queryFields = ['name', 'email'];
        foreach ($queryFields as $queryField) {
            if ($request->get($queryField)) {
                $query = $query->where($queryField, $request->get($queryField));
            }
        }

        //每页数量
        $per_page = $request->get('limit') ? $request->get('limit') : 10;
        if ($per_page > 100) {
            //限制最大100
            $per_page = 100;
        }
        $query = $query->orderBy('id', 'desc');
        $data = $query->paginate($per_page);
        $data->withPath($request->fullUrl());
        $result->succeed($data);

        return $result->toArray();
    }

    /**
     * 新增页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return $this->backend_view('users.create_edit');
    }

    /**
     * 添加
     * @param UserRequest $request
     * @param Result $result
     * @return array
     */
    public function store(UserRequest $request, Result $result)
    {

        try {
            $data = $request->only(['name', 'email', 'password','status','sex']);

            $data['password'] = bcrypt($data['password']);

            $user = User::create($data);

            $result->succeed($user);
        } catch (\Exception $exception) {
            $result->failed($exception->getMessage());
        }

        return $result->toArray();

    }
}
