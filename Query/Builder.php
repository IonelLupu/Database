<?php 
namespace Webtron\Database\Query;

use PDO;
use Webtron\Database\Connector\DriverFactory;

use Webtron\Database\Connector\ConnectorContract;

class Builder {

	/**
	 * The PDO connection
	 * 
	 * @var PDO
	 */
	public static $DB;

	/**
	 * Set this to false if you want to see th final query
	 * without the bindings (recommended for development purposes)
	 * 
	 * @var boolean
	 */
	public static $paramBinding = true;

	/**
	 * Table prefix for database's tables
	 * 
	 * @var string
	 */
	public static $tablePrefix = '';

	/**
	 * Create the PDO connection to the database
	 * @param  Array $config 
	 *         		
	 * @return void
	 */
	public static function connect( ConnectorContract $connector ){

		self::$DB = $connector->connection();

		self::$tablePrefix = $connector->driver->config['prefix'];

	}

	public static function getTable( $table ){
		
		return self::$tablePrefix.$table;
	}

	/**
	 * Create a new query object
	 * 
	 * @param  String $table
	 * @return Query
	 */
	public static function table( $table ){

		return new Query( self::getTable( $table ) );
	}

	/**
	 * Change parameter binding option
	 * 
	 * @param  boolean $flag 
	 * @return void        
	 */
	public static function useParamBinding( $flag = true ){
		
		self::$paramBinding = $flag;
	}

	/**
	 * Change parameter binding option
	 * 
	 * @param  boolean $flag 
	 * @return void        
	 */
	public static function resultObject( $flag = true ){

		$mode = PDO::FETCH_ASSOC;
		if( $flag )
			$mode = PDO::FETCH_OBJ;
		
		self::$DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , $mode); 
	}


}

 ?>