<?php  
namespace Webtron\Database\Query\Clauses;

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