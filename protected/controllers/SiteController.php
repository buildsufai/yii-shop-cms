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

    /**
     * Render the homepage
     */
    public function actionIndex()
    {
        $model = Content::model()->findByAttributes(array('alias' => 'home'));
        
        if ($model == null)
            throw new CHttpException(404, "The requested page: 'home' does not excists");
        
        $this->pageTitle = (!empty($model->meta_title)) ? $model->meta_title : $model->title;
        $this->metaKeywords = $model->meta_keywords;
        $this->metaDescription = $model->meta_description;

        $this->render('index', array('model'=>$model));
    }
    
    public function actionAboutUs()
    {
        $model = Content::model()->findByAttributes(array('alias' => 'about-us'));
        
        if ($model == null)
            throw new CHttpException(404, "The requested page: 'home' does not excists");
        
        $this->pageTitle = (!empty($model->meta_title)) ? $model->meta_title : $model->title;
        $this->metaKeywords = $model->meta_keywords;
        $this->metaDescription = $model->meta_description;
        
        $this->render('/page/content', array('model'=>$model));
    }
    
    public function actionSitemap()
    {
			$criteria = new CDbCriteria(array('select'=>'t.alias, t.title'));
			$categories = Category::model()->with(array('content'=>array('select'=>'title, alias')) )->findAll($criteria);
			echo "<ul>";
			foreach($categories as $cat)
			{
				echo "<li>".$cat->name."</li>";
				foreach($cat->content as $content)
				{
					echo $content->getUrl(); 
				}
			}
			echo "</ul>";
        //Load all categories and content name + alias
			
				//Load all shop categories
			$this->render('sitemap', array('categories'=>$categories, 'shop'=>$shop));
    }

    /**
     * The contact page is responseble for submitting the contact form
     * TODO: add email adres to newsletter signup if desired
     */
    public function actionContact()
    {
        $model = new QuestionForm;
        if (isset($_POST['QuestionForm']))
        {
            $model->attributes = $_POST['QuestionForm'];

            if (isset($_POST['ajax']) && $_POST['ajax'] === 'contact-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
            if ($model->validate())
            {
                $app = ($model->wish_appointment) ? Yii::t('lang', 'An appointment with advisor').", " : "";
                $pho = ($model->wish_phone_contact) ? Yii::t('lang', 'Have phone contact'). ", " : "";
                $news = ($model->wish_newsletter) ? Yii::t('lang', 'Signup for newsletter'). " " : "";
                $body = Yii::t('lang', 'Company') . ": " . $model->company . "\n".
                    Yii::t('lang', 'Name') . ": " . $model->name . "\n".
                    Yii::t('lang', 'Address') . ": " . $model->address . "\n".
                    Yii::t('lang', 'Postalcode') . ": " . $model->postalcode . "\n".
                    Yii::t('lang', 'Place') . ": " . $model->place . "\n".
                    Yii::t('lang', 'Country') . ": " . $model->country . "\n".
                    Yii::t('lang', 'Phone number') . ": " . $model->telephone . "\n".
                    Yii::t('lang', 'E-Mail') . ": " . $model->email . "\n".
                    Yii::t('lang', 'Your wish') . ": " . $app . $pho . $news . "\n\n".
                    Yii::t('lang', 'Your question') . ": \n" . $model->body;

                $headers = "From: {$model->email}\r\nReply-To: {$model->email}";
                mail(Yii::app()->administration->email, "Contact form: ". Yii::app()->administration->name, $body, $headers);
                Yii::app()->user->setFlash('contact', Yii::t('lang', 'Thank you for contacting us. We will contact you as soon as possible.'));
                $this->refresh(); //$this->redirect(Yii::app()->user->returnUrl); //$this->refresh();
            }
        }
        
        $this->render('contact', array('model' => $model));
    }

    public function actionDistributors()
    {
        $administrations = Administration::model()->findAll();

        $this->render('distributors', array('administrations'=>$administrations));
    }

    /*
     * TODO: remove action and make function of CAdministation Component
     */
    public function actionPickLocation()
    {
        $administrations = Yii::app()->administration->getList();
        $this->render('picklocation', array('administrations'=>$administrations));
    }

    public function actionNewDistributor()
    {
        $this->render('new_distributor');
    }

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
        $criteria->join = "RIGHT JOIN product_translation lang ON t.id=lang.product_id";
        $criteria->with = "translation";
        $criteria->compare('t.serial_number', $q, true, 'OR');
        $criteria->compare('t.description', $q, true, 'OR');
        $criteria->compare('t.name', $q, true, 'OR');
        $criteria->compare('t.keywords', $q, true, 'OR');
        $criteria->compare('t.url_prefix', $q, true, 'OR');
        $criteria->compare('t.material', $q, true, 'OR');
        $criteria->compare('t.anchoring', $q, true, 'OR');
        $criteria->compare('t.remarks', $q, true, 'OR');
        $criteria->compare('lang.description', $q, true, 'OR');
        $criteria->compare('lang.keywords', $q, true, 'OR');
        $criteria->compare('lang.url_prefix', $q, true, 'OR');
        $criteria->compare('lang.material', $q, true, 'OR');
        $criteria->compare('lang.anchoring', $q, true, 'OR');
        $criteria->compare('lang.remarks', $q, true, 'OR');
        $product = new CActiveDataProvider('Product', array('criteria' => $criteria));

        $mergedData = array_merge($product->data, $content->data);

        $this->render('search', array('results'=>$mergedData));
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

}