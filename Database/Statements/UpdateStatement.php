<?php 

namespace Webtron\Database\Statements;

use Webtron\Database\QueryBuilder;
use Webtron\Database\Query;
use Webtron\Database\StatementContract;

class UpdateStatement extends QueryBuilder implements StatementContract{


	public function __construct(Query $query,$values = []){

		$query->values  = $values;
		
		parent::__construct($query);
	}

	public function executeQuery(){

		return $this->execute();
	}
	public function getStatement(){
		return [
			$this->query->getUpdate(),
			$this->query->getWhere(),
			$this->query->getLimit()
			];
	}



}

?>