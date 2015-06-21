<?php 
namespace Webtron\Database\Query\Clauses;


class JoinClause {
	
	/**
	 * The type of join being performed.
	 *
	 * @var String
	 */
	public $type;

	/**
	 * The table used for subjoining.
	 *
	 * @var String
	 */
	public $first;

	/**
	 * The table the join clause is joining to.
	 *
	 * @var String
	 */
	public $table;

	/**
	 * The "on" clauses for the join.
	 *
	 * @var array
	 */
	public $clauses = [];

	/**
	 * The "on" bindings for the join.
	 *
	 * @var array
	 */
	public $bindings = [];


	/**
	 * Create a new join clause instance.
	 *
	 * @param  String  $type
	 * @param  String  $table
	 * @return void
	 */
	public function __construct($type = 'INNER', $table = ''){

		$this->type  = $type;
		$this->table = $table;
	}

	/**
	 * Add an "on" clause to the join.
	 *
	 * @param  String  $first
	 * @param  String  $operator
	 * @param  String  $second
	 * @param  String  $link
	 * @param  bool  $where
	 * @return $this
	 */
	public function on($first, $operator, $second, $link = 'AND'){

		$this->clauses[$link][] = compact('first', 'operator', 'second');

		return $this;
	}
	/**
	 * Add an "or on" clause to the join.
	 * 
	 * @param  String  $first
	 * @param  String  $operator
	 * @param  String  $second
	 * @return $this
	 */
	public function orOn($first, $operator, $second){

		return $this->on($first, $operator, $second, 'OR');
	}

	/**
	 * Add an "on where" clause to the join.
	 *
	 * @param  String  $first
	 * @param  String  $operator
	 * @param  String  $second
	 * @param  String  $link
	 * @return $this
	 */
	public function where($first, $operator, $second, $link = 'AND')
	{
		$this->bindings[] = $second;
		return $this->on($first, $operator, $second, $link, true);
	}

	/**
	 * Set the first table name
	 * 
	 * @param  String $first 
	 * @return $this
	 */
	public function table($first){

		$this->first = $first;

		return $this;
	}

	/**
	 * Add a new join based on type
	 * 
	 * @param  String $type     
	 * @param  String $table    
	 * @param  String $first    
	 * @param  String $operator 
	 * @param  String $second   
	 * @param  String $link     
	 * @return $this
	 */
	public function type($type, $table, $first, $operator = null, $second = null, $link = 'AND'){

		$this->type  = $type;
		$this->table = $table;
		$this->on($first, $operator, $second, $link);

		return $this;
	}

	public function inner($table, $first, $operator = null, $second = null, $link = 'AND'){

		return $this->type( strtoupper(__FUNCTION__), $table, $first, $operator, $second, $link );
	}

	public function left($table, $first, $operator = null, $second = null, $link = 'AND'){

		return $this->type( strtoupper(__FUNCTION__), $table, $first, $operator, $second, $link );
	}

	public function outer($table, $first, $operator = null, $second = null, $link = 'AND'){

		return $this->type( strtoupper(__FUNCTION__), $table, $first, $operator, $second, $link );
	}

	public function right($table, $first, $operator = null, $second = null, $link = 'AND'){

		return $this->type( strtoupper(__FUNCTION__), $table, $first, $operator, $second, $link );
	}

}

?>