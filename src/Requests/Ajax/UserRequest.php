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
            return [
                'name' => 'required|between:1,25|unique:users,name,' . request('user')->id,//|regex:/^[A-Za-z0-9\-\_]+$/
                'email' => 'required|email|unique:users,email,' . request('user')->id,
            ];
        }
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.unique' => '用户昵称已存在！',
            'name.between' => '用户昵称长度应1-25个字符！',
            'email.required_without' => '邮箱或者用户名必须至少填写一个！'
        ];
    }


}
