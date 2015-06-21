<?php  
namespace Webtron\Database\Query\Clauses;

trait Update{

	public  function getUpdate(){
		$this->setUpdateValues();

		return implode(' ',[
			"UPDATE",
			$this->table,
			"SET",
			$this->implodedColumns(),
			]);
	}

	public function setUpdateValues(){
		
		foreach ($this->values as $key => $value) {
			if(is_int($key))
				$this->columns[] = $value;
			else
				$this->columns[] = $key." = ". $this->addParam($value);
		}

	}

	public function increment($column, $amount = 1){

		$this->columns[] = "$column = $column + $amount";
		return StatementFactory::make('update',$this)->executeQuery();
	}

	public function decrement($column, $amount = 1){

		$this->columns[] = "$column = $column - $amount";
		return StatementFactory::make('update',$this)->executeQuery();
	}

}
?>