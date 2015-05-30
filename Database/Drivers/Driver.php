<?php 
namespace Webtron\Database\Drivers;

abstract class Driver implements DriverInterface{

	public static $defaultDriver = 'mysql';

	public $DB;

	public $config = [
				'driver'   => 'mysql',
				'database' => '',
				'host'     => '',
				'username' => '',
				'password' => '',
				'prefix'   => ''
				];

	public function __construct($config){
		
		$this->config = array_merge($this->config, $config);
	}

}


?>