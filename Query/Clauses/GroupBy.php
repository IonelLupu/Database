<?php 
namespace Webtron\Database\Query\Clauses;


trait GroupBy {

	use Having;

	public $groupBy = [];

	public function getGroupBy(){
		if( count($this->groupBy) )
			return "GROUP BY ".implode(',', $this->groupBy);
		return "";
	}

	public  function groupBy(){

		foreach (func_get_args() as $arg)
			$this->groupBy = array_merge( $this->groupBy, [$arg]);

		return $this;

	}	
}

?>