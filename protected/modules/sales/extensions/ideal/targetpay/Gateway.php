<?php

class Gateway
{

    public $_layout_code = 0;

    // Load iDEAL settings
    public function __construct()
    {
        $this->_layout_code = Yii::app()->params['ideal_code'];
    }

    public function doSetup($order_id)
    {
        $html = '<form action="'. Yii::app()->controller->createUrl('/sales/payment/transaction/', array('order_id'=>$order_id)) . '" method="post" id="checkout">
                <p>
                    <b>Kies uw bank</b><br>
                    <select name="issuer_id" style="margin: 6px; width: 200px;">
                        <script src="http://www.targetpay.com/ideal/issuers-nl.js"></script>
                    </select><br>
                    <input type="submit" value="Verder"></p>
                </form>';
        return $html;
    }


    // Execute payment
    public function doTransaction($order_id)
    {
        if(empty($_POST['issuer_id']) || empty($order_id))
            Yii::app()->controller->redirect($this->createUrl('index', array('id'=>$order_id)));
        
        $issuer_id = $_POST['issuer_id'];

        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        if($order->status==Order::STATUS_PROCESSING)
            throw new CHttpException (500, 'Transaction already completed');

        $oIdeal = new TargetPayIdeal($this->_layout_code);
        $oIdeal->setIdealAmount(round($order->totalPrice * 100)); //Payment amount needs to be in centen
        $oIdeal->setIdealissuer($issuer_id);
        $oIdeal->setIdealDescription('Webshop bestelling #' . $order->id);
        $oIdeal->setIdealReturnUrl(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/return/', array('order_id'=>$order->id)));
        $oIdeal->setIdealReportUrl(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/report/', array('order_id'=>$order->id)));

        list($sTransactionId, $sTransactionUrl) = $oIdeal->startPayment();

        Yii::log('doTransaction TARGETPAY: '. $sTransactionId . ' ' . $this->_layout_code, 'error');
        
        if($sTransactionId && $sTransactionUrl)
        {
            Yii::app()->controller->redirect($sTransactionUrl);
        }
        else
            throw new CHttpException (500, 'Incorrect execution of payment');
    }

    // This is the underwater return by the payment service provider
    public function doReport()
    {

        Yii::log('doReport TARGETPAY: '. print_r($_GET, true), 'warning');

        if(empty($_GET['trxid']) || empty($_GET['status']) || empty($_GET['order_id']))
            throw new CHttpException(500, 'Invalid return request.');

        $trxid = $_GET['trxid'];
        $order_id = $_GET['order_id'];
        $status = $_GET['status'];
        
        $order = Order::model()->findByPk($order_id);
        
        $this->zetStatus($order, $status);

    }

    // Catch return
    public function doReturn()
    {
        //Yii::log('doRetutn TARGETPAY: '. print_r($_GET, true), 'warning');
        
        if(empty($_GET['trxid']) || empty($_GET['ec']) || empty($_GET['order_id']))
             throw new CHttpException(500, 'Invalid return request.');

        $trxid = $_GET['trxid'];
        $order_id = $_GET['order_id'];

        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        # Init the class
        $oIdeal = new TargetPayIdeal ( $this->_layout_code );
        $status = $oIdeal->validatePayment($trxid, 0, 0);  //1 = TEST MODE

        $html .= $this->zetStatus($order, $status);

        return $html;

    }
    
    private function zetStatus($order, $status)
    {
        $html = "";
        if($status == "SUCCESS")
        {
            $history = new OrderHistory;
            $history->order_id = $order->id;
            $history->status = Order::STATUS_PROCESSING;
            $order->setStatus(Order::STATUS_PROCESSING);
            $order->payment_status = $status;
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