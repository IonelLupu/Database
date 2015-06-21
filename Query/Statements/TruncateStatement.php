<?php 

namespace Webtron\Database\Query\Statements;

use Webtron\Database\Query\Query;
use Webtron\Database\Query\QueryBuilder;
use Webtron\Database\Query\StatementContract;

class TruncateStatement extends QueryBuilder implements StatementContract{

	public function __construct(Query $query){

		parent::__construct($query);
	}

	public function executeQuery(){
		
		return $this->execute();
	}
	public function getStatement(){
		return [
			$this->query->getTruncate()
			];
	}

}

?>
