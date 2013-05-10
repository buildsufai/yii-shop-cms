 <?php

/**
 * This is the model class for table "review".
 *
 * The followings are the available columns in table 'review':
 * @property integer $id
 * @property string $author
 * @property string $create_date
 * @property string $description
 * @property integer $rate
 * @property string $ip
 * @property integer $approved
 * @property integer $product_id
 * @property integer $customer_id
 *
 * The followings are the available model relations:
 * @property Product $product
 * @property Customer $customer
 */
class Review extends XActiveRecord
{

		public function afterConstruct()
		{
			if(!Yii::app()->customer->isGuest)
			{
				$this->author = Yii::app()->customer->customer->name;
				$this->customer_id = Yii::app()->customer->id;
			}
			$this->create_date = date('Y-m-d H:m:s');
			$this->ip = $_SERVER["REMOTE_ADDR"];
		}
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Review the static model class
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
        return 'review';
    }
		
		public function scopes()
		{
			return array(
					'approved'=>array('condition'=>'approved = 1')
			);
		}
		
		public function getRates()
		{
			return array(
					1=>1,
					2=>2,
					3=>3,
					4=>4,
					5=>5,
			);
		}

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('author, create_date, description, rate, ip, product_id', 'required'),
            array('rate, approved, product_id, customer_id', 'numerical', 'integerOnly'=>true),
            array('author', 'length', 'max'=>100),
						array('description', 'length', 'min'=>25),
            array('ip', 'length', 'max'=>45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, author, create_date, description, rate, ip, approved, product_id, customer_id', 'safe', 'on'=>'search'),
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
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'author' => 'Auteur',
            'create_date' => 'Datum',
            'description' => 'Beschrijving',
            'rate' => 'Waardering',
            'ip' => 'Ip',
            'approved' => 'Goedgekeurd',
            'product_id' => 'Product',
            'customer_id' => 'Klant',
						'product.name' => 'Product',
        );
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
        $criteria->compare('author',$this->author,true);
        $criteria->compare('create_date',$this->create_date,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('rate',$this->rate);
        $criteria->compare('ip',$this->ip,true);
        $criteria->compare('approved',$this->approved);
        $criteria->compare('product_id',$this->product_id);
        $criteria->compare('customer_id',$this->customer_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'id DESC',
              )
        ));
    }
} 