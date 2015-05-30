<?php  
namespace Webtron\Database\Clauses;

trait Truncate{

	public  function getTruncate(){

		return implode(' ',[
			"TRUNCATE",
			$this->table
			]);
	}
}
?>