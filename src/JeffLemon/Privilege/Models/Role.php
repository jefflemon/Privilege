<?php namespace JeffLemon\Privilege\Models;

class Role extends \Eloquent {

	public function users()
    {
        return $this->belongsToMany('JeffLemon\Privilege\Models\User');
    }

    public function permissions()
    {
      	return $this->belongsToMany('JeffLemon\Privilege\Models\Permission');
    }	
}