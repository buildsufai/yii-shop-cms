<?php

/**
 * This is the model class for table "product".
 *
 * The followings are the available columns in table 'product':
 * @property integer $id
 * @property string $sku
 * @property string $name
 * @property string $alias
 * @property string $manufacturer
 * @property integer $status
 * @property string $weight
 * @property string $description
 * @property string $description2
 * @property double $price
 * @property double $sale_price
 * @property double $stock_price
 * @property integer $btw_group
 * @property string $create_date
 * @property string $update_date
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 * @property integer $category_id
 * @property integer $is_bargain
 *
 * The followings are the available model relations:
 * @property ProductCategory $category
 * @property Media[] $mediaItems
 * @property ProductMedia[] $mediaLinks
 * @property Property[] $properties
 * @property RelatedProduct[] $relatedProducts
 * @property Review[] $reviews
 */
Yii::import('ext.behaviors.WithRelatedBehavior');

class Product extends XActiveRecord implements IECartPosition
{
    const SHIPPING_SHIP = 1;
    const SHIPPING_DELIVER = 2;
    
    const STATUS_ONLINE = 1;
    const STATUS_OFFLINE = 2;
    const STATUS_SALE = 3;
    const STATUS_SOLD_OUT = 4;
    
    const BTW_HOOG = 1;
    const BTW_LAAG = 2;
    const BTW_GEEN = 3;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Product the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getId(){
        return 'Product'.$this->id;
    }

    public function getPrice(){
        if(!empty($this->sale_price))
            return $this->sale_price;
        else
            return $this->price;
    }
		
		public function getStrokedPriceText(){
			if(!empty($this->sale_price))
            return '<span style="color:red;text-decoration:line-through;">'.$this->priceText.'</span><br>'.$this->salePriceText;
        else
            return $this->priceText;
		}
		
		public function getPriceIncVat(){
			return $this->price;
		}
		
		public function getPriceExVat(){
			return $this->removeVat($this->price);
		}
		
		public function getSalePriceExVat(){
			return $this->removeVat($this->sale_price);
		}
		
		public function getStockPriceExVat(){
			return $this->removeVat($this->stock_price);
		}
    
    public function getInStock()
    {
        return $this->status == self::STATUS_ONLINE || $this->status == self::STATUS_SALE;
    }
		
		public function getImage($type)
    {
        foreach ($this->mediaLinks as $media)
        {
            if ($media->type == $type)
                return $media;
        }
        return false;
    }
    
    public function getImages($type)
    {
        $images = array();
        foreach($this->mediaLinks as $media)
        {
            if ($media->type == $type)
                $images[] = $media;
        }
        return $images;
    }
    
    public function getThumb($format = 'thumb')
    {
        foreach ($this->mediaLinks as $media)
        {
            if ($media->type == ProductMedia::MEDIA_HEADER_IMAGE)
                return $media->media->getImageUrl($format);
        }
        return false;
    }
    
    public function getIsBuyable()
    {
        return true; //$this->price_type == self::PRICE_NONE;
    }
    
    public function getStatusText()
    {
        $productStatus = $this->productStatus;
        return isset($productStatus[$this->status]) ? $productStatus[$this->status] : "unknown type ({$this->status})";
    }
    
    public function getProductStatus()
    {
        return array(
            self::STATUS_ONLINE => 'Op voorraad',
            self::STATUS_SOLD_OUT => 'Uitverkocht',
            self::STATUS_OFFLINE => 'Offline',
            self::STATUS_SALE => 'Aanbieding',
        );
    }
    
    public function getBTWGroupOptions()
    {
        $amounts = array(
            self::BTW_HOOG => '21% BTW',
            self::BTW_LAAG => '6% BTW',
            self::BTW_GEEN => '0% BTW',
        );
				if(date('Ymd') < '20121001')
					$amounts[self::BTW_HOOG] = '19% BTW';
				return $amounts;
    }
		
		/**
		 * return the amount of btw as a percentage of 1
		 * @param int $vat_group one of the BTW_ constants of this class
		 * @return real the vat is percentage of 1
		 */
		public function getVatPercentage($vat_group=null)
		{
			$amounts = array(
					self::BTW_HOOG => 0.21,
					self::BTW_LAAG => 0.06,
					self::BTW_GEEN => 0,
			);
			if(date('Ymd') < '20121001')
				$amounts[self::BTW_HOOG] = 0.19;
			
			if($vat_group == 'all')
				return $amounts;
			
			$group = ($vat_group != null) ? $vat_group : $this->btw_group;
			return $amounts[$group];
		}
		
