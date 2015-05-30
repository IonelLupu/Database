<?php  

namespace Webtron\Database;

interface StatementContract{

	public function getStatement();
	public function executeQuery();

}

?>