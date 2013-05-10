<?php
ini_set('display_errors',1);
date_default_timezone_set('Europe/Amsterdam'); // TODO: change this accordingly
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php'; // <- download yii framework 1.1.x and place it in this directory
$config=dirname(__FILE__).'/protected/config/myconfig.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createWebApplication($config)->run();
