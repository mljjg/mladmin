<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        //
        return $user->can('manage_roles');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
        return $user->can('manage_roles');
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        //
        return $user->can('manage_roles');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param User $user
     * @param Role $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        //
        return $user->can('manage_roles');
    }

    /**
     * 批量删除的权限
     * @param User $user
     * @return bool
     */
    public function destroyBat(User $user)
    {
        return $user->can('manage_roles');
    }
}
