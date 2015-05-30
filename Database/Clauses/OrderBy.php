<?php 
namespace Webtron\Database\Clauses;


trait OrderBy {

	public $orderBy = [];

	public function getOrderBy(){
		if( count($this->orderBy) )
			return "ORDER BY ".implode(',', $this->orderBy);
		return "";
	}

	public  function orderBy($column ,$ordering = "ASC"){

		$this->orderBy[] = implode(' ', [ $column, $ordering ] );

		return $this;

	}	
}

?>