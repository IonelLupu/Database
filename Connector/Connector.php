<?php 


namespace Webtron\Database\Connector;

class Connector implements ConnectorContract{

	public function __construct($config){

		$this->driver = DriverFactory::make( $config );

		$this->driver->connect();
	}

	public function connection(){

		return $this->driver->DB;
	}
}