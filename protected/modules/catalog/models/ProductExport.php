 <?php

/**
 * This is the model class for table "product_export".
 *
 * The followings are the available columns in table 'product_export':
 * @property integer $product_id
 * @property integer $beslist
 * @property integer $kieskeurig
 * @property integer $vergelijk
 *
 * The followings are the available model relations:
 * @property Product $product
 */
class ProductExport extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductExport the static model class
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
        return 'product_export';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_id', 'required'),
            array('product_id, beslist, kieskeurig, vergelijk', 'numerical', 'integerOnly'=>true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('product_id, beslist, kieskeurig, vergelijk', 'safe', 'on'=>'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'product_id' => 'Product',
            'beslist' => 'Beslist',
            'kieskeurig' => 'Kieskeurig',
            'vergelijk' => 'Vergelijk',
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

				$criteria->with = 'product';
				$criteria->together = true;
				
        $criteria->compare('product_id',$this->product_id);
        $criteria->compare('beslist',$this->beslist);
        $criteria->compare('kieskeurig',$this->kieskeurig);
        $criteria->compare('vergelijk',$this->vergelijk);
				//$criteria->compare('product.status', Product::STATUS_SALE);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
		
		//10012|digitale camera|
		//canon|ixus 70|roze|met gratis tas|1244B012AA|3409789645311|299.00|9.95|2.95|
		//http://www.uwwinkelopkieskeurig.nl?product=10012&ref=kieskeurig|http://www.uwwinkelopkieskeurig.nl/images/10012.jpg|1|2-4 dagen 
		public function toKieskeurigLine()
		{
			return array(
					'id'=>$this->product_id,
					'productgroep'=>$this->product->category->name,
					'merk'=>$this->product->manufacturer,
					'type'=>$this->product->name,
					'toevoeging-type'=>'leeg',
					'productbeschrijving'=> str_replace ("\r\n", '', strip_tags($this->product->description)),
					'partnumber'=>'leeg',
					'ean-code'=>$this->product->sku,
					'prijs'=>$this->product->getPrice(),
					'verzendkosten'=>$this->product->getShippingPrice(),
					'afhaalkosten'=>0.0,
					'deeplink'=>Yii::app()->request->hostInfo . $this->product->getUrl(),
					'imagelink'=>Yii::app()->request->hostInfo . $this->product->getThumb(),
					'voorraad'=>'ja',
					'levertijd'=>'1-2 dagen',
			);
		}
		public static function kieskeurigHeaders()
		{
			return array(
					'id',
					'productgroep',
					'merk',
					'type',
					'toevoeging-type',
					'productbeschrijving',
					'partnumber',
					'ean-code',
					'prijs',
					'verzendkosten',
					'afhaalkosten',
					'deeplink',
					'imagelink',
					'voorraad',
					'levertijd',
			);
		}

		public static function vergelijkHeaders()
		{
			return array(
					'Category',
					'SubCategory',
					'Brand',
					'ProductName',
					'Deeplink',
					'Price',
					'DeliveryPeriod',
					'DeliveryCosts',
					'OfferId',
					'ProductVendorPartNr',
					'ProductEAN',
					'ProductDescription',
					'DeeplinkPicture',
					'StockStatus',
					'ProductsInStock',
					'PromotionText'
			);
		}
		public function toVergelijkLine()
		{
			return array(
					'Category'=>$this->product->category->parent->name,
					'SubCategory'=>$this->product->category->name,
					'Brand'=>$this->product->manufacturer,
					'ProductName'=>$this->product->name,
					'Deeplink'=>Yii::app()->request->hostInfo . $this->product->getUrl(),
					'Price'=> str_replace('.', ',', $this->product->getPrice()),
					'DeliveryPeriod'=>'1-2 dagen',
					'DeliveryCosts'=>  str_replace('.', ',', $this->product->getShippingPrice()),
					'OfferId'=>$this->product_id,
					'ProductVendorPartNr'=>$this->product->sku,
					'ProductEAN'=>$this->product->sku,
					'ProductDescription'=>str_replace ("\r\n", '', strip_tags($this->product->description)),
					'DeeplinkPicture'=>Yii::app()->request->hostInfo . $this->product->getThumb(),
					'StockStatus'=>'Op voorraad',
					'ProductsInStock'=>'1-2 dagen',
					'PromotionText'=>str_replace ("\r\n", '', strip_tags($this->product->description)),
			);
		}

		public static function beslistHeaders()
		{
			return array(
					'Titel',
					'EAN/ISBN',
					'Merk',
					'Artikelfabrikantcode (SKU)',
					'Beschrijving',
					'Prijs',
					'Levertijd',
					'Deeplink',
					'Imagelocatie',
					'Categorie',
					'Portokosten',
					'Winkelproductcode (eigen)',
			);
		}
		
		public function toBeslistLine()
		{
			return array(
					'Titel'=>$this->product->name,
					'EAN/ISBN'=>$this->product->sku,
					'Merk'=>$this->product->manufacturer,
					'Artikelfabrikantcode (SKU)'=>$this->product->sku,
					'Beschrijving'=>str_replace ("\r\n", '', strip_tags($this->product->description)),
					'Prijs'=> str_replace('.', ',', $this->product->getPrice()),
					'Levertijd'=>'1-2 dagen',
					'Deeplink'=> Yii::app()->request->hostInfo . $this->product->getUrl(),
					'Imagelocatie'=>Yii::app()->request->hostInfo . $this->product->getThumb(),
					'Categorie'=>$this->product->category->parent->name,
					'Portokosten'=>str_replace('.', ',', $this->product->getShippingPrice()),
					'Winkelproductcode (eigen)'=>$this->product_id,
			);
		}
		
} 