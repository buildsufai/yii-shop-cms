<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php //TODO: check language echo Yii::app()->language; ?>" />

	<!-- blueprint CSS framework -->
	<link href="<?php echo Yii::app()->request->hostInfo . Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="icon" />
	<link href="<?php echo Yii::app()->request->hostInfo . Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="shortcut icon" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui.css" media="all" />
	

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

	
</head>

<body>

<div id="header">
	<h1>Admin</h1>
	<?php $this->widget('zii.widgets.CMenu',array(
                    'id'=>'navigation',
                    'encodeLabel'=>false,
                    'items'=>array(
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/graph.png" /><br />Dashboard',
                                'url'=>array('/admin'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/default/")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/pages.png" /><br />Pagina\'s',
                                'url'=>array('/admin/content'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/content/")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/home.png" /><br />Vestigingen',
                                'url'=>array('/admin/locations'),
                                'active'=>Yii::app()->controller->module->name == "locations"),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/catalog.png" /><br />Catalogus',
                                'url'=>array('/admin/bestellijst/product'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/bestellijst/product")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/users.png" /><br />'.Yii::t('backend', 'Klanten'),
                                'url'=>array('/admin/bestellijst/customer/'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/bestellijst/customer")!==false),
                    ),
            ));?>
    <div id="account">
    	<?php echo Yii::t('backend', 'Hello'); ?> <a href=""><?php echo Yii::app()->user->name; ?></a> | <a href="<?php echo $this->createUrl('/admin/default/logout'); ?>"><?php echo Yii::t('backend', 'Logout'); ?></a>
    </div>
</div>

<div id="wrapper">


	<?php /*$this->widget('zii.widgets.CBreadcrumbs', array(
		'links'=>$this->breadcrumbs,
	)); */ ?><!-- breadcrumbs -->

	<?php echo $content; ?>

</div><!-- wrapper -->
</body>
</html>