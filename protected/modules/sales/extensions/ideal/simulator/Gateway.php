<?php

class Gateway
{
    public $aquirer = 'Simulator'; // Use Rabobank, ING Bank or Simulator
    public $hash_key = 'Password';
    public $merchant_id = '123456789';
    public $sub_id = '0';
    public $test_mode = true;

    // Load iDEAL settings
    public function __construct()
    {
        
    }


    public function doSetup($order_id)
    {

        return CHtml::link('Start test betaling', Yii::app()->controller->createUrl('/sales/payment/transaction', array('order_id'=>$order_id)));
    }


    // Execute payment
    public function doTransaction($order_id)
    {
        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        if($order->status==Order::STATUS_PROCESSING)
            throw new CHttpException (500, 'Transaction already completed');

        $oIdeal = new IdealLite();

        $oIdeal->setAquirer($this->aquirer, $this->test_mode);
        $oIdeal->setHashKey($this->hash_key);
        $oIdeal->setMerchant($this->merchant_id, $this->sub_id);
        
        $oIdeal->setUrlSuccess(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/return/', array('order_id'=>$order->id, 'status'=>'success')));
        $oIdeal->setUrlError(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/return/', array('order_id'=>$order->id, 'status'=>'error')));
        $oIdeal->setUrlCancel(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/return/', array('order_id'=>$order->id, 'status'=>'cancel')));

        // Set order details
        $oIdeal->setAmount($order->totalPrice); // Bedrag (in EURO's)
        $oIdeal->setOrderId($sOrderId); // Unieke order referentie (tot 16 karakters)
        $oIdeal->setOrderDescription('Test betaling: ' . number_format($fAmount, 2, ',', '')); // Order omschrijving (tot 32 karakters)

        // Customize submit button
        $oIdeal->setButton('Afrekenen met iDEAL');

        $html = $oIdeal->createForm();
        $html .= '<script type="text/javascript"> function doAutoSubmit() { document.forms[0].submit(); } setTimeout(\'doAutoSubmit()\', 100); </script>';
        return $html;
    }

    // Catch return
    public function doReturn()
    {
        if(empty($_GET['status']) || empty($_GET['order_id']))
             throw new CHttpException(500, 'Invalid return request.');

        $order_id = (empty($_GET['order_id']) ? '' : $_GET['order_id']);
        $status = (empty($_GET['status']) ? 'error' : $_GET['status']);

        //Find the transaction to validate
        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        // Send conformation to user
        $html = '';
        if(strcasecmp($status, 'success') === 0)
        {
            $history = new OrderHistory;
            $history->order_id = $order->id;
            $history->status = Order::STATUS_PROCESSING;
            $order->setStatus(Order::STATUS_PROCESSING);
            $order->payment_status = 'test_success';
            if($history->validate() && $order->validate())
            {
                //TODO: save multiple record in a transaction
                $history->save();
                $order->save();
            }

            $html .= '<p>Uw betaling is met succes ontvangen.</p>';
        }
        else
        {
            $order->payment_status = $status;
            $order->save();
            $html .= '<p>Er is een fout opgetreden bij het verwerken van uw betaling.</p>';
        }

        return $html;
    }
}

?>