<?php 

namespace Webtron\Database\Model;

use Webtron\Database\Query\Builder;

trait Relations{

	public function getForeignKey( $model ){

		$class = $model;
		if( is_object( $model ) )
			$class = get_class( $model );

		return strtolower( end(explode('\\', $class)) ).'_id';
	}

	public function fullColumn( $column ){

		return $this->getTable().'.'.$column;
	}

	public function fullPK(){

		return $this->fullColumn( $this->primaryKey );
	}

	public function addRelation( $relation = null, $model = null, $foreignKey = null, $localKey = null){
		
		if( !is_null($relation) ){
			$this->relations[$relation] = [
				'model'      => $model,
				'foreignKey' => $foreignKey,
				'localKey'   => $localKey,
			];
		}

	}

	public function hasOne( $model, $foreignKey = null, $localKey = null ){

		$this->addRelation(__FUNCTION__, $model, $foreignKey, $localKey );

		return $this->hasMany( $model, $foreignKey, $localKey, false);
	}

	public function hasMany( $related, $foreignKey = null, $localKey = null ,$all = true){

		$model = new $related;

		$foreignKey = $foreignKey ?: $this->getForeignKey( $this );
		$localKey   = $localKey ?: $this->primaryKey;

		$model->addRelation(__FUNCTION__, $related, $foreignKey, $localKey);

		return  $model::where( $foreignKey, $this->getAttr( $localKey ) );
	}

	public function belongsTo( $model, $foreignKey = null, $localKey = null){

		$model = new $model;

		$foreignKey = $foreignKey ?: $this->getForeignKey( $model );
		$localKey   = $localKey ?: $model->primaryKey;

		$model->addRelation(__FUNCTION__, $foreignKey, $localKey);

		return $model::where( $localKey, $this->getAttr( $foreignKey ) );
	}

	public function hasManyThrough( $model, $through, $firstKey = null, $secondKey = null){

		$model   = new $model;
		$through = new $through;
		
		$firstKey  = $firstKey ?: $this->getForeignKey( $this );
		$secondKey = $secondKey ?: $this->getForeignKey( $through );

		$model->addRelation(__FUNCTION__, $model, $firstKey, $secondKey);

		return $model::select([$model->getTable().'.*'])
					->join($through->getTable(), $through->fullPK(),'=', $model->fullColumn($secondKey) )
					->join($this->getTable(),function($join) use ( $through, $firstKey ) {
						$join->on($this->fullPK(),'=',$through->fullColumn($firstKey))
							 ->where($this->fullPK(),'=',$this->getAttr( $this->primaryKey ));
					});

	}
								    // 'roles'	'user_roles' 'user_id'	  'role_id'
	public function belongsToMany( $related, $through, $firstKey = null, $secondKey = null){

		$related = new $related;
		
		$firstKey  = $firstKey ?: $this->getForeignKey( $this );
		$secondKey = $secondKey ?: $this->getForeignKey( $related );

		$related->addRelation(__FUNCTION__, $related, $firstKey, $secondKey);
		
		return $related::select([$related->getTable().'.*'])
					->join($through, $related->fullPK(),'=', $through.'.'.$secondKey )
					->join($this->getTable(),function($join) use ( $firstKey, $through ) {
						$join->on($this->fullPK(),'=',$through.'.'.$firstKey)
							 ->where($this->fullPK(),'=',$this->getAttr( $this->primaryKey ));
					});

	}



}