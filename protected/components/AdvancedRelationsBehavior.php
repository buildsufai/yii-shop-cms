<?php

/**
 * AdvancedRelationsBehavior behavior class.
 * @see http://www.yiiframework.com/extension/advancedrelationsbehavior/
 * @version 1.0.6
 */
class AdvancedRelationsBehavior extends CActiveRecordBehavior
{
	/**
	 * @var array the relations list to be handled
	 */
	public $relations;
	/**
	 * @var boolean automatic update related models
	 */
	public $autoUpdate = false;
	/**
	 * @var boolean automatic delete related models
	 */
	public $autoDelete = true;
	/**
	 * @var string the sort order attribute, set it to NULL to disable auto-sort
	 */
	public $sortAttribute = 'sort';

	/**
	 * Returns HAS_MANY and MANY_MANY relations.
	 * @return array
	 */
	protected function getRelations()
	{
		if(null === $this->relations)
		{
			$this->relations = array();
			foreach($this->owner->relations() as $name=>$relation)
				if(($relation[0] == CActiveRecord::HAS_ONE || $relation[0] == CActiveRecord::HAS_MANY) ||
					$relation[0] == CActiveRecord::MANY_MANY)
					$this->relations[] = $name;
		}
		return $this->relations;
	}

	/**
	 * Returns PK hash, support composite PK.
	 * @param mixed $pk
	 * @return string
	 */
	protected function getPkHash($pk)
	{
		if(is_array($pk))
			$pk = implode('##', $pk);
		return md5($pk);
	}

	/**
	 * Update related models for HAS_MANY or MANY_MANY relations.
	 * Returns TRUE on success or FALSE on failure.
	 * @param mixed $relations the list of relations, class configuration will be used, if not set
 	 * @return boolean
	 */
	public function updateRelated($relations = null)
	{
		if(null === $relations)
			$relations = $this->getRelations();

		if(empty($relations))
			return true;

		$success = true;

		$_relations = $this->owner->relations();

		foreach((array)$relations as $name)
		{
			if(!isset($_relations[$name]) || $this->owner->$name === false)
				continue;

			$relation = $_relations[$name];

			if($relation[0] == CActiveRecord::HAS_ONE || $relation[0] == CActiveRecord::HAS_MANY)
			{
				$modelClass = $relation[1];
				$foreignKey = isset($relation[2]) ? $relation[2] : null;

				if(!is_array($this->owner->$name))
					$this->owner->$name = array($this->owner->$name);

				$related = array();

				$criteria = new CDbCriteria;
				$criteria->compare($foreignKey, $this->owner->primaryKey);
				if(($models = CActiveRecord::model($modelClass)->findAll($criteria)))
				{
					foreach($models as $model)
						$related[$this->getPkHash($model->primaryKey)] = $model;
				}

				foreach($this->owner->$name as $sort=>$model)
				{
					if(!is_object($model))
						$model = CActiveRecord::model($modelClass)->findByPk($model);
					if(!is_object($model))
						continue;

					$model->$foreignKey = $this->owner->primaryKey;

					if(!empty($this->sortAttribute) && $model->hasAttribute($this->sortAttribute))
						$model->{$this->sortAttribute} = $sort;

					if(!$model->save())
						$success = false;
					else
						unset($related[$this->getPkHash($model->primaryKey)]);
				}

				foreach($related as $model)
					$model->delete();
			}
			elseif($relation[0] == CActiveRecord::MANY_MANY)
			{
				if(preg_match('/^\s*[\{]*([^\}]+?)[\}]*\s*\(\s*([^,\s]+)\s*,\s*([^\)]+?)\s*\)\s*$/s', $relation[2], $m))
				{
					$modelClass = str_replace(' ', '', ucwords(str_replace('_', ' ', $m[1])));
					$primaryKey = $m[2];
					$foreignKey = $m[3];

					$IDs = array();

					if(!is_array($this->owner->$name))
						$this->owner->$name = array($this->owner->$name);

					foreach($this->owner->$name as $model)
					{
						if(!is_object($model))
							$model = CActiveRecord::model($relation[1])->findByPk($model);
						if(!is_object($model))
							continue;
						if($model->isNewRecord)
						{
							if(!$model->save())
								continue;
						}
						if(!CActiveRecord::model($modelClass)->countByAttributes(array(
							$primaryKey => $this->owner->primaryKey,
							$foreignKey => $model->primaryKey,
							)))
						{
							$temp = new $modelClass;
							$temp->$primaryKey = $this->owner->primaryKey;
							$temp->$foreignKey = $model->primaryKey;
							if(!$temp->save())
								$success = false;
						}
						$IDs[] = $model->primaryKey;
					}
					$criteria = new CDbCriteria;
					$criteria->compare($primaryKey, $this->owner->primaryKey);
					$criteria->addNotInCondition($foreignKey, $IDs);
					CActiveRecord::model($modelClass)->deleteAll($criteria);
				}
			}
		}
		return $success;
	}

