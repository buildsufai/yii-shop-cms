<?php

/**
 * This is the model class for table "shipping_cost".
 *
 * The followings are the available columns in table 'shipping_cost':
 * @property double $weight
 * @property double $price
 */
class ShippingCost extends XActiveRecord
{
    private $id = 't';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ShippingCost the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function getId()
        {
            if (!$this->isNewRecord)
                return $this->weight;
            else
                return $this->id;
        }
        public function setId($value)
        {
            $this->id = $value;
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shipping_cost';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('weight, price', 'required'),
			array('weight, price', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('weight, price', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'weight' => 'Weight',
			'price' => 'Price',
		);
	}
}