<?php namespace JeffLemon\Privilege;

use \Auth;
use \Config;
use \Redirect;
use \Exception;
use \JeffLemon\Privilege\Models\User as User;
use \JeffLemon\Privilege\Models\Role as Role;
use \JeffLemon\Privilege\Models\Permission as Permission;

class Privilege {
    
    /**
     * Role(s) authentication.
     *
     * @param string|array  $role
     * @param bool          $matchAll
     * @param int           $userId
     *
     * @return bool
     */

  	public function hasRole($role, $matchAll = false, $userId = NULL){
       
        $valid = false;

        if(!is_array($role)) $role = (array)$role;

        $this->setUserId($userId);

        if($matchAll)
        {
            $data = User::whereHas('roles', function($q) use ($role){
                $q->whereIn('role', $role);
            })->find($userId);
        }
        else
        {
            $data = User::whereHas('roles', function($q) use ($role){
                $q->whereIn('role', $role);
            }, '=', count($role))->find($userId);
        }

        if($data)$valid = true;

        return $valid;
  	}

    /**
     * Route filter for role(s) authentication.
     *
     * @param object        $route
     * @param object        $request
     * @param string|array  $role
     * @param bool          $matchAll
     * @param int           $userId
     *
     */

    public function hasRoleFilter($route, $request, $role = NULL, $matchAll = false, $userId = NULL){

        $this->setUserId($userId);
        $roles = explode('/', $role);
        if(!$this->hasRole($role, $matchAll, $userId)) return Redirect::route(Config::get('privilege::privilege_fail_route'));

    }

    /**
     * Permission(s) authentication.
     *
     * @param string|array  $permission
     * @param bool          $matchAll
     * @param int           $userId
     *
     * @return bool
     */

  	public function hasPermission($permission, $matchAll = false, $userId = NULL){

        $valid = false;

        if(!is_array($permission)) $permission = (array)$permission;

        $this->setUserId($userId);

        if($matchAll){
            $data = User::whereHas('roles', function($q) use ($permission){
                $q->whereHas('permissions', function($q) use ($permission){
                    $q->whereIn('permission', $permission);
                });
            })->find($userId);
        }
        else{
            $data = User::whereHas('roles', function($q) use ($permission){
                $q->whereHas('permissions', function($q) use ($permission){
                    $q->whereIn('permission', $permission);
                }, '=', count($permission));
            })->find($userId);
        }

        if($data)$valid = true;

        return $valid;
  	}

     /**
     * Route filter for permission(s) authentication.
     *
     * @param object        $route
     * @param object        $request
     * @param string|array  $permission
     * @param bool          $matchAll
     * @param int           $userId
     *
     */

    public function hasPermissionFilter($route, $request, $permission = NULL, $matchAll = false, $userId = NULL){

        $this->setUserId($userId);
        $permission = explode('/', $permissions);
        if(!$this->hasPermission($permission, $matchAll, $userId)) return Redirect::route(Config::get('privilege::privilege_fail_route'));

    }

    /**
     * Add a role to a user.
     *
     * @param int   $roleId
     * @param bool  $detach
     * @param int   $userId
     *
     * @return object
     */

    public function addRole($roleId, $detach = true, $userId = NULL){

        if(!is_array($roleId)) $roleId = (array)$roleId;
        
        $this->setUserId($userId);

        $user = User::findOrFail($userId);
        $user->roles()->sync($roleId, $detach);

        return $user;
    }

    /**
     * Delete a role from a user.
     *
     * @param int $roleId
     * @param int $userId
     *
     * @return object
     */

    public function deleteRole($roleId, $userId = NULL){

        if(!is_array($roleId)) $roleId = (array)$roleId;

        $this->setUserId($userId);

        $user = User::findOrFail($userId);
        $user->roles()->detach($roleId);

        return $user;
    }

    /**
     * Get all roles that exist.
     *
     * @return object
     */

    public function getRoles(){

        return Role::orderBy('title')->get();
    }

    /**
     * Get all roles with a user.
     *
     * @param int $userId
     *
     * @return bool
     */

    public function getRolesWithUser($userId = NULL){

       $this->setUserId($userId);

       return Role::with(array('users'=>function($query) use ($userId){
            $query->where('user_id', $userId);
        }))->get();

    }

    /**
     * Set user id if empty.
     *
     * @param int $userId
     *
     */

    private function setUserId(&$userId){

        if(empty($userId)){

            if(Auth::check()) $userId = Auth::id();
            else throw new Exception("Priviliege: User ID not valid.");
        }
    }
}