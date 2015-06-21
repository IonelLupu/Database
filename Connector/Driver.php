<?php 
namespace Webtron\Database\Connector;

abstract class Driver implements DriverContract{

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