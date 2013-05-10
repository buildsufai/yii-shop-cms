<?php

/**
 * This is the model class for table "order_detail".
 *
 * The followings are the available columns in table 'order_detail':
 * @property integer $id
 * @property string $sku
 * @property string $name
 * @property double $price
 * @property double $vat_percentage
 * @property double $shipping_costs
 * @property integer $qty
 * @property integer $product_id
 * @property integer $order_id
 *
 * The followings are the available model relations:
 * @property Order $order
 */
class OrderDetail extends XActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return OrderDetail the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
		
		/**
		 * Devide price by 1.21 where 21 is vat_percentage
		 * @return double price ex btw
		 */
		public function getNetPrice()
		{
			return $this->price / (($this->vat_percentage+100)/100);
		}
		
		public function getBtwGroupText()
		{
			return $this->vat_percentage."%";
			//$groups = Product::model()->getBTWGroupOptions();
			//return $groups[$this->btw_group];
		}

    function getId()
    {
        return $this->id;
        //return 'Product'.$this->id;
    }

    function getPrice()
    {
        return $this->price;
    }

    function getQuantity()
    {
        return $this->quantity;
    }
    function setQuantity($value)
    {
        $this->quantity = $value;
    }

    function getPriceText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->price, 'EUR');
    }

    public function getTotalText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->totalPrice, 'EUR');
    }

    public function getTotalPrice()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Make sure it doesnt matter if you enter the decimal seperator with a dot or a comma
     */
    protected function beforeValidate()
    {
            if(parent::beforeValidate())
            {
                    $this->price = str_replace(",", ".", $this->price);
                    return true;
            }
            else
                    return false;

    }

    /**
     * remove any temporairly id assign to the detail
     * @return boolean successfull
     */
    protected function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                $this->primaryKey = null; //Remove any temporary uniq id
            }
            return true;
        }
        else
            return false;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'order_detail';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sku, name, price', 'required'),
            array('quantity, order_id, product_id', 'numerical', 'integerOnly' => true),
            array('price, vat_percentage', 'numerical'),
            array('sku', 'length', 'max' => 45),
            array('name', 'length', 'max' => 100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, sku, name, price, quantity, order_id, product_id', 'safe', 'on' => 'search'),
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
            'product' => array(self::BELONGS_TO, 'Product', 'product_id'),
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'sku' => 'Artikel nr',
            'name' => 'Naam',
            'price' => 'Prijs',
            'quantity' => 'Aantal',
            'product_id' => 'Product',
            'order_id' => 'Bestelling',
        );
    }

}