	/**
	 * Delete related models for HAS_MANY or MANY_MANY relations.
	 * Returns TRUE on success or FALSE on failure.
	 * @param mixed $relations the list of relations, class configuration will be used, if not set
	 * @return boolean
	 */
	public function deleteRelated($relations = null)
	{
		if(null === $relations)
			$relations = $this->getRelations();

		if(empty($relations))
			return true;

		$success = true;

		$_relations = $this->owner->relations();

		foreach((array)$relations as $name)
		{
			if(!isset($_relations[$name]) || $this->owner->$name === false)
				continue;

			$relation = $_relations[$name];

			if($relation[0] == CActiveRecord::HAS_ONE || $relation[0] == CActiveRecord::HAS_MANY)
			{
				$modelClass = $relation[1];

				if(!is_array($this->owner->$name))
					$this->owner->$name = array($this->owner->$name);

				foreach($this->owner->$name as $model)
				{
					if(!is_object($model))
						$model = CActiveRecord::model($modelClass)->findByPk($model);
					if(!is_object($model))
						continue;
					if(!$model->delete())
						$success = false;
					unset($model);
				}
				unset($this->owner->$name);
			}
			elseif($relation[0] == CActiveRecord::MANY_MANY)
			{
				if(preg_match('/^\s*[\{]*([^\}]+?)[\}]*\s*\(\s*([^,\s]+)\s*,\s*([^\)]+?)\s*\)\s*$/s', $relation[2], $m))
				{
					$modelClass = str_replace(' ', '', ucwords(str_replace('_', ' ', $m[1])));
					$primaryKey = $m[2];

					CActiveRecord::model($modelClass)->deleteAllByAttributes(array(
						$primaryKey => $this->owner->primaryKey
					));
					unset($this->owner->$name);
				}
			}
		}
		return $success;
	}

	/**
	 * Update current and related models. Returns TRUE on success or FALSE on failure.
	 * @param mixed $relations the list of relations, class configuration will be used, if not set
	 * @return boolean
	 */
	public function saveWithRelated($relations = null)
	{
		if($this->owner->save())
		{
			if($this->autoUpdate)
				return true;
			return $this->updateRelated($relations);
		}
		return false;
	}

	/**
	 * Delete current and related models. Returns TRUE on success or FALSE on failure.
	 * @param mixed $relations the list of relations, class configuration will be used, if not set
	 * @return boolean
	 */
	public function deleteWithRelated($relations = null)
	{
		if($this->owner->delete())
		{
			if($this->autoDelete)
				return true;
			return $this->deleteRelated($relations);
		}
		return false;
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterSave} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CModelEvent event parameter
	 */
	public function afterSave($event)
	{
		if($this->autoUpdate)
			$this->updateRelated($this->relations);
		parent::afterSave($event);
	}

	/**
	 * Responds to {@link CActiveRecord::onAfterDelete} event.
	 * Overrides this method if you want to handle the corresponding event of the {@link CBehavior::owner owner}.
	 * @param CEvent event parameter
	 */
	public function afterDelete($event)
	{
		if($this->autoDelete)
			$this->deleteRelated($this->relations);
		parent::afterDelete($event);
	}
}