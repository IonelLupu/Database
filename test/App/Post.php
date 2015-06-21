<?php 
namespace App;

use Webtron\Database\Model\Model;

class Post extends Model {

	protected $table = 'posts';

	public function user(){

		return $this->belongsTo('App\User');
	}

}