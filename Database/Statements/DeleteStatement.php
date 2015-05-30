<?php 

namespace Webtron\Database\Statements;

use Webtron\Database\QueryBuilder;
use Webtron\Database\Query;
use Webtron\Database\StatementContract;

class DeleteStatement extends QueryBuilder implements StatementContract{

	public function __construct(Query $query){

		$query->columns = ['*'];
		parent::__construct($query);
	}

	public function executeQuery(){
		
		return $this->execute();
	}
	
	public function getStatement(){
		return [
			$this->query->getDelete(),
			$this->query->getWhere(),
			$this->query->getOrderBy(),
			$this->query->getLimit()
			];
	}

}

?>
