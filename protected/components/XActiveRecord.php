<?php
/**
 * XActiveRecord class file.
 *
 * @author Michael de Hart <derinus@gmail.com>
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2010-2012 Cloud Engineering
 */
class XActiveRecord extends CActiveRecord
{
        private $_markeddeleted=false;        // whether this instance is marked for deletion or not
    
        /**
	 * Returns if the current record is marked for deletion
	 * @return boolean whether the record should be deleted when calling {@link save}.
	 * Defaults to false
	 */
	public function getMarkedDeleted()
	{
		return $this->_markeddeleted;
	}

	/**
	 * Marks the record for deletion on save().
	 * @param boolean $value whether the record should be deleted when calling {@link save}.
	 * @see getIsMarkedDeleted
	 */
	public function setMarkedDeleted($value)
	{
		$this->_markeddeleted=$value;
	}
        
        /**
         * Stil same aas parent implementation
         * @param type $runValidation
         * @param type $attributes
         * @return type 
         */
        public function save($runValidation=true,$attributes=null)
	{
		if(!$runValidation || $this->validate($attributes))
			return $this->getIsNewRecord() ? $this->insert($attributes) : $this->update($attributes);
		else
			return false;
	}
}
?>
