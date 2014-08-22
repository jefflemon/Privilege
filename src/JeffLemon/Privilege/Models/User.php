<?php namespace JeffLemon\Privilege\Models;

class User extends \User {
	
	public function roles()
    {
        return $this->belongsToMany('JeffLemon\Privilege\Models\Role');
    }
}