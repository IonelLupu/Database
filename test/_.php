<?php 

class DB {

	private static $instance;

	public static $DB;

	public $table 	= "";

	public $columns = [];

	public $where 	= [];

	public $groupBy = [];

	public $limit 	= [];

	public $params            = [];
	public static $paramIndex = 0;

	public $subQueries = [];



	public static function connect($config){
		// create PDO object
		self::$DB = new PDO('mysql:dbname='.$config['database'].';host='.$config['host'] , $config['username'] , $config['password']);
		
		// // set PDO Attributes
		self::$DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ); 
	}



	/***********************************************
	 * Private methods
	 ***********************************************/

	private function buildQuery($all = true){
		
		$instance = self::$instance;

		$query = $instance->getQuery();
		print_r($instance->params);
		print_r($query);

		$query->execute($instance->params);
		print_r($query->errorInfo());
		if($all)
			return $query->fetchAll();
		return $query->fetch();
	}
	private function getQuery(){
		
		$instance = self::$instance;

		// get columns
		$columns = implode($instance->columns,",");

		// get table(s)
		$tables  = $instance->table;
		// compute limits
		$limit   = count($instance->limit) > 0 ?
					"LIMIT ".implode($instance->limit,',') : "";

		// compute where conditions
		$where = [];
		foreach ($instance->where as $link => $conditions){
			if(count($where))
				$where[] = $link;
			$where[] = implode(" ".$link." ",$conditions);
		}
		$where = count($instance->where)?" WHERE ".implode(' ',$where):"";

		// compute groupBy
		$groupBy = count($instance->groupBy) > 0 ?
					"GROUP BY ".implode($instance->groupBy,',') : "";


		$query = self::$DB->prepare(
			"SELECT $columns FROM $tables $where $groupBy $limit"
			);

		return $query;
	}

	private function addParam($value){
		$instance = self::$instance;

		if(is_null($value) )
			return;

		$param    = ":param".(self::$paramIndex++);

		$instance->params[$param] = $value;

		return $param;
	}

	/***********************************************
	 * Starting methods
	 ***********************************************/

	public static function table($tableName){
		
		$instance = self::$instance = new static;

		$instance->table = $tableName;

		return self::$instance;

	}
	public  function all($tableName,$columns = ['*']){

		return self::table($tableName)->get($columns);

	}
	/***********************************************
	 * Condition methods
	 ***********************************************/

	public  function where($column ,$operator = null,$value = null,$link = "AND"){

		$instance = self::$instance;

		// if operator in an array means that you are using whereIn condition
		if(is_array($operator)){
			$operator = implode($operator);
		}else{
			// if value is null means that operator is '='
			if(is_null($value) && !is_null($operator)){
				$value    = $operator;
				$operator = '=';
			}
		}

		// for advanced queries (subqueries)
		if($value instanceof Closure ){
			$query           = call_user_func($value);
			$query->select([$column]);
			$subQuery = " ( ".$query->getQuery()->queryString.")";
			$instance->where[$link][] = 
				implode(" ",[ $column , $operator.$subQuery , "" ]);

			foreach ($query->params as $paramName => $paramValue) {
				$instance->params[$paramName] = $paramValue;
			}
			self::$instance = $instance;
			return $instance;
		}
		

		if( !isset($instance->where[$link]) )
			$instance->where[$link] = [];

		$instance->where[$link][] = 
			implode(" ",[ $column , $operator , $instance->addParam($value) ]);
		
		return $instance;

	}


	public  function whereNull($column){

		return self::$instance->where($column,'=',"NULL");

	}

	public  function orWhere($column ,$operator = null,$value = null){

		return self::$instance->where($column,$operator,$value,"OR");

	}


	private  function whereType($column , $values = [],$link = "AND", $not = false,$type = "IN"){
		$instance = self::$instance; 

		// can't add empty values in whereIn condition
		if(empty($values))
			return $instance;

		$not   = $not ? " NOT ".$type." " : " ".$type." ";


		foreach ($values as &$value ) {
			$value  = $instance->addParam($value );
		}

		$values = $type == "IN"? "(".implode(',',$values).")" : 
								implode(' AND ',$values);

		return $instance->where($column,[$not.$values],null,$link);

	}
	

	public  function whereIn($column , $values = [],$link = "AND", $not = false){
		
		return self::$instance->whereType($column , $values ,$link , $not ,"IN");

	}

	public  function whereNotIn($column , $values = []){
		
		return self::$instance->whereIn($column,$values,"OR",true);
	}

	public  function orWhereIn($column , $values = []){
		
		return self::$instance->whereIn($column,$values,"OR");

	}
	public  function orWhereNotIn($column , $values = []){
		
		return self::$instance->whereIn($column,$values,"OR",true);

	}
	public  function whereBetween($column , $values = [],$link = "AND", $not = false){
		
		return self::$instance->whereType($column , [min($values),max($values)] ,$link , $not ,"BETWEEN");

	}

	public  function whereNotBetween($column , $values = []){
		
		return self::$instance->whereBetween($column,$values,"OR",true);
	}

	public  function orwhereBetween($column , $values = []){
		
		return self::$instance->whereBetween($column,$values,"OR");

	}
	public  function orWhereNotBetween($column , $values = []){
		
		return self::$instance->whereBetween($column,$values,"OR",true);

	}

	public  function limit($start ,$chunk = null){

		self::$instance->limit = [$start];
		if($chunk)
			self::$instance->limit[] = $chunk;

		return self::$instance;

	}
	public  function groupBy($columns = []){

		self::$instance->groupBy = array_merge(self::$instance->groupBy,$columns);

		return self::$instance;

	}
	public  function select($columns = ['*']){

		self::$instance->columns = $columns;

		return self::$instance;

	}
	public  function addSelect($columns = []){

		self::$instance->columns = array_merge(self::$instance->columns,$columns);

		return self::$instance;

	}


	/***********************************************
	 * Execute methods
	 ***********************************************/


	public function first($columns = ['*']){

		return self::$instance->execute($columns , false);
	}

	public function get($columns = ['*']){

		return self::$instance->execute($columns , true);
	}

	private function execute($columns ,$all = true){

		// prevent this method to overwrite the columns added through select method
		if($columns[0] == '*' && count(self::$instance->columns) > 0 )
			$columns = [];

		self::$instance->columns = array_merge(self::$instance->columns,$columns);

		return self::$instance->buildQuery($all);
	}

}

 ?>