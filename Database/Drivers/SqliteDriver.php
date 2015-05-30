<?php 
namespace Webtron\Database\Drivers;


class SqliteDriver extends Driver{

	public $config = [
		'database' => '',
		'prefix'   => ''
	];

	public function connect(){
		// create PDO object
		$this->DB = new PDO('sqlite:'.$this->config['database']);

		// set PDO Attributes
		$this->DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_OBJ); 

	}

}

?>