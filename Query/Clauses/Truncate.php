<?php  
namespace Webtron\Database\Query\Clauses;

trait Truncate{

	public  function getTruncate(){

		return implode(' ',[
			"TRUNCATE",
			$this->table
			]);
	}
}
?>