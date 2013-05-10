<?php

/**
 * This is the model class for table "property_group".
 *
 * The followings are the available columns in table 'property_group':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $position
 * @property integer $type
 * @property integer $product_category_id
 *
 * The followings are the available model relations:
 * @property Property[] $properties
 * @property ProductCategory $productCategory
 */
class PropertyGroup extends XActiveRecord
{
    const TYPE_SELECT = 0;
    const TYPE_CHOICE = 1;
    const TYPE_MULTIPLE = 2;
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return PropertyGroup the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        
        public function getTypeOptions()
        {
            return array(
                self::TYPE_SELECT => Yii::t('backend', 'Selectie'),
                self::TYPE_CHOICE => Yii::t('backend', 'Keuze'),
                self::TYPE_MULTIPLE => Yii::t('backend', 'Meer keuze'),
            );
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'property_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, product_category_id', 'required'),
			array('position, type, product_category_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>100),
                        array('markedDeleted, description', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, position, type, product_category_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'productProperties' => array(self::HAS_MANY, 'ProductProperty', 'property_group_id'),
			'properties' => array(self::HAS_MANY, 'Property', 'property_group_id', 'index'=>'id'),
			'productCategory' => array(self::BELONGS_TO, 'ProductCategory', 'product_category_id'),
		);
	}
        
        /**
         * Return the id of the selected propertie for the given product
         * @param PropertyLinks[] $properties
         * @return int selected properties of given product 
         */
        public function getSelected($properties)
        {            
            foreach($properties as $property)
                if(in_array($property->property_id, array_keys($this->properties))) return $property->property_id;
                
            return null;
        }
        
        public function getValues($properties)
        {
            $result = array();
            foreach($properties as $property)
                $result[] = $property->property_id;
            return $result;
        }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Filter naam',
                        'description' => 'Beschrijving',
			'position' => 'Positie',
			'type' => 'Type filter',
			'product_category_id' => 'Product Category',
		);
	}
        
        /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->id = null;
            }
            return true;
        }
        else
            return false;
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('type',$this->type);
		$criteria->compare('product_category_id',$this->product_category_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}