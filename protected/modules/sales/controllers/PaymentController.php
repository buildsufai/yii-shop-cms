<?php

//Import the right gateway into this controller so it will perform the right actions
Yii::import('application.modules.sales.extensions.ideal.professional.*');

//PS: a decorator pattern on a controller is what you get when trying to solve the same problem for to long

class PaymentController extends Controller
{

    public function init()
    {
        $this->layout = '//layouts/full';
        Yii::app()->customer->loginUrl = array('sales/account/login');
    }
    
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
        	array('allow',  // allow admin
                'actions'=>array('transaction', 'report', 'return'),
            ),
            array('allow',  // allow admin
                'actions'=>array('index'),
                'expression'=>'!Yii::app()->customer->isGuest',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    /**
     * Link to pay the order using ideal
     * @param int $id Order id
     */
    public function actionIndex($id)
    {
        $model = Order::model()->findByPk($id);

        if($model===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');
				if(!$model->isPayable())
					throw new CHttpException(500, 'De order die uw probeerd te betalen is niet meer in afwachting van betaling');
        $gateway = new Gateway;
        $content = $gateway->doSetup($model->id);
        $this->render('index', array('content'=>$content, 'model'=>$model));

    }
    

    /**
     * Make the transaction
     */
    public function actionTransaction($order_id)
    {
        $gateway = new Gateway;
        $content = $gateway->doTransaction($order_id);
        $this->render('ideal', array('content'=>$content));
    }

    public function actionReport()
    {
        $gateway = new Gatway;

        $gateway->doReport();

    }

    /**
     * This is where the user goes when transation is complete
     */
    public function actionReturn()
    {
        $gateway = new Gateway;
        $content = $gateway->doReturn();
        $this->render('ideal', array('content'=>$content));
    }

    /**
     * Re-check at the payment service provider if the order was actualy paid for.
     * @param <type> $order_id
     */
    public function actionValidate($order_id)
    {
        
    }

}

?>