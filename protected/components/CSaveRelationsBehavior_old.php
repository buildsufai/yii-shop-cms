<?php

/**
 * CMasterDetailBehavior class file.
 *
 * @author Michael de Hart <derinus@gmail.com>
 * @link http://www.cloudengineering.nl
 * @version 1.0
 */
class CSaveRelationsBehavior extends CActiveRecordBehavior
{

    public $relations = array();   // An array of the relations that needs updating key = relation name, value = array of params
    public $hasError = false;   // Where there errors?
    private $transaction;    // The transaction object for creating transactions

    /**
     * Set the relational records that need to be saved upon save.
     * @param string relation - name of the relation
     * @throws CDbException if relation not found
     */

    private function initSaveRelation($relation, $message=null)
    {
        $model = $this->owner;
        $this->validateRelation($relation);

        if (!array_key_exists($relation, $this->relations)) // Is the relation already initialized?
        {
            Yii::trace("Init {$relation} relation", 'application.components.CSaveRelatedBehavior');
            $this->relations[$relation] = $model->{$relation};
            if (!is_null($message))
                $this->setSaveRelationMessage($relation, $message);
        }
    }

    /**
     * Check if the model has the relation
     * @param string $name
     * @throws CDbException
     */
    private function validateRelation($name)
    {
        $md = $this->owner->getMetaData();
        if (!isset($md->relations[$name])) // Does the relation excists in the model?
            throw new CDbException(Yii::t('yii', '{class} does not have relation "{name}".', array('{class}' => get_class($this), '{name}' => $name)));
    }

    /**
     * Get the relation from temporary array and dont reload relations from database
     * return an item array with active record if record are modified
     * @param string $name (of relation)
     * @throws CDbException
     */
    public function getRelation($name)
    {
        $this->initSaveRelation($name);
        return $this->relations[$name];
    }

    /**
     * Batch update all the HAS_MANY relations for $name (tabular input)
     * @param string $name (of relation)
     * @param array $data Shoud be assosiated array where the index is the primary key and the value an array of attributes
     */
    public function setHasManyRelation($name, $data=null)
    {
        $this->initSaveRelation($name);

        if (!is_null($data))
        {
            foreach ($this->relations[$name] as $i => $relatedRecord)
            {
                if (isset($data[$i]))
                    $relatedRecord->attributes = $data[$i];
            }
        }
    }

    public function setManyManyRelation($name, $data=null)
    {
        $this->initSaveRelation($name);

        if (!is_null($data) && is_array($data))
        {
            $this->relations['categories'] = $data;
        }
    }

    public function addHasManyRelation($name, $record, $index)
    {
        $this->initSaveRelation($name);
        $this->relations[$name][$index] = $record;
    }

    /**
     * Set the related record marked for deletion
     * The record wont be delete from the database till a save is made.
     * @param string $name (of relation)
     * @param mixxed $index (key of item)
     */
    public function deleteHasManyRelation($name, $index)
    {
        $this->initSaveRelation($name);
        if ($this->relations[$name][$index] != null) //If excists
            $this->relations[$name][$index]->markDeleted = true; // mark for deletion

    }

    /**
     * Set the senario param of the relation. Save will only be executed in this senario
     * @param string $relation
     * @param string $scenario
     */
    private function setRelationScenario($relation, $scenario)
    {
        $this->initSaveRelation($relation);
        $this->relations[$relation] = CMap::mergeArray($this->relations[$relation], array('scenario' => $scenario));
    }

    /**
     * Set the message in the errorSummery of this relation could not be saved
     * @param string $relation
     * @param string $message
     */
    private function setSaveRelationMessage($relation, $message)
    {
        $this->initSaveRelation($relation);
        $this->relations[$relation] = CMap::mergeArray($this->relations[$relation], array('message' => $message));
    }

