<?php  
namespace Webtron\Database\Clauses;

trait Select{

	public $distinct = false;

	public function getSelect(){
		
		$stmt = ["SELECT"];

		if($this->distinct) 
			$stmt[] = 'DISTINCT';

		$stmt = array_merge($stmt,[
				$this->implodedColumns(),
				"FROM",
				$this->table
			]);

		if( !empty($this->columns) && !empty($this->table) )
			return implode(' ',$stmt);
	}


	public  function select($columns = ['*']){

		$this->columns = $columns;

		return $this;

	}

	public  function addSelect($columns = []){

		$this->columns = array_merge($this->columns,$columns);

		return $this;

	}

	public  function distinct($flag = true){

		$this->distinct = $flag;

		return $this;

	}
	public  function from($tableName = ""){

		$this->table = $tableName;

		return $this;

	}
}
?>