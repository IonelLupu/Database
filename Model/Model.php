<?php 

namespace Webtron\Database\Model;

use PDO;
use JsonSerializable;
use Webtron\Database\Query\Builder;
use Webtron\Database\Query\Query;


abstract class Model implements JsonSerializable{

	use Relations;

	protected $table = '';

	protected $primaryKey = 'id';

	protected $attributes = [];

	protected $relations = [];

	// protected $eager = [];

	public $exists = false;

	// public static $with = [];

	public function __construct($attributes = null, $exists = false){

		$this->toArray($attributes);

		$this->exists = $exists;
		
		// set the fetch mode
		Builder::$DB->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS );
		Builder::$DB->setAttribute( PDO::ATTR_STATEMENT_CLASS, [ __NAMESPACE__.'\Statement',[ get_class($this)] ] );
	}
	
	public function newQuery(){

		return new Query($this->table);
	} 

	public static function query(){
		
		return (new static)->newQuery();
	} 

	public static function all($columns = ['*']){

		return (new static( [], true ))->newQuery()->get($columns);
	}

	public static function find($id, $columns = ['*']){
		
		$model = new static( [], true );

		$attributes = $model->newQuery()->find($id, $columns, $model->primaryKey);
		
		// If the query returns an array it means that the model was found
		// in the database and we will get those attributes
		if( $attributes ){

			$model->toArray($attributes);
			$model->exists     = true;
			return $model;
		}
		return false;
	} 

	public static function findOrNew($id, $columns = ['*']){

		if( $model = self::find($id, $columns) )
			return $model;

		return new static;
	} 

	public function delete( $id = null ){

		if( is_null($id) )
			$id = $this->getAttr( $this->primaryKey );

		if( $this->exists ){

			$this->newQuery()
					  ->where( $this->primaryKey, $id)
					  ->delete();
			$this->exists = false;
		}
	}
	public static function destroy($ids){

		$ids = is_array($ids) ? $ids : func_get_args();

		$model = new static;

		return	self::query()->whereIn($model->primaryKey, $ids)->delete();
	}


	public function save(){
		
		// If the model does not have the 'primaryKey' attribute value set
		// means that we have to insert a brand new row in the table
		if( !$this->exists ){

			$id           = $this->newQuery()->insert( $this->attributes );
			$this->exists = true;
			$this->setAttr( $this->primaryKey , $id );
		}

		// If the model has the 'primaryKey' attribute value set
		// we have to perform an update only
		else{

			$primaryKeyValue = $this->getAttr( $this->primaryKey );

			$this->newQuery()
				 ->where( $this->primaryKey, $primaryKeyValue )
				 ->update( $this->attributes );
		}		
	}

	public static function create($attributes){

		$model = new static($attributes);

		$model->save();

		return $model;
	}

	public static function firstOrCreate($attributes){
		
		if ( $model = static::where($attributes)->first() )
			return new static( $model , true);
		
		return static::create($attributes);
	}

	public static function firstOrNew($attributes){
		
		if ( $model = static::where($attributes)->first() )
			return new static( $model , true);
		
		return new static($attributes);
	}

	// public static function with($relations){
		
	// 	$relations = explode('.', $relations);
	// 	static::$with = $relations;

	// 	return static::query();
	// }

	public function toArray($attributes = null){

		if( ! is_null( $attributes ) ){

			foreach( $attributes as $attrName => $attrVal )
				$this->setAttr($attrName, $attrVal);
		}
		
		// foreach ($this->eager as $relationName => $relation) {
		// 	$this->attributes[$relationName] = $relation->toarray();
		// }

		return $this->attributes;
	}

	// public function setRelation($name, $model){
		
	// 	$this->eager[$name] = $model;
	// }

	public function getTable(){
		
		return $this->table;
	}
	public function __set($name, $value){

		$this->attributes[$name] = $value;
	}

	public function setAttr($attribute, $value){
		
		$this->{$attribute} = $value;
	}

	public function __get($attribute){

		// Check if the method exists in the model
		// If so, create a shortcut for the relations
		// by calling the method with this name
		if( method_exists( $this, $attribute ) ){
			return $this->getResults( $attribute );
		}
		
		return $this->attributes[$attribute];
	}

	public function getResults( $attribute ){
		
		$query = call_user_func([$this, $attribute]);

		if( isset($this->relations['hasOne']) || isset($this->relations['belongsTo']) )
			return $query->first();
		
		return $query->get();
	}
	
	public function getAttr($attribute){
		
		return $this->{$attribute};
	}

	public static function __callStatic($method, $parameters){

		return call_user_func_array([ static::query(), $method ], $parameters);
	}

	public function __toString(){
		
		return json_encode( $this->toArray() ,true );
	}

	public function jsonSerialize(){
        return (string) $this;
    }

}