    public function beforeValidate($event)
    {
        $model = $this->owner;

        foreach ($this->relations as $relation => $items) //each relations
        {
            $validRelation = true;

            $activeRelation = $model->getActiveRelation($relation);
            Yii::log(get_class($activeRelation), 'warning');
            if(get_class($activeRelation) == "CHasManyRelation") // IF HAS_MANY
            {
                foreach ($items as $relatedRecord) //each related record
                {
                    //$validRelation = $validRelation && $relatedRecord->validate();
                    Yii::log("validating: " . print_r($relatedRecord->errors, true), 'warning');
                }
            }
            if (!$validRelation) //Add error when not valid
                $model->addError($relation, "An error occured during the validation of {$relation}");

        }
    }

    /**
     * Start a transaction scope before save starts
     * @param Event $event
     */
    public function beforeSave($event)
    {
        parent::beforeSave($event);
        $model = $this->owner;

        if (!$model->dbConnection->currentTransaction)
        { // Not already started??
            //Yii::log("beforeSave start transaction",'warning');
            $this->transaction = $model->dbConnection->beginTransaction();
        }
    }

    /**
     * Save the HAS_MANY relation object with transaction scope on beforesave
     * @param Event $event
     */
    public function afterSave($event)
    {
        parent::afterSave($event);
        $model = $this->owner;
        try
        {
            foreach ($this->relations as $relation => $items) //each relation
            {

                $activeRelation = $model->getActiveRelation($relation);
                if(get_class($activeRelation) == "CHasManyRelation")
                {
                    $relationForeignKey = $activeRelation->foreignKey;

                    foreach ($items as $relatedRecord) //each record
                    {
                        if ($relatedRecord->markDeleted && !$relatedRecord->isNewRecord)
                            $relatedRecord->delete(); // delete the record that are not new and marked for deletion
                        else // save the rest
                        {
                            //TODO: make this work for multiply foreignKeys?
                            $relatedRecord->{$relationForeignKey} = $model->primaryKey;
                            $relatedRecord->save();
                        }
                    }
                }
                /*
                elseif(get_class($activeRelation) == "CManyManyRelation")
                {
                    $this->saveManyMany($relation, $items); // AND HERE IT SAVES THE MANYMANY RELATIONS
                }*/
            }
            $this->writeManyManyTables(); //TODO: make pretty or wait for yii 1.2 :P
            $this->transaction->commit();
        } catch (Exception $e)
        {
            Yii::trace("An error occured during the save operation for related records : " . $e->getMessage(), 'application.components.CSaveRelatedBehavior');
            $this->hasError = true;
            if (isset($relation))
                $this->owner->addError($relation, "An error occured during the save of {$relation}");
            if ($this->transaction)
                $this->transaction->rollBack();
        }
    }

    private function writeManyManyTables()
    {
        foreach ($this->owner->relations() as $key => $relation)
        {
            if (isset($this->owner->$key) && $relation['0'] == CActiveRecord::MANY_MANY)
            {
                Yii::app()->db->createCommand($this->makeManyManyDeleteCommand($relation[2],$this->owner->{$this->owner->tableSchema->primaryKey}))->execute();

                foreach ($this->owner->$key as $foreignkey)
                    Yii::app()->db->createCommand($this->makeManyManyInsertCommand($relation[2], $foreignkey))->execute();
            }
        }
    }

    // It is important to use insert IGNORE so SQL doesn't throw an foreign key
    // integrity violation
    private function makeManyManyInsertCommand($model, $rel)
    {
        return sprintf("insert ignore into %s values ('%s', '%s')", $model, $this->owner->{$this->owner->tableSchema->primaryKey}, $rel);
    }

    private function makeManyManyDeleteCommand($model, $rel)
    {
        preg_match('/\((.*),/', $model, $matches);
        $relationNameForDeletion = substr($matches[0], 1, strlen($matches[0]) - 2);
        return sprintf("delete ignore from %s where %s = '%s'", $this->getManyManyTable($model), $relationNameForDeletion, $rel);
    }

