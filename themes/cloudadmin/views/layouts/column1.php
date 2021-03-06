<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php //TODO: check language echo Yii::app()->language; ?>" />

	<!-- blueprint CSS framework -->
	<link href="<?php echo Yii::app()->request->hostInfo . Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="icon">
	<link href="<?php echo Yii::app()->request->hostInfo . Yii::app()->theme->baseUrl; ?>/images/favicon.ico" type="image/x-icon" rel="shortcut icon">
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/main.css" media="all" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/jquery-ui.css" media="all" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>
<body class="login">

	<!-- Begin login window -->
	<div id="login_wrapper">

		
		<!-- Begin content -->
		<div id="login_body_window">
			<?php echo $content; ?>

		</div>
		<!-- End content -->

	</div>

	<!-- End login window -->
	
</body>
</html>
