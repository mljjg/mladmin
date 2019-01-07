<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends Policy
{
//    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *  判断用户列表能否被用户查询
     * @param User $user 当前登录的用户
     * @param User $model 操作的对象
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        //
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can create models.
     * 判断是否有权限创建用户
     * @param User $user 当前登录用户
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->can('manage_users');
    }

    /**
     * Determine whether the user can update the model.
     * 判断是否有权限更新用户:要求拥有用户管理权限或者是用户自己
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $user->can('manage_users') || $user->id == $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     * 判断是否有权限删除用户
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        //用户禁止删除自身(非超级管理员)
        if ($user->id === $model->id) {
            return false;
        }
        return $user->can('manage_users');
    }

    /**
     * 批量删除的权限
     * @param User $user
     * @return bool
     */
    public function destroyBat(User $user)
    {
        return $user->can('manage_users');
    }
}
