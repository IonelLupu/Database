<?php 
namespace App;

use Webtron\Database\Model\Model;

class User extends Model {

	protected $table = 'users';

	public function car(){

		return $this->hasOne('App\Car');
	}
	
	public function posts(){

		return $this->hasMany('App\Post');
	}
	public function roles(){

		return $this->belongsToMany('App\Role','user_roles');
	}

}