		private function removeVAT($price)
		{
			return round($price/(1+$this->getVatPercentage()), 2);

		}
    
    public function getStockPriceText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->stock_price, 'EUR');
    }
    
    public function getPriceText()
    {
        //if($this->price_type == self::PRICE_NONE)
            return Yii::app()->numberFormatter->formatCurrency($this->price, 'EUR');
        //elseif($this->price_type < 100)
        //    return $this->priceTypeText;
        //else
        //    return Yii::app()->numberFormatter->formatCurrency($this->price, 'EUR');
    }
    
    public function getCostText()
    {
        $price = (empty($this->sale_price)) ? $this->price : $this->sale_price;
        return Yii::app()->numberFormatter->formatCurrency($price, 'EUR');
    }
    
    public function getSalePriceText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->sale_price, 'EUR');
    }
    
		//TODO: load shipping costs ones and cache into table
		public function getShippingPrice()
		{
			$shippingcost = 0;
        $costs = ShippingCost::model()->findAll();
        foreach($costs as $cost)
        {
            if ($this->weight >= $cost->weight && $shippingcost < $cost->price) //if weight is bigger then rule and cost is smaller then rule costrule = cost
                $shippingcost = $cost->price;
        }
				return $shippingcost;
		}
    public function getShippingCosts()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->getShippingPrice(), 'EUR');
    }
    
    public function getCreateDateText()
    {
        if($this->isNewRecord)
            return "-";
        else
            return Yii::app()->dateFormatter->formatDateTime($this->create_date, 'long', null);
    }

    public function getUpdateDateText()
    {
        if($this->isNewRecord)
            return "-";
        else
            return Yii::app()->dateFormatter->formatDateTime($this->update_date, 'long');
    }
    
    public function getUrl()
    {
        return Yii::app()->controller->createUrl('/catalog/default/product', array('category'=>$this->category->alias, 'alias'=>$this->alias, 'sku'=>$this->sku));
    }
    
    public function getShortDescription($limit = 100)
    {
        $varlength = strlen($this->description);
        if($limit < $varlength)
        {
            $string = str_replace('&nbsp;', ' ', $this->description);
            $string = trim(strip_tags($string,"<br><p>"));
            //return $string;
            return substr($string, 0, $limit) . '...';
        }
        return strip_tags(str_replace('<br>', ' ', $this->description));
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'product';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('sku, name, alias, price, create_date, category_id, is_bargain', 'required'),
            array('status, btw_group, category_id, votes_like, votes_dont_like, is_bargain', 'numerical', 'integerOnly'=>true),
            array('price, sale_price, stock_price', 'numerical'),
            array('sku, manufacturer, weight', 'length', 'max'=>45),
            array('name, alias', 'length', 'max'=>150),
            array('meta_title, meta_keywords', 'length', 'max'=>100),
            array('meta_description', 'length', 'max'=>255),
            array('description, description2, update_date', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('search_properties, id, sku, name, alias, manufacturer, status, weight, description, description2, price, sale_price, stock_price, btw_group, create_date, update_date, meta_title, meta_keywords, meta_description, category_id, votes_like, votes_dont_like, is_bargain', 'safe', 'on'=>'search'),
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
            'category' => array(self::BELONGS_TO, 'ProductCategory', 'category_id'),
            'mediaLinks' => array(self::HAS_MANY, 'ProductMedia', 'product_id'),
            'mediaItems'=>array(self::HAS_MANY,'Media','media_id','through'=>'mediaLinks'),
            'properties' => array(self::HAS_MANY, 'Property','property_id','through'=>'propertyLinks'),
            'propertyLinks' => array(self::HAS_MANY, 'ProductProperty', 'product_id'),
            'relatedProducts' => array(self::MANY_MANY, 'Product', 'related_product(product_id, product_id1)'),
            'reviews' => array(self::HAS_MANY, 'Review', 'product_id'),
            'pixmania' => array(self::BELONGS_TO, 'Pixmania', 'sku'),
        );
    }
    
    public function stockChange()
    {
        $criteria=new CDbCriteria;
        $criteria->with = array('pixmania');
        $criteria->condition = "t.status = ".self::STATUS_ONLINE." AND pixmania.availability != 'in stock'";
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    public function noPixmania()
    {
        $criteria=new CDbCriteria;
        $criteria->with = array('pixmania');
        $criteria->compare('pixmania.code',null);
        $criteria->condition = "pixmania.code IS NULL";
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
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
            'alias' => 'Alias',
            'manufacturer' => 'Fabrikant',
            'status' => 'Status',
            'weight' => 'Gewicht',
            'description' => 'Beschrijving',
            'description2' => 'Technische beschrijving',
            'price' => 'Prijs',
            'sale_price' => 'Uitverkoop Prijs',
            'stock_price' => 'PIxmania Prijs',
            'btw_group' => 'BTW Groep',
            'create_date' => 'Toevoegdatum',
            'update_date' => 'Bewerkdatum',
            'meta_title' => 'Meta Titel',
            'meta_keywords' => 'Meta Keywords',
            'meta_description' => 'Meta Beschrijving',
            'category_id' => 'Categorie',
            'votes_like' => 'Votes Like',
            'votes_dont_like' => 'Votes Dont Like',
            'is_bargain' => 'Kassakoopje?',
        );
    }
    
    public function behaviors()
    {
        return array(
            'withRelated' => array(
              'class'=>'ext.behaviors.WithRelatedBehavior',
            ),
        );
    }
    
    protected function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            //Read media item from post
            if(isset($_POST['ProductMedia']))
            {
                $mediaItems = array();
                $media =(is_array(@$_POST['ProductMedia'])) ? $_POST['ProductMedia'] : array();
                foreach($media as $key => $atts)
                {
                    $mediaLink = new ProductMedia();
                    $mediaLink->markedDeleted = $atts['markedDeleted'];
                    if(strpos($key,'-'))
                        $mediaLink->isNewRecord = false;
                    $mediaLink->attributes = $atts;
                    $mediaLink->product_id = $this->id;
                    $mediaItems[] = $mediaLink;
                }
                $this->mediaLinks = $mediaItems;
            }

            $this->relatedProducts = (is_array(@$_POST['RelatedProduct'])) ? Product::model()->findAllByPk($_POST['RelatedProduct']) : array();

            if(isset($_POST['Properties']))
            {
                $propertyItems = array();
                $properties = (is_array(@$_POST['Properties'])) ? $_POST['Properties'] : array();
                foreach($properties as $property_group_id => $property_id)
                {
                    $propertyLink = null;
                    
                    if(is_array($property_id)) // checkboxes
                    {
                        $propertyLinks = array();
												
                        if($this->id != null) //mark all checkboxes deleted
                        {
                            $allGroupProps = ProductProperty::model()->findAllByAttributes(array('product_id'=>$this->id, 'property_group_id'=>$property_group_id));
                            foreach($allGroupProps as $link)
                            {
                                $link->markedDeleted = true;
                                $propertyLinks[$link->property_id] = $link;
                            }
                        }
												
                        foreach($property_id as $prid) // loop throw ids set markdeleted to false en change values
                        {
													if(isset($propertyLinks[$prid]))
														$propertyLink = $propertyLinks[$prid];
													else
														$propertyLink = new ProductProperty();

													$propertyLink->property_group_id = $property_group_id;
													$propertyLink->property_id = $prid;
													$propertyLink->markedDeleted = false;

													$propertyLinks[$prid] = $propertyLink;
												
                        }
                        $propertyItems = array_merge($propertyItems, $propertyLinks);
                    }
										
                    elseif(empty($property_id)) //dropdownbox value
                    {
                        if(!$this->isNewRecord)
                            $propertyLink = ProductProperty::model()->findByAttributes(array('product_id'=>$this->id,'property_group_id'=>$property_group_id));
                        if($propertyLink != null)
                        {
                            $propertyLink->markedDeleted = true;
                            $propertyItems[] = $propertyLink;
                        }
                    }
                    else //dropdownbox set value
                    {
                        if(!$this->isNewRecord)
                            $propertyLink = ProductProperty::model()->findByPk(array('property_id'=>$property_id,'product_id'=>$this->id));
                        if($propertyLink == null)
                            $propertyLink = new ProductProperty();
                        
                        $propertyLink->property_group_id = $property_group_id;
                        $propertyLink->property_id = $property_id;
                        $propertyItems[] = $propertyLink;
                    }
                }
                $this->propertyLinks = $propertyItems;
            }
            if($this->isNewRecord)
            {
                $this->alias = CSlugging::slug($this->name);
                $this->create_date = date('Y-m-d H:m:s');
            }
            $this->update_date = date('Y-m-d H:m:s');
            return true;
        }
        else
            return false;
    }
    
    
    public function scopes()
    {
        return array(
            'bargain'=>array(
                'condition'=>'is_bargain=1',
            ),
            'sale'=>array(
                'condition'=>'sale_price!=\'\'',
            ),
            'recently'=>array(
                'order'=>'create_date DESC',
                'limit'=>10,
            ),
        );
    }
    
    public function bycategory($alias)
    {
        $category = ProductCategory::model()->findByAttributes(array('alias'=>$alias));
        
        if($category == null)
            return $this;
        
        $ids = $category->getAllIds();
        if(empty($ids))
            return $this;
        $this->getDbCriteria()->mergeWith(array(
            'condition'=>'category_id IN ('. implode(', ', $ids) . ')',
        ));
        return $this;
    }
    
	public function filter()
	{
	  $criteria=new CDbCriteria;

	  $joinq = '';
	  $selectq = 't.*, ';

	  $cfgroups = PropertyGroup::model()->with('properties')->together()->findAllByAttributes(array('product_category_id'=>$this->category_id));
	  
	  foreach($cfgroups as $cfgroup)
	  {
		$selectq .= "cf_$cfgroup->id.property_id AS cf_$cfgroup->id, ";
		$joinq .= "LEFT OUTER JOIN `product_has_property` `cf_$cfgroup->id` ON (`cf_$cfgroup->id`.`product_id`=`t`.`id` AND `cf_$cfgroup->id`.`property_group_id`=$cfgroup->id) ";
	  }
	  $cfsearch = array(144=>447,141=>435);
	  
	  $criteria->select = substr($selectq, 0, -2);
	  $criteria->join = $joinq;
	  $criteria->compare('manufacturer',$this->manufacturer,true);
	  $criteria->compare('category_id',$this->category_id);
	  $criteria->compare('status','<>:'.Product::STATUS_OFFLINE, false, 'AND'); //not offline
	  foreach($this->search_properties as $field => $value)
          $criteria->compare('cf_'.$field.'.property_id', $value, false, 'AND');
	  //foreach(FilterForm[search_properties][144]:447 as $field => $value)
	  //$criteria->compare('')
	  
	  return new CActiveDataProvider('Product', array('criteria'=>$criteria));

	}
	
    public function _filter()
    {
	  $customFields = ProductProperty::model()->with('properyGroup')->findAllByAttributes(array('project_category_id'=>$this->category_id));
	  
        $criteria=new CDbCriteria;
        
        $together = (!empty($this->search_properties)) ? true : false;
		$criteria->with = array(
			'propertyLinks'=>array(
				'together'=>$together
			),
		);
		$criteria->distinct = true;
		//$criteria->group 
        
        //$criteria->compare('status','<>:2', false, 'AND');
        $criteria->compare('name',$this->name,true);
        
        foreach($this->search_properties as $property_id)
            $criteria->compare('propertyLinks.property_id', $property_id, false, 'OR');
        $criteria->compare('manufacturer',$this->manufacturer,true);
        $criteria->compare('category_id',$this->category_id);
        //TODO: compare price
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
		
		/**
		 * return the average rating of all reviews
		 * return 0 if no reviews 
		 */
		public function getRating()
		{
			$rating = 0;
			if(empty($this->reviews))
				return $rating;
			foreach($this->reviews as $review)
				$rating += $review->rate;
			return round($rating / count($this->reviews), 1)*2;
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
        
        //$criteria->together = true; //This will make the admin grid mad
        $criteria->with = array( 'propertyLinks' );
        
        $criteria->compare('propertyLinks.property_id', $this->search_properties, true );

				$criteria->compare('sku',$this->sku,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('manufacturer',$this->manufacturer,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('weight',$this->weight,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('description2',$this->description2,true);
        $criteria->compare('price',$this->price);
        $criteria->compare('sale_price',$this->sale_price);
        $criteria->compare('stock_price',$this->stock_price);
        $criteria->compare('meta_title',$this->meta_title,true);
        $criteria->compare('meta_keywords',$this->meta_keywords,true);
        $criteria->compare('meta_description',$this->meta_description,true);
        $criteria->compare('category_id',$this->category_id);
        $criteria->compare('is_bargain',$this->is_bargain);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    public $search_properties =array();
    public $search_minprice;
    public $search_maxprice;
    
} 