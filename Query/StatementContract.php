<?php  

namespace Webtron\Database\Query;

interface StatementContract{

	public function getStatement();
	public function executeQuery();

}

?>