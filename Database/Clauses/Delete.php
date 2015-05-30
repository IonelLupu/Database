<?php  
namespace Webtron\Database\Clauses;

trait Delete{

	public  function getDelete(){

		return implode(' ',[
			"DELETE",
			"FROM",
			$this->table
			]);
	}
}
?>