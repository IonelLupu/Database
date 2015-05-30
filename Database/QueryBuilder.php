<?php  

namespace Webtron\Database;

abstract class QueryBuilder{

	/**
	 * The query instance
	 * 
	 * @var Query
	 */
	public $query ;

	/**
	 * Create the QueryBuilder
	 * 
	 * @param Query $query
	 */		
	public function __construct(Query $query){
		$this->query = $query;
		$this->prepare();
	}

	/**
	 * Prepare the statement and bind the parameters
	 * 
	 * @return void
	 */
	protected function prepare(){

		$this->final = DB::$DB->prepare(implode(' ', array_filter($this->getStatement()) ) );
		
		// bind parameters
		foreach (Query::$params as $key => $param) {
			
			$this->final->bindParam($key, $param->value, $param->type);
		}
	}

	/**
	 * Execute the query and return it's result
	 * 
	 * @return [type] [description]
	 */
	protected function execute(){
		$q = $this->final->execute();
		
		print_r([
			"queryString" => $this->final->queryString,
			"params"      => Query::$params,
			"error"       => $this->final->errorInfo()
			]);

		$this->query->init();

		return $q;
	}

	/**
	 * Fetch the query and return the results
	 * 
	 * @param  boolean $all 
	 * @return Array 
	 */
	protected function fetch($all = true){

		$this->execute();

		if($all)
			return $this->final->fetchAll();

		return $this->final->fetch();
		
	}

}

?>