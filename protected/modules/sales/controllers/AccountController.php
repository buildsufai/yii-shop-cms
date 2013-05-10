<?php
/**
 * This controller renders all the content the right way.
 */
class AccountController extends Controller
{
/*
    protected function beforeAction()
    {
        $this->pageTitle = "Webshop";
        return true;
    }
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
        	array('allow',  // allow admin
                'actions'=>array('login', 'register', 'cart', 'addProduct'),
            ),
            array('allow',  // allow admin
                'actions'=>array('account','logout','invoice'),
                'expression'=>'!Yii::app()->customer->isGuest',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

    public function actionLogin()
    {
        $this->layout = "//layouts/main";
        $model=new CustomerLoginForm;

        // collect user input data
        if(isset($_POST['CustomerLoginForm']))
        {
            $model->attributes=$_POST['CustomerLoginForm'];
            // validate user input and redirect to the previous page if valid
            if($model->validate() && $model->login())
                 $this->redirect(Yii::app()->customer->returnUrl);
        }
        // display the login form
        $this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        Yii::app()->customer->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function actionRegister()
    {
        $model=new Customer('register');

        if(isset($_POST['Customer']))
        {
            $model->attributes=$_POST['Customer'];
            // validate user input and redirect to the previous page if valid
            if($model->save())
            {
                $this->render('register_success');
                Yii::app()->end();
            }

        }

        $this->render('register',array('model'=>$model));
    }

    /**
     * Shows the session data of all items that are saved into the shoppingcart
     * If a product id is given the product gets added to the cart before displaying it
     */
    public function actionCart()
    {

        if(isset($_POST['CartItems'])) //Update the cart
        {
            $valid=true;
            foreach($_POST['CartItems'] as $i=>$item)
            {
                if(isset($item['markedDeleted']))
                    Yii::app()->shoppingCart->remove($i);
                else
                    Yii::app()->shoppingCart->itemAt($i)->setQuantity($item['quantity']);
            }
        }
				
				//get all related products of the product in the shoppingcart
				$shoppingCartIds = array();
				foreach(Yii::app()->shoppingCart->getPositions() as $pos)
					$shoppingCartIds[] = $pos->primaryKey;
				
				$related=array();
				if(!Yii::app()->shoppingCart->isEmpty())
				{
					$products = Product::model()->with('relatedProducts')->findAll(array(
						'condition'=>'t.id IN ('.implode(',',$shoppingCartIds).')',
						'order'=>'RAND()'
						));
					foreach($products as $product)
						$related = array_merge($related, $product->relatedProducts);

					shuffle($related);
				}

        $this->render('cart', array('related'=>$related));
    }

    public function actionAddProduct($product_id)
    {
        $quantity = (isset($_POST['Product']['quantity'])) ? $_POST['Product']['quantity'] : 1;
        
        $product = Product::model()->findByPk($product_id);
        if($product != null)
        {
            //if(isset($_POST['ChoiceGroup']))
            //{
                //TODO: save choice options to product before putting into cart
                //$choices = Choice::model()->with('choiceGroup')->findAllByPk($_POST['ChoiceGroup']);
                //$product->choices = $choices;
            //}
            Yii::app()->shoppingCart->put($product, $quantity);
        }
        else
            throw new CHttpException(404, "Het product kon niet gevonden worden");
        $this->redirect(array('cart'));
    }

    /**
     * Displays a page where the customer can change his customer details
     * Also gives a list of all the customers previous orders
     */
    public function actionAccount()
    {
        $model = Customer::model()->with('orders')->findByPk(Yii::app()->customer->id);
        $model->scenario='account';
        if(isset($_POST['Customer']))
        {
            $model->attributes=$_POST['Customer'];
            // validate user input and redirect to the previous page if valid
            if($model->save())
            {
                Yii::app()->customer->setFlash('accountSaved', 'Uw accountgegevens zijn succesvol gewijzigd');
            }
        }

        $this->render('account', array('model'=>$model));
    }

    public function actionInvoice($id)
    {
        $model = Order::model()->findByPk($id);

        if(Yii::app()->customer->id != $model->customer_id)
            throw new CHttpException (404, 'factuur not found');
        
        $this->renderPartial('application.modules.sales.views.account.print',array(
            'model'=>$model,
        ));
        
        $content = $this->renderPartial('print',array('model'=>$model), true);
    }


}