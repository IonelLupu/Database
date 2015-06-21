<?php  
namespace Webtron\Database\Query\Clauses;

trait Insert{

	public  function getInsert(){
		return implode(' ',[
			"INSERT",
			"INTO",
			$this->table,
			'('.$this->implodedColumns().')',
			"VALUES",
			$this->insertValues(),
			]);
	}

	public  function insertValues(){

		foreach ($this->values as &$record){
			foreach ($record as &$value)
				$value = $this->addParam($value);
			$record = '('.implode(', ', $record).')';
		}

		return implode(', ', $this->values);

	}



}
?>