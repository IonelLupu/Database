<?php 

namespace Webtron\Database\Clauses;

use Closure;

trait WHERE {

	public $where = [];

	public function getWhere(){

		$where = [];

		// Build the actual where condition 
		foreach ( $this->where as $link => $conditions ){
			if( count($where) )
				$where[] = $link;
			$where[] = implode(" ".$link." ",$conditions);
		}
		
		$whereStmt = "";

		// If there are columns and conditions added to the query 
		// we will add the 'WHERE' clause to it otherwise
		// the WHERE clause remains as nested parameter grouping
		if(!empty($this->columns) && count($this->where))
			$whereStmt = "WHERE ";
		$whereStmt .= implode(' ',$where);

		return $whereStmt;
	}

	private function addWhere($data,$link = 'AND', $sub = false){

		if( !isset($this->where[$link]) )
			$this->where[$link] = [];

		foreach ($data as &$val) 
			if( is_array($val) )
				$val = "( ".implode(' ', array_filter($val) )." )";
		
		$this->where[$link][] = implode(" ",$data);
	}

	public function where($column ,$operator = null, $value = null, $link = "AND"){

		// If $column is an array, create a nested where and add the conditions 
		// as key-value pairs with the equal comparison between
		if (is_array($column))
		{
			return $this->whereNested(function($query) use ($column)
			{
				foreach ($column as $key => $value)
				{
					$query->where($key, '=', $value);
				}
			}, $link);
		}

		// If there are only two values passes to the method it means that the operator
		// is an equal sign and the $value is the $operator's value
		if (func_num_args() == 2)
		{
			list($value, $operator) = [$operator, '='];
		}

		// If the $column is a Closure, there is a nested where wrapped in parenthesis
		if ($column instanceof Closure)
		{
			return $this->whereNested($column, $link);
		}

		// If the $value is a Closure, we are performing a sub query
		if ($value instanceof Closure)
		{
			return $this->whereSub($column, $operator, $value, $link);
		}

		$this->addWhere( [ $column , $operator , $this->addParam($value) ], $link );
		
		return $this;

	}

	private function whereSub($column, $operator, Closure $callback, $link = 'AND'){

		$select = $this->newQuery( $callback );

		$this->addWhere( [$column , $operator,$select->getStatement() ], $link, true );

		return $this;
	}

	private function whereNested(Closure $callback, $link = 'AND'){

		$select = $this->newQuery( $callback );

		$this->addWhere( [ $select->getStatement() ], $link, true );

		return $this;
	}

	public function orWhere($column ,$operator = null,$value = null){

		return $this->where($column ,$operator,$value,"OR");
	}

	public function whereNull($column,$link = 'AND',$not = false){

		$type = $not ? 'IS NOT NULL' : 'IS NULL';

		$this->addWhere( [ $column , $type ], $link, true );

		return $this;
	}

	public function whereNotNull($column,$link = 'AND',$not = true){

		return $this->whereNull($column,$link,$not);
	}
	
	public function orWhereNull($column,$link = 'OR',$not = false){
		
		return $this->whereNull($column,$link,$not);
	}

	public function orWhereNotNull($column,$link = 'OR',$not = true){
		
		return $this->whereNull($column,$link,$not);
	}

	private  function whereType($column , $values = [],$link = "AND", $not = false,$type = "IN"){

		// If the $values is a Closure, we are performing a sub query for the where-in
		if ($values instanceof Closure)
		{
			return $this->whereInSub($column, $values, $link, $not);
		}

		// can't add empty values in whereIn condition
		if(empty($values))
			return $this;

		$not   = $not ? 'NOT '.$type : $type;

		// add bindings to the query and remember the binding's name 
		// to add them later to the sql statement
		foreach ($values as &$value ) {
			$value  = $this->addParam($value );
		}

		// check what type of where is( WHERE IN or WHERE BETWEEN)
		// and and the proper format for each
		$values = $type == 'IN'? "( ".implode(', ',$values)." )" : 
								implode(' AND ',$values);

		$this->addWhere( [ $column, $not, $values], $link);

		return $this;

	}
	protected function whereInSub($column, Closure $callback, $link = 'AND', $not = false)
	{

		$not   = $not ? 'NOT IN' : 'IN';

		$select = $this->newQuery( $callback );

		$this->addWhere( [ $column, $not, $select->getStatement() ], $link , true);

		return $this;
	}

	public  function whereIn($column , $values = []){
		
		return $this->whereType($column , $values ,'AND');
	}
	public  function whereNotIn($column , $values = []){
		
		return $this->whereType($column , $values ,'AND',true);
	}
	public  function orWhereIn($column , $values = []){
		
		return $this->whereType($column , $values ,'OR');
	}

	public  function orWhereNotIn($column , $values = []){
		
		return $this->whereType($column , $values ,'OR',true);
	}

	public  function whereBetween($column , $values = []){
		
		return $this->whereType($column , $values ,'AND',false,'BETWEEN');
	}

	public  function whereNotBetween($column , $values = []){
		
		return $this->whereType($column , $values ,'AND',true,'BETWEEN');
	}

	public  function orWhereBetween($column , $values = []){
		
		return $this->whereType($column , $values ,'OR',false,'BETWEEN');
	}

	public  function orWhereNotBetween($column , $values = []){
		
		return $this->whereType($column , $values ,'OR',true,'BETWEEN');
	}


}

?>