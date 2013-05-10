<?php

class ProductSlider extends CWidget
{
    public $title;
    public $condition = '';
		public $limit = '10';
    public $htmlOptions = array();
    
    public $options = array(
        'fx'=>'scrollHorz',
        'speed',1000,
        'timeout'=>5000,
        'pager'=>'#pager',
        'next'=>'#slide_next',
        'prev'=>'#slide_prev',
    );

    public function init()
    {
        
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        if(is_dir($assets)){
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerScriptFile($baseUrl . '/jquery.cycle.all.js', CClientScript::POS_HEAD);
        }
        
        parent::init();

        $id=$this->getId();
        if (isset($this->htmlOptions['id']))
                $id = $this->htmlOptions['id'];
        else
                $this->htmlOptions['id']=$id;

        //echo CHtml::openTag($this->tag,$this->htmlOptions)."\n";

        
        $options=empty($this->options) ? '' : CJavaScript::encode($this->options);
        Yii::app()->getClientScript()->registerScript(__CLASS__.'#'.$id,"jQuery('#slideframe').cycle($options);");

    }
    
    public function run()
    {
			// create a criteria that selected random items
			$criteria = new CDbCriteria(array(
					'condition'=>$this->condition,
					'order'=>'RAND()',
					'limit'=>10,
			));
        if(isset($_GET['alias']))
            $models = Product::model()->sale()->bycategory($_GET['alias'])->findAll($criteria);
        else
            $models = Product::model()->sale()->findAll($criteria);
        $this->render('productSlider', array('models'=>$models, 'title'=>$this->title));
    }
}