    private function getManyManyTable($model)
    {
        if (($ps = strpos($model, '(')) !== FALSE)
            return substr($model, 0, $ps);
        else
            return $model;
    }

    /**
     * Start a transaction scope before delete
     * @param Event $event
     */
    public function beforeDelete($event)
    {
        $model = $this->owner;
        if (!$model->dbConnection->currentTransaction)
        {
            //Yii::trace("beforeDelete start transaction",'application.components.CSaveRelatedBehavior');
            $this->transaction = $model->dbConnection->beginTransaction();
            try
            {
                foreach ($model->relations() as $relation => $params)
                {
                    $activeRelation = $model->getActiveRelation($relation);
                    $relationClassName = $activeRelation->className;
                    $relationForeignKey = $activeRelation->foreignKey;
                    $relatedRecord = new $relationClassName;
                    $criteria = new CDbCriteria;
                    $criteria->addColumnCondition(array($relationForeignKey => $model->primaryKey));
                    $relatedRecord->deleteAll($criteria);
                }
            } catch (Exception $e)
            {
                //Yii::trace("An error occured during the delete operation for related records : ".$e->getMessage(),'application.components.CSaveRelatedBehavior');
                $this->hasError = true;
                if (isset($relation))
                    $model->addError($relation, "An error occured during the delete operation of {$relation}");
                if ($this->transaction)
                    $this->transaction->rollBack();
            }
        }
    }

    /*
      public function afterDelete($event)
      {
      if($this->deleteRelatedRecords)
      {
      $model = $this->owner;
      try{
      foreach($model->relations() as $relation=>$params) {
      $activeRelation = $model->getActiveRelation($relation);
      if(is_object($activeRelation) && ($activeRelation instanceOf CManyManyRelation || $activeRelation instanceOf CHasManyRelation || $activeRelation instanceOf CHasOneRelation)) {
      Yii::trace("deleting {$relation} related records.",'application.components.CSaveRelatedBehavior');
      $relationClassName = $activeRelation->className;
      $relationForeignKey = $activeRelation->foreignKey;
      if($activeRelation instanceOf CManyManyRelation) {
      // ManyMany relation : delete related records from the many to many relation table
      $schema = $model->getCommandBuilder()->getSchema();
      preg_match('/^\s*(.*?)\((.*)\)\s*$/',$relationForeignKey,$matches);
      $joinTable=$schema->getTable($matches[1]);
      $fks=preg_split('/[\s,]+/',$matches[2],-1,PREG_SPLIT_NO_EMPTY);
      $baseParams = array();
      $baseCriteriaCondition = array();
      reset($fks);
      foreach($fks as $i=>$fk) {
      if(isset($joinTable->foreignKeys[$fk])) {
      list($tableName,$pk)=$joinTable->foreignKeys[$fk];
      if($schema->compareTableNames($model->tableSchema->rawName,$tableName)) {
      $baseCriteriaCondition[$fk] = $baseParams[':'.$fk] = $model->{$pk};
      }
      }
      }
      // Delete records
      $criteria = new CDbCriteria;
      $criteria->addColumnCondition($baseCriteriaCondition);
      $model->getCommandBuilder()->createDeleteCommand($joinTable->name,$criteria)->execute();
      }
      else {
      // HasMany & HasOne relation : delete related records
      $relatedRecord = new $relationClassName;
      $criteria = new CDbCriteria;
      $criteria->addColumnCondition(array($relationForeignKey=>$model->primaryKey));
      $relatedRecord->deleteAll($criteria);
      }
      }
      }
      unset($relation);
      if($this->transactional && $this->transaction) $this->transaction->commit();
      }
      catch(Exception $e)
      {
      Yii::trace("An error occured during the delete operation for related records : ".$e->getMessage(),'application.components.CSaveRelatedBehavior');
      $this->hasError = true;
      if(isset($relation)) $model->addError($relation,"An error occured during the delete operation of {$relation}");
      if($this->transactional && $this->transaction) $this->transaction->rollBack();
      }
      }
      }
     */
}