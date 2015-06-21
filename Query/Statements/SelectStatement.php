<?php 

namespace Webtron\Database\Query\Statements;

use Webtron\Database\Query\Query;
use Webtron\Database\Query\QueryBuilder;
use Webtron\Database\Query\StatementContract;

class SelectStatement extends QueryBuilder implements StatementContract{

	public function __construct(Query $query,$columns = ['*']){

		// prevent this method to overwrite the columns added through select method from 'Query'
		if( isset($columns[0]) && $columns[0] == '*' && count($query->columns) > 0 )
			$columns = [];

		$query->columns = array_merge($query->columns,$columns);

		parent::__construct($query);
	}

	public function executeQuery($all = true){
		
		return $this->fetch($all);
	}
	public function getStatement(){
		return [
			$this->query->getSelect(),
			$this->query->getWhere(),
			$this->query->getJoins(),
			$this->query->getGroupBy(),
			$this->query->getHaving(),
			$this->query->getOrderBy(),
			$this->query->getLimit()
			];
	}

}

?>
