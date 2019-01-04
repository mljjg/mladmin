<?php

namespace Ml\Requests\Ajax;


use Ml\Requests\BaseRequest;

class UserRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        //根据路由设置不同的规则
        if ($this->routeIs('admin.users.store')) {
            return [
                'name' => 'required|between:1,25|unique:users,name',//|regex:/^[A-Za-z0-9\-\_]+$/
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|between:6,16',
            ];
        } elseif ($this->routeIs('admin.users.update')) {
//            \Log::info('_id_' . request('id'));
//            \Log::info('_all_' . print_r(request()->all(),true));
            return [
                'name' => 'required|between:1,25|unique:users,name,' . request('id'),//|regex:/^[A-Za-z0-9\-\_]+$/
                'email' => 'required|email|unique:users,email,' . request('id'),
            ];
        }


    }
}
