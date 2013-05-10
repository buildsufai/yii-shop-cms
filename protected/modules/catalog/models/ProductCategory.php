<?php

/**
 * This is the model class for table "product_category".
 *
 * The followings are the available columns in table 'product_category':
 * @property integer $id
 * @property string $name
 * @property integer $position
 * @property string $alias
 * @property integer $media_id
 * @property integer $parent_id
 * @property boolean $active
 *
 * The followings are the available model relations:
 * @property Product[] $products
 * @property ProductCategory $parent
 * @property ProductCategory[] $productCategories
 * @property Media $media
 * @property PropertyGroup[] $propertyGroups
 */
class ProductCategory extends XActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductCategory the static model class
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
        return 'product_category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, alias', 'required'),
            array('position, media_id, parent_id, active', 'numerical', 'integerOnly'=>true),
            array('name, alias', 'length', 'max'=>100),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, position, alias, media_id, parent_id', 'safe', 'on'=>'search'),
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
            'products' => array(self::HAS_MANY, 'Product', 'category_id'),
            'itemCount' => array(self::STAT, 'Product', 'category_id'),
            'parent' => array(self::BELONGS_TO, 'ProductCategory', 'parent_id'),
            'childCount'=> array(self::STAT, 'ProductCategory', 'parent_id'),
            'subcategories' => array(self::HAS_MANY, 'ProductCategory', 'parent_id'),
            'media' => array(self::BELONGS_TO, 'Media', 'media_id'),
            'propertyGroups' => array(self::HAS_MANY, 'PropertyGroup', 'product_category_id', 'order'=>'propertyGroups.position ASC'),
        );
    }
    
    public function subcategories($limit)
    {
        return array_slice($this->subcategories, 0, $limit);
        
    }
    
    public $children;

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => Yii::t('catalog', 'Name'),
            'position' => Yii::t('backend', 'Position'),
            'alias' => Yii::t('backend', 'Alias'),
            'media_id' => Yii::t('backend', 'Media'),
            'parent_id' => Yii::t('backend', 'Parent category'),
        );
    }

    public function behaviors()
    {
        return array(
            'adjacency' => array(
                'class' => 'ext.behaviors.AdjacencyBehavior',
                'text' => 'name'
            ),
            'withRelated' => array(
              'class'=>'ext.behaviors.WithRelatedBehavior',
            ),
        );
    }
    
    public function scopes()
    {
        return array(
            'root'=>array(
                'condition'=>'parent_id IS NULL',
            ),
						'active'=>array(
								'condition'=>'active = 1',
						),
        );
    }
    
    public function beforeValidate()
    {
        if(parent::beforeValidate())
        {
            //Read media item from post
            if(isset($_POST['PropertyGroup']))
            {
                $propertyGroups = array();
                $postGroups =(is_array(@$_POST['PropertyGroup'])) ? $_POST['PropertyGroup'] : array();
                foreach($postGroups as $key => $atts)
                { 
                    $propertyGroup = new PropertyGroup();
                    if(substr($key, 0, 3) != 'new')
                        $propertyGroup->isNewRecord = false;
                    $propertyGroup->id = $key;
                    $propertyGroup->product_category_id = $this->id;
                    //$propertyGroup->markedDeleted = $atts['markedDeleted'];
                    $propertyGroup->attributes = $atts;
                    
                    $properties = array();
                    $postProperties =(is_array(@$_POST['Property'][$key])) ? $_POST['Property'][$key] : array();
                    foreach($postProperties as $pkey => $patts)
                    {
                        //yii::log(print_r($patts, true), 'error');
                        $property = new Property;
                        if(substr($pkey, 0, 3) != 'new')
                            $property->isNewRecord = false;
                        $property->id = $pkey;
                        $property->property_group_id = $propertyGroup->id;
                        $property->attributes = $patts;
                        
                        $properties[] = $property;
                    }
                    $propertyGroup->properties = $properties;
                    $propertyGroups[] = $propertyGroup;
                }
                $this->propertyGroups = $propertyGroups;
            }

            if($this->isNewRecord)
                $this->alias = CSlugging::slug($this->name);
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
        $criteria->compare('alias',$this->alias,true);
        $criteria->compare('media_id',$this->media_id);
        $criteria->compare('parent_id',$this->parent_id);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
    /**
     * Move the category to another position or parent
     */
    public function move($new_parent_id, $after_id)
    {
        //find this category his parent;
        $parent = $this->parent;
        //$new_parent =

        //find all children of this parent
        if($parent == null)
            $children = ProductCategory::model()->findAllByAttributes(array('parent_id'=> null));
        else
            $children = $parent->productCategories;

        //$new_parent =
        //$parent->productCategories
        if($this->parent_id != $new_parent_id)
        {
            //The category was placed into another category
        }

        if($parent == null)
        {
            //category was located in the root
        }
    }
    
    public function getManufacturers()
    {
        $manufacturers = Yii::app()->db->createCommand()
            ->select('manufacturer')
            ->from('product')
            ->group('manufacturer')
            ->where('category_id=:id', array(':id'=>$this->id))
            ->queryAll();
        
        $items = CHtml::listData($manufacturers, 'manufacturer', 'manufacturer');
        
        //print_r($items);
        
        return $items;
    }

    public function getUrl()
    {
        if(!empty($this->childCount) ) //see if there are subcategories
            return Yii::app()->controller->createUrl('/catalog/default/categories', array('alias'=>$this->alias));
        else
            return Yii::app()->controller->createUrl('/catalog/default/productline', array('alias'=>$this->alias));
    }

    public function getThumb()
    {
        if (!empty($this->media_id))
            return $this->media->getImageUrl('thumb');
        return false;
    }
    
}