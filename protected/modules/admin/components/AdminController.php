<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class AdminController extends Controller
{

    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column2';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    public function init()
    {
        $this->pageTitle = Yii::app()->name . " - " . Yii::t('backend', 'Admin');
        //Yii::app()->errorHandler->errorAction = array('admin/site/error');
        Yii::app()->language = Yii::app()->administration->language;
        Yii::app()->setTheme('cloudadmin');
        Yii::app()->clientScript->registerCoreScript('jquery.ui');
        Yii::app()->user->loginUrl = array('admin/default/login');
        Yii::app()->homeUrl = array('default/');
    }

}