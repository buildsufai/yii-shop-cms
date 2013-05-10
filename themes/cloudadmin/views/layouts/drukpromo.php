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
        
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/general.js"></script>
</head>

<body>

<div id="header">
	<h1>Admin</h1>
	<?php $this->widget('zii.widgets.CMenu',array(
                    'id'=>'navigation',
                    'encodeLabel'=>false,
                    'items'=>array(
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/graph.png" /><br />'.Yii::t('backend', 'Dashboard'),
                                'url'=>array('/admin'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/default/")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/pages.png" /><br />'.Yii::t('backend', 'Pages'),
                                'url'=>array('/admin/content'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/content/")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/catalog.png" /><br />Catalogus',
                                'url'=>array('/admin/drukcatalog'),
                                'active'=>Yii::app()->controller->module->name == "drukcatalog"),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/pages.png" /><br />Orders',
                                'url'=>array('/admin/sales'),
                                'active'=>Yii::app()->controller->module->name == "sales"),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/folder.png" /><br />Bestanden',
                                'url'=>array('/admin/file'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/file/")!==false),
                            array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/users.png" /><br />Gebruikers',
                                'url'=>array('/admin/location'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/location/")!==false),
                            /*array(
                                'label'=>'<img src="'.Yii::app()->theme->baseUrl.'/images/icons/pages.png" /><br />Export',
                                'url'=>array('/admin/fralio/import'),
                                'active'=>strpos(Yii::app()->controller->route, "admin/fralio/export/")!==false),*/
                    ),
            ));?>
    <div id="account" class="li-count">
    	Hello <?php echo Yii::app()->user->name; ?> | <a href="<?php echo $this->createUrl('/admin/default/logout'); ?>">Logout</a>
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