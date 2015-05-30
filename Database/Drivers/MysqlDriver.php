<?php 
namespace Webtron\Database\Drivers;

use PDO;		

class MysqlDriver extends Driver{

	public $config = [
		'database' => '',
		'host'     => '',
		'username' => '',
		'password' => '',
		'prefix'   => ''
	];

	public function connect(){

		$config = $this->config;

		// create PDO object
		$this->DB = new PDO('mysql:dbname='.$config['database'].';host='.$config['host'] , $config['username'] , $config['password']);
		
		// set PDO Attributes
		$this->DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ); 

	}

}

?>