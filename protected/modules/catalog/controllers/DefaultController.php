<?php
/**
 * This controller renders all the content the right way.
 */
class DefaultController extends Controller
{
    //public $layout = "//layouts/shop";


    public function actionIndex()
    {
        $model = Content::model()->findByAttributes(array('alias' => 'producten'));

        if ($model == null)
            echo "null"; //$this->actionProductLine();
        else
            $this->render('webroot.themes.'.Yii::app()->theme->name.'.views.page.content', array('model' => $model,));

    }
	
	public function actionTest($category_id)
	{
	  $criteria=new CDbCriteria;

	  $joinq = '';
	  $selectq = 't.*, ';

	  $cfgroups = PropertyGroup::model()->with('properties')->together()->findAllByAttributes(array('product_category_id'=>$category_id));
	  
	  foreach($cfgroups as $cfgroup)
	  {
		$selectq .= "cf_$cfgroup->id.property_id AS cf_$cfgroup->id, ";
		$joinq .= "LEFT OUTER JOIN `product_has_property` `cf_$cfgroup->id` ON (`cf_$cfgroup->id`.`product_id`=`t`.`id` AND `cf_$cfgroup->id`.`property_group_id`=$cfgroup->id) ";
	  }
	  $cfsearch = array(144=>447,141=>435);
	  
	  $criteria->select = substr($selectq, 0, -2);
	  $criteria->join = $joinq;
	  $criteria->compare('manufacturer',"Samsung",true);
	  $criteria->compare('category_id',$category_id);
	  $criteria->compare('status','<>:'.Product::STATUS_OFFLINE, false, 'AND'); //not offline
	  foreach($cfsearch as $field => $value)
          $criteria->compare('cf_'.$field.'.property_id', $value, false, 'AND');
	  //foreach(FilterForm[search_properties][144]:447 as $field => $value)
	  //$criteria->compare('')
	  
	  $dataprovider = new CActiveDataProvider('Product', array('criteria'=>$criteria));
	  //var_dump($dataprovider->getData());
	  foreach($dataprovider->getData() as $row)
		var_dump($row->name);

	}

    public function actionProduct($category, $alias, $sku)
    {

        //$sku = $_GET['sku'];
        $model = Product::model()->with('category', 'reviews:approved')->findByAttributes(array('sku' => $sku));

				$this->pageTitle = (!empty($model->meta_title)) ? $model->meta_title : $model->title;
        $this->metaKeywords = $model->meta_keywords;
        $this->metaDescription = $model->meta_description;
				
        if ($model == null)
            throw new CHttpException(404, 'The product you requested does not excists');

				$review = new Review;
				$review->product_id = $model->id;
				
				if(isset($_POST['Review']))
				{
					$review->attributes = $_POST['Review'];
					if($review->save())
						Yii::app()->user->setFlash('success', 'Bedankt voor uw recentie');
				}
				
        $this->render('product', array(
            'model' => $model,
						'review' => $review,
        ));
    }
    
    public function actionCategories($alias)
    {
        $this->layout = '//layouts/slider';
        $model = ProductCategory::model()->with('subcategories')->findByAttributes(array('alias'=>$alias));
        
        $this->render('categories',array('model'=>$model));
    }

    public function actionProductLine($alias)
    {
        $this->layout = '//layouts/sidebar';
        $category = ProductCategory::model()->active()->findByAttributes(array('alias'=>$alias));
        
				if($category == null)
					throw new CHttpException(404, 'Product category not found');
				
        $model=new Product('search');

        $model->unsetAttributes();  // clear any default values
        $model->category_id = $category->id;
        
        $filter=new FilterForm;
        
        if(!isset($_POST['FilterForm']) && Yii::app()->session->contains('filterForm'.$category->id))
          $filterArr = Yii::app()->session->get('filterForm'.$category->id);
        elseif(isset($_POST['FilterForm']))
        {
          if($_POST['filterReset'] == 'true')
            Yii::app()->session->remove('filterForm'.$category->id);
          else
          {
            $filterArr = $_POST['FilterForm'];
            Yii::app()->session->add('filterForm'.$category->id,$_POST['FilterForm']);
          }
        }
          
        if(isset($filterArr))
        {
            $filter->attributes=$filterArr;
            //$filter->saveState();
            $model->name = $filterArr['name'];
            $model->manufacturer = $filterArr['manufacturer'];
			/*
            $props = array();
            foreach($filterArr['search_properties'] as $field => $value)
            {
                if(is_array($value))
                    $props = array_merge($props,$value);
                else
                    $props[] = $value;
            } */
            $model->search_properties = $filterArr['search_properties']; // [$field] = $props;
        } 

        $this->render('productlist',array('model'=>$model, 'category'=>$category, 'filter'=>$filter));
    }

}