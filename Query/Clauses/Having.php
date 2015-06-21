<?php 
namespace Webtron\Database\Query\Clauses;


trait Having {

	public $having = [];

	public function getHaving(){
		$having = [];
		foreach ($this->having as $link => $conditions){
			if( count($having) )
				$having[] = $link;
			$having[] = implode(" ".$link." ",$conditions);
		}

		$havingStmt = "";
		
		// we can't add the having clause if there 
		// is no GROUP BY clause in the query
		if(!empty($this->having) && count($this->groupBy))
			$havingStmt = "HAVING ".implode(' ',$having);;

		return $havingStmt;
	}

	public function having($column, $operator = null, $value = null, $link = 'AND'){

		if( !isset($this->having[$link]) )
			$this->having[$link] = [];

		$this->having[$link][] = 
			implode(" ",[ $column , $operator , $this->addParam($value) ]);
		
		return $this;

	}	
	public function orHaving($column, $operator = null, $value = null){

		return $this->having($column,$operator,$value,'OR');

	}	
}

?>