<?php 

namespace Webtron\Database\Statements;

use Webtron\Database\QueryBuilder;
use Webtron\Database\Query;
use Webtron\Database\StatementContract;

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
