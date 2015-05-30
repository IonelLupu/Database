<?php 

namespace Webtron\Database\Statements;

use Webtron\Database\DB;
use Webtron\Database\Query;
use Webtron\Database\QueryBuilder;
use Webtron\Database\StatementContract;

class InsertStatement extends QueryBuilder implements StatementContract{
	
	public function __construct(Query $query,Array $values){
	
		// We can't add empty data to the database
		if ( empty($values) ) return true;

		// The INSERT statement inserts data as bulk by default 
		// so we have to heck if the users tries to insert
		// a single row in the database and make it bulk too
		if ( ! is_array(reset($values)) )
			$values = [$values];
		else{
			foreach ($values as $key => $value){
				ksort($value);
				$values[$key] = $value;
			}
		}
		
		$query->columns = array_keys(reset($values));
		$query->values  = $values;
		
		parent::__construct($query);
	}
	public function executeQuery(){

		$this->execute();
		return DB::$DB->lastInsertId();
	}

	public function getStatement(){
		return [
			$this->query->getInsert()
			];
	}



}

?>