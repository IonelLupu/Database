<?php  

namespace Webtron\Database;


use PDO;
use Closure;
use Webtron\Database\StatementFactory;

class Query{

	use Clauses\Select;
	use Clauses\Insert;
	use Clauses\Update;
	use Clauses\Delete;

	use Clauses\Aggregates;

	use Clauses\Where;
	use Clauses\GroupBy;
	use Clauses\OrderBy;
	use Clauses\Limit;

	use Clauses\Join;

	/**
	 * The query's table
	 * 
	 * @var string
	 */
	public $table = "" ;

	/**
	 * Returned columns
	 * 
	 * @var Array
	 */
	public $columns = [];

	/**
	 * The list of parameters bound in the query
	 * 
	 * @var Array
	 */
	public static $params     = [];

	/**
	 * Create the query object
	 * 
	 * @param string $table 
	 */
	public function __construct($table = ""){
		
		$this->table        = $table;

		return $this;
	}

	/**
	 * After a query has been fetched the next query has to be prepared 
	 * so we have to reset the params array
	 * 
	 * @return void 
	 */
	public function init(){

		Query::$params     = [];
	}

	/**
	 * Add a parameter to the params list
	 * 
	 * @param mixed $value 
	 * @param PDO::PARAM $type  
	 * @return String  
	 */
	protected function addParam($value,$type = PDO::PARAM_STR){

		if( is_null($value) )
			return;

		if( !DB::$paramBinding )
			return DB::$DB->quote($value);

		if( is_numeric($value) )
			$type = PDO::PARAM_INT;

		$param    = ":param".( count(self::$params) );

		self::$params[$param] = (object)[
			"value" => $value,
			"type" => $type
		];

		return $param;
	}


	/**
	 * Concatenate columns
	 * 
	 * @return void 
	 */
	protected function implodedColumns(){
		return implode(', ',$this->columns);
	}

	/**
	 * The default query statement
	 * Ir can be overwritten by other statements
	 * 
	 * @return Array 
	 */
	public function getStatement(){
		return [ 
			$this->getSelect(),
			$this->getWhere(),
			$this->getLimit()
		];
	}

	/**
	 * Create a new select statement based on the closure
	 * 
	 * @param  Closure $callback 
	 * @return QueryBuilder
	 */
	public function newQuery( Closure $callback ){

		call_user_func($callback, $query = new Query( DB::getTable($this->table) ) );

		return StatementFactory::make( 'select', $query );
	}

	/**
	 * Execute a select statement and get all the rows
	 * 
	 * @param  Array $columns
	 * @return QueryBuilder
	 */
	public function get($columns = ['*']){

		return StatementFactory::make('select',$this,$columns)->executeQuery();
	}

	/**
	 * Execute a select statement and get the first result
	 * 
	 * @param  Array $columns
	 * @return QueryBuilder
	 */
	public function first($columns = ['*']){

		return StatementFactory::make('select',$this,$columns)->executeQuery(false);
	}

	/**
	 * Execute a select statement and get the first result's value
	 * 
	 * @param  String $column
	 * @return String
	 */
	public function value($column){

		return StatementFactory::make('select',$this,['*'])->executeQuery(false)->{$column};
	}

	/**
	 * Execute a select statement by ID
	 * 
	 * @param  Int $id
	 * @param  Array  $columns
	 * @return QueryBuilder
	 */
	public function find( $id, $columns = ['*'] ,$primaryKey = 'id'){

		$this->where($primaryKey, '=', $id);

		return StatementFactory::make('select',$this,$columns)->executeQuery(false);
	}

	/**
	 * Insert one or more values into the database 
	 * and get the result's id
	 * 
	 * @param  Array  $values 
	 * @return Int
	 */
	public function insert(Array $values){

		return StatementFactory::make('insert',$this,$values)->executeQuery();
	}

	/**
	 * Execute an update statement
	 * 
	 * @param  Array $values
	 * @return Int
	 */
	public function update($values = []){

		return StatementFactory::make('update',$this,$values)->executeQuery();
	}

	/**
	 * Execute a delete statement
	 * 
	 * @return Int
	 */
	public function delete(){

		return StatementFactory::make('delete',$this)->executeQuery();
	}

	/**
	 * Truncate the table
	 * 
	 * @return Int
	 */
	public function truncate(){

		return StatementFactory::make('truncate',$this)->executeQuery();
	}

}
?>