<?php 
namespace Webtron\Database\Clauses;


trait LIMIT {

	public $limit = [];

	public function getLimit(){
		if( count($this->limit) )
			return "LIMIT ".implode(',', $this->limit);
		return "";
	}

	public  function limit($start ,$chunk = null){

		$this->limit = [$this->addParam($start)];
		if($chunk)
			$this->limit[] = $this->addParam($chunk);

		return $this;

	}	
}

?>