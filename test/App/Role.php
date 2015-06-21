<?php 
namespace App;

use Webtron\Database\Model\Model;

class Role extends Model {

	protected $table = 'roles';

	public function users(){

		return $this->belongsToMany('App\User','user_roles');
	}

}