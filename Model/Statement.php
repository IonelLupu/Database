<?php 

namespace Webtron\Database\Model;

use PDO;
use PDOStatement;

use Webtron\Database\Query\Builder;

class Statement extends PDOStatement{

	public static $fetchClass;

	protected function __construct( $fetchClass = 'StdClass' ){
		
		// internally set the fetch class for later use
		self::$fetchClass = $fetchClass;
	}

	public function getResults( $results ){

		Builder::$DB->setAttribute( PDO::ATTR_STATEMENT_CLASS, [ __NAMESPACE__.'\DefaultStatement',[ ] ] );
		return $results;
	}

	public function fetch(){

		// go ahead and fetch, we should be good now
		$res = $this->getResults(parent::fetchObject( self::$fetchClass,[ [], true ] ) );
		return $res;
	}

	public function fetchAll(){
		
		// go ahead and fetch, we should be good now
		$res = $this->getResults( parent::fetchAll(PDO::FETCH_CLASS , self::$fetchClass,[ [], true] ) );

		// foreach ($res as &$r) {
		// 	$class = get_class($r);
		// 	$with =  \App\User::$with ;
			
		// 	foreach( $with as $relation ){
		// 		$r->setRelation($relation, $r->{$relation} );
		// 	}
		// }
		return $res;

	}
}

class DefaultStatement extends PDOStatement{

	public static $fetchClass;

	protected function __construct( $fetchClass = 'StdClass' ){
		
		// internally set the fetch class for later use
		self::$fetchClass = $fetchClass;
	}

	public function fetch(){

		// go ahead and fetch, we should be good now
		return parent::fetchObject();
	}

	public function fetchAll(){
		// go ahead and fetch, we should be good now
		return parent::fetchAll( PDO::FETCH_OBJ );
	}
}

