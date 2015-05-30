<?php 
namespace Webtron\Database\Clauses;

use Closure;

trait Join {

	public $joins = [];

	public function getJoins(){

		$joinStmt = [];
		
		foreach ($this->joins as $joinClause)
			$joinStmt[]    = $this->processJoin( $joinClause );

		return implode(' ',$joinStmt);
	}

	public function processJoin($sub){

		if($sub->table instanceof JoinClause)
			$sub->table = '( '. $this->processJoin( $sub->table ) .' )';

		$joinStmt = [ $sub->first,$sub->type,'JOIN',$sub->table,'ON' ];
		

		$clauses  = [];

		foreach ($sub->clauses as $link => $clause){

			foreach ($clause as $c) {
				
				// add bindings
				if( isset($sub->bindings[0]) && $c['second'] == $sub->bindings[0] )
					$c['second'] = $this->addParam( array_shift ($sub->bindings) );

				if( count( $clauses ) )
					$clauses[] = $link;
				$clauses[] = implode(' ',$c);
			}

		}
		$joinStmt[]     = implode(' ',$clauses);

		return implode(' ',array_filter($joinStmt));

	}

	public function join($table, $one, $operator = null, $two = null, $type = 'INNER'){

		if($table instanceof Closure){

			call_user_func($table, $join = new JoinClause());

			$join = new JoinClause($type, $join);

			$this->joins[] = $join->on($one, $operator, $two);

		}elseif($one instanceof Closure){

			$this->joins[] = new JoinClause($type, $table);
			
			call_user_func($one, end($this->joins));

		}else{

			$join = new JoinClause($type, $table);

			$this->joins[] = $join->on($one, $operator, $two);
			
		}

		return $this;
	}

	public function leftJoin($table, $one, $operator = null, $two = null){

		return $this->join($table, $one, $operator, $two, "LEFT");
	}

	public function rightJoin($table, $one, $operator = null, $two = null){

		return $this->join($table, $one, $operator, $two, "RIGHT");
	}
}

?>