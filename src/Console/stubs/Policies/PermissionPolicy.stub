<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function view(User $user, Permission $permission)
    {
        //
        return $user->can('manage_permissions');
    }

    /**
     * Determine whether the user can create permissions.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->can('manage_permissions');
    }

    /**
     * Determine whether the user can update the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function update(User $user, Permission $permission)
    {
        //
        return $user->can('manage_permissions');
    }

    /**
     * Determine whether the user can delete the permission.
     *
     * @param User $user
     * @param Permission $permission
     * @return mixed
     */
    public function delete(User $user, Permission $permission)
    {
        //
        return $user->can('manage_permissions');
    }

    /**
     * 批量删除的权限
     * @param User $user
     * @return bool
     */
    public function destroyBat(User $user)
    {
        return $user->can('manage_permissions');
    }
}
