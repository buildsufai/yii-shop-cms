<?php

/**
 * This controller takes care of rendering all static pages
 */
class FralioController extends Controller
{
    
    public function actionIndex()
    {
        $this->layout = 'slider';
        
        $models = ProductCategory::model()->root()->findAll();
        
        $this->render('index', array('models'=>$models));
    }
    
    public function actionSitemap()
    {
			$criteria = new CDbCriteria(array('select'=>'t.alias, t.title'));
			$categories = Category::model()->with(array('content'=>array('select'=>'title, alias')) )->findAll($criteria);
			
        //Load all categories and content name + alias
			$criteria = new CDbCriteria(array('select'=>'t.alias, t.name'));
			$shopcats = ProductCategory::model()->with(array('subcategories'=>array('select'=>'name, alias')))->findAll($criteria);
				//Load all shop categories
			$this->render('sitemap', array('categories'=>$categories, 'shopcats'=>$shopcats));
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
        $this->layout = 'sidebar';
        
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
        $criteria->compare('meta_description', $q, true, 'OR');
        $criteria->compare('meta_keywords', $q, true, 'OR');
        $content = new CActiveDataProvider('Content', array('criteria' => $criteria));

        $criteria = new CDbCriteria;
        $criteria->compare('t.sku', $q, true, 'OR');
        $criteria->compare('t.description', $q, true, 'OR');
        $criteria->compare('t.name', $q, true, 'OR');
        $criteria->compare('t.meta_keywords', $q, true, 'OR');
        $product = new CActiveDataProvider('Product', array('criteria' => $criteria));

        $mergedData = array_merge($content->data, $product->data);

        $this->render('search', array('results'=>$mergedData));
    }

}