<?php  

namespace Webtron\Database;

class StatementFactory 
{
	/**
	 * Create a new QueryBuilder object
	 * 
	 * @param  String $statement 
	 * @param  Query  $query     
	 * @param  Array $data
	 * @return QueryBuilder / Exception
	 */
	public static function make($statement,Query $query, $data = [])
	{
		$statement = __NAMESPACE__.'\Statements\\'.ucfirst(strtolower( $statement )).'Statement';
		if(class_exists($statement)){
			return new $statement($query,$data);
		}
		else {
			throw new \Exception("Invalid query statement given.");
		}
		
	}
}
?>