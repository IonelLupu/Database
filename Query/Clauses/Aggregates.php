<?php 

namespace Webtron\Database\Query\Clauses;

trait Aggregates {

	public function aggregate($function, $column = '*'){

		$this->columns[] = strtoupper($function)."( $column ) as value";
		return StatementFactory::make('select',$this)->executeQuery(false)->value;

	}
	public function count($column = '*'){

		return $this->aggregate(__FUNCTION__, $column);
	}

	public function avg($column = '*'){

		return $this->aggregate(__FUNCTION__, $column);
	}

	public function sum($column = '*'){

		return $this->aggregate(__FUNCTION__, $column);
	}
	
	public function max($column = '*'){

		return $this->aggregate(__FUNCTION__, $column);
	}

	public function min($column = '*'){

		return $this->aggregate(__FUNCTION__, $column);
	}

}

?>