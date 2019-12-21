<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use Auth;
class UsersPolicy
{
    use HandlesAuthorization;
    public $list = [];
    public function __construct($list = [])
    {
        $user=Auth::user();
        if($user->role->permissions()->exists()) {
            foreach ($user->role->permissions as $key => $permission) {
                $list[] = $permission->name;
            }
            $this->list = $list;
        }
    }
    public function viewAny(User $user)
    {

    }

    public function view()
    {
        if( in_array('user_view', $this->list)){
            return true;
        }else{
            return false;
        }
    }

    public function create()
    {
        if( in_array('user_create', $this->list)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update()
    {
        if( in_array('user_edit', $this->list)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete()
    {
        if( in_array('user_delete', $this->list)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
}
