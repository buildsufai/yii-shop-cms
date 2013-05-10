<?php

/**
 * This controller renders all the content the right way.
 */
class CheckoutController extends Controller
{
    public function init()
    {
        $this->layout = '//layouts/full';
        Yii::app()->customer->loginUrl = array('sales/account/login');
    }
    /**
     * @return array action filters
     */
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
            array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('shipping','overview'),
                'users' => array('@'),
            ),
            array('deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    /**
     * Display the customers information that can be edited here
     * Display a login/register form if no customer was logged in
     */
    public function actionShipping()
    {
        $model = Yii::app()->customer->getState('Order');
        if ($model == null)
            $model = new Order;

        //Add contact detail to order when user is logged in
        if(!Yii::app()->customer->isGuest && isset(Yii::app()->customer->customer))
        {
            $customer = Yii::app()->customer->customer;

            $model->email = $customer->email;
            $model->company = $customer->company;
            $model->shipping_name = $customer->name;
            $model->shipping_address = $customer->address;
            $model->shipping_city = $customer->city;
            $model->shipping_country_code = $customer->country_code;
            $model->shipping_postalcode = $customer->postalcode;
            $model->shipping_phone_nb = $customer->phone_nb;
            $model->customer_id = $customer->id;
        }

        if(isset($_POST['Order']))
        {
            $model->attributes=$_POST['Order'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->saveState())
            {
                $this->redirect(array('overview'));
            }
        }

        $this->render('shipping', array('model'=>$model));
    }

    /**
     * Overview of order settings
     * On the overview page we put the content of the shoppingcart into the order
     */
    public function actionOverview()
    {
        $model = Yii::app()->customer->getState('Order');

        if(Yii::app()->request->isPostRequest) //confirm overview
        {
            if($model->withRelated->save(array('orderDetails')))
            {
                Yii::app()->customer->setState('Order', new Order);
								Yii::app()->shoppingCart->clear();
                $this->redirect(array('payment/index', 'id' => $model->id));
            }
        }
        $this->render('overview', array('model'=>$model));
    }

}