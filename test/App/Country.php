<?php 
namespace App;

use Webtron\Database\Model\Model;

class Country extends Model {

	protected $table = 'countries';

	public function posts(){

		return $this->hasManyThrough('App\Post','App\User','country_id','user_id');
	}

}