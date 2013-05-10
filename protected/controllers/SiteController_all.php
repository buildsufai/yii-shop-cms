<?php

/**
 * This controller takes care of rendering all static pages
 */
class SiteController extends Controller
{

    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionIndex()
    {
        $this->layout = 'slider';

        $alias = 'nieuws';

        $model = Category::model()->with('content:published')->findByAttributes(array('alias' => $alias));
        if ($model == null)
            throw new CHttpException(404, 'The requested page does not excists');

        $this->render('category', array(
            'model' => $model,
        ));
    }
    
    /**
     * Render the homepage
     */
    public function actionIndex2()
    {
        $model = Content::model()->findByAttributes(array('alias' => 'home'));

        if ($model == null)
            throw new CHttpException(404, "The requested page: 'home' does not excists");

        $this->render('/page/content', array('model'=>$model));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error)
        {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * The contact page is responseble for submitting the contact form
     * TODO: add email adres to newsletter signup if desired
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm']))
        {
            $model->attributes = $_POST['ContactForm'];

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'contact-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->validate())
            {
                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->administration->email, "Contact form: ". Yii::app()->administration->name, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Uw bericht is naar ons verzonden, wij nemen zo snel mogelijk contact met u op.');
                $this->refresh(); //$this->redirect(Yii::app()->user->returnUrl); //$this->refresh();
            }
        }
        $this->render('contact', array('model'=>$model));
    }

    public function actionSearch($q)
    {
        $criteria = new CDbCriteria;
        $criteria->compare('title', $q, true, 'OR');
        $criteria->compare('description', $q, true, 'OR');
        $criteria->compare('summary', $q, true, 'OR');
        $criteria->compare('meta_keywords', $q, true, 'OR');
        $content = new CActiveDataProvider('Content', array('criteria' => $criteria));

        $criteria = new CDbCriteria;
        $criteria->compare('t.sku', $q, true, 'OR');
        $criteria->compare('t.description', $q, true, 'OR');
        $criteria->compare('t.name', $q, true, 'OR');
        $criteria->compare('t.keywords', $q, true, 'OR');
        $product = new CActiveDataProvider('Product', array('criteria' => $criteria));

        $mergedData = array_merge($content->data, $product->data);

        $this->render('search', array('results'=>$mergedData));
    }

    /*
    public function actionSearch($q)
    {
        //$q = $_POST['q'];

        $criteria = new CDbCriteria;
        $criteria->compare('title', $q, true, 'OR');
        $criteria->compare('description', $q, true, 'OR');
        $criteria->compare('summary', $q, true, 'OR');
        $criteria->compare('meta_keywords', $q, true, 'OR');
        $content = new CActiveDataProvider('Content', array('criteria' => $criteria));

        $criteria = new CDbCriteria;
        $criteria->join = "LEFT JOIN product_translation lang ON t.id=lang.product_id";
        $criteria->with = "translation";
        $criteria->select = 't.name, t.description, t.serial_number';
        $criteria->compare('t.serial_number', $q, true, 'OR');
        $criteria->compare('t.description', $q, true, 'OR');
        $criteria->compare('t.name', $q, true, 'OR');
        $criteria->compare('t.keywords', $q, true, 'OR');
        $criteria->compare('t.material', $q, true, 'OR');
        $criteria->compare('t.anchoring', $q, true, 'OR');
        $criteria->compare('t.remarks', $q, true, 'OR');
        $criteria->compare('lang.description', $q, true, 'OR');
        $criteria->compare('lang.keywords', $q, true, 'OR');
        $criteria->compare('lang.material', $q, true, 'OR');
        $criteria->compare('lang.anchoring', $q, true, 'OR');
        $criteria->compare('lang.remarks', $q, true, 'OR');
        $product = new CActiveDataProvider('Product', array('criteria' => $criteria));

        $mergedData = array_merge($content->data, $product->data);

        $this->render('search', array('results'=>$mergedData));
    }
     * 
     */

}