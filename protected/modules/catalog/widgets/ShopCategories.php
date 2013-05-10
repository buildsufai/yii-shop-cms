<?php
//Yii::import('zii.widgets.CPortlet');

class ShopCategories extends CWidget
{
    public $title;

    public function run()
    {
        $categories = ProductCategory::model()->findAll();
        $new = Product::model()->count('t.status='.Product::STATUS_NEW);
        $this->render('shopCategories', array('categories'=>$categories, 'new'=>$new, 'title'=>$this->title));
    }
}