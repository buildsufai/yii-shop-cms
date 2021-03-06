<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Fralioshop',
    'defaultController'=>'Fralio',
    'language' => 'nl', //'en_gb',
    // preloading 'log' component
    'preload' => array('log', 'administration'),
    'theme'=>'fralioshop',
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.widgets.*',
	'application.modules.catalog.models.*',
        'application.modules.sales.models.*', //TODO: kinda crappy solution
        'application.modules.sales.components.*', //TODO: kinda crappy solution
    ),
    'modules' => array(
        'admin' => array(
            'modules' => array(
                'catalog',
                'sales',
                'pixmania',
            ),
        ),
        'sales'=>array(
            'gateway'=>'professional', //targetpay professional, simulator
            'aquirer'=>'Rabobank', // Use Rabobank, ABN Amro, ING Bank or Simulator
            'merchant_id'=>'002092267',
            'secure_path'=>dirname(dirname(dirname(__FILE__))) . '/ssl/',
            'private_cert'=>'cert.cer',
            'private_key_pass'=>'fe352T',
            'private_key_file'=>'priv.pem',
            //'return_url'=>'',
            //'sub_id'=>'',
            'test_mode'=>false
        ),
        'catalog',
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'lemonade',
            // 'ipFilters'=>array(...a list of IPs...),
            // 'newFileMode'=>0666,
            // 'newDirMode'=>0777,
        ),
        
    ),
    // application components
    'components' => array(
        'administration' => array(
            'class' => 'application.components.CAdministration',
            'administration_id'=>1,
            'urlRulesPath' => dirname(__FILE__) . '/urlRules',
        ),
        'webshop' => array(
            'class' => 'application.components.CAdministration',
            'administration_id'=>1,
            'urlRulesPath' => dirname(__FILE__) . '/urlRules',
        ),
        'mailer' => array(
            'class' => 'application.extensions.mailer.EMailer',
            'pathViews' => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts'
        ),
        'shoppingCart' => array(
            'class' => 'application.modules.sales.components.EShoppingCart',
        ),
        'image'=>array(
            'class'=>'ext.image.CImageComponent',
            'driver'=>'GD', // GD or ImageMagick
        ),
        'widgetFactory' => array(
            'widgets' => array(
                'CJuiAutoComplete' => array(
                    'cssFile' => false,
                ),
                'CJuiSortable' => array(
                    'cssFile' => false,
                    'itemTemplate'=>'<li>{content}<a href="#" rel="{id}">close</a></li>'
                ),
                'CJuiSelectable' => array(
                    'cssFile' => false,
                    //'itemTemplate'=>'<li>{content}<a href="#" rel="{id}">close</a></li>'
                ),
                'CJuiDatePicker' => array(
                    'cssFile' => false,
                    'language' => 'nl',
                    'options' => array(
                        'firstDay' => '1', // Weeks start on monday in europe
                    ),
                ),
                'CLinkPager' => array(
                    'maxButtonCount' => 20,
                    'cssFile' => false,
                ),
                'CGridView' => array(
                    'cssFile' => false,
                    'template' => '{items} {pager} {summary}',
                    'selectableRows' => 0,
                ),
                'CJuiButton' => array(
                    'cssFile' => false,
                ),
                'Ewysiwyg' => array(
                    'cssFile' => false,
                ),
                'CJuiDialog' => array(
                    'cssFile' => false,
                ),
            ),
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl'=>array('/sales/account/login'),
        ),
        'customer'=>array(
            'class' => 'CWebUser',
            'allowAutoLogin' => true,
            'loginUrl'=>array('/sales/account/login'),
        ),
				'assetManager' => array(
             'linkAssets' => true,
        ),
        // uncomment the following to enable URLs in path-format

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'caseSensitive' => true,
            'rules' => array(
                'admin'=>'admin/',
                'gii'=>'gii/',
                'over-ons.html'=>'site/aboutus',

                '<alias>.html'=>'fralio/<alias>',
                '<category>/<alias>.html'=>'page/content',
                '<alias>/' => 'page/category',
                
                '<module:\w+>' => '<module>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        /*
          'db'=>array(
          'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
          ),
         */
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=mydbname',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'schemaCachingDuration' => 3600,
            'enableParamLogging'=>true,
        ),
        'cache'=>array(
            'class'=>'system.caching.CDummyCache', //CApcCache',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'session'=>array(
            'class'=>'CHttpSession',
            'cookieMode' => 'allow',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                //'categories'=>'application.components.CSaveRelatedBehavior',
                ),
                array(
                    'class' => 'CWebLogRoute',
                    'categories' => 'system.db.CDbCommand',
                    'showInFireBug' => true,
                ), 
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // Datum formaat
        'date_format' => 'dd-MM-yyyy',
        // Max image width and height
        'gapi_login' => 'info@fralioshop.nl',
        'gapi_ww' => 'liekefrank',
        'bank_nr'=>'1032.77.412',
        // this will define formats for the image.
        // The format 'normal' always exist. This is the default format, by default no
        // suffix or no processing is enabled.
        'image_formats'=>array(
            // create a thumbnail grayscale format
            'thumb' => array(
                'action' => 'resize',
                'max_width' => 100,
                'max_height' => 100,
                'suffix' => '_thumb',
            ),
            'big' => array(
                'action' => 'resize',
                'max_width' => 300,
                'max_height' => 235,
                'suffix' => '_big',
            ),
            'small' => array(
                'action' => 'resize_w',
                'max_width' => 145,
                'max_height' => 120,
                'suffix' => '_list',
            ),
            'large' => array(
                'action' => 'resize',
                'max_width' => 1024,
                'max_height' => 768,
                'suffix' => '_large',
            )
        ),
    ),
);
