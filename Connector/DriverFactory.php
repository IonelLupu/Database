<?php  


namespace Webtron\Database\Connector;	

class DriverFactory {
	
	/**
	 * Create a new Driver object
	 * 
	 * @param  Array $config 
	 * @return Driver / Exception
	 */
	public static function make($config)
	{	
		$driver = Driver::$defaultDriver;
		if( isset( $config['driver'] ) )
			$driver = $config['driver'];

		$driverClass = __NAMESPACE__.'\\Drivers\\'.ucfirst( strtolower( $driver ) ).'Driver';

		if(class_exists($driverClass)){
			return new $driverClass($config);
		}
		else {
			throw new \Exception("Invalid database driver given.");
		}
		
	}
}
?>