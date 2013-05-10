<?php

/**
 * This is the model class for table "product_has_property".
 *
 * The followings are the available columns in table 'product_has_property':
 * @property integer $product_id
 * @property integer $property_group_id
 * @property integer $property_id
 *
 * @author Michael de Hart
 */
class ProductProperty extends XActiveRecord
{
    //put your code here
    public function getId()
        {
            return implode('-', $this->primaryKey);
        }
        
        /**
	 * Returns the static model of the specified AR class.
	 * @return ContentMedia the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'product_has_property';
	}
        
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('property_group_id', 'required'),
			array('property_id, property_group_id, product_id', 'numerical', 'integerOnly'=>true),
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
                    'property'=>array(self::BELONGS_TO,'Property','property_id'),
		);
	}
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
                    'product_id' => 'Product',
                    'property_group_id' => 'Property group',
                    'property_id' => 'Property',
            );
	}
}

?>
