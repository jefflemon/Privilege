<?php namespace JeffLemon\Privilege\Models;

class Permission extends \Eloquent {

	public function roles()
    {
        return $this->belongsToMany('JeffLemon\Privilege\Models\Role');
    }
}