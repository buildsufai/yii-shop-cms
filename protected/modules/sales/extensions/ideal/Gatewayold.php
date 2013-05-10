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
        Yii::import('application.modules.sales.extensions.ideal.simulator.IdealLite.php');
    }

    protected function randomCode($iLength = 64)
    {
            $aCharacters = array('a', 'b', 'c', 'd', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

            $sResult = '';

            for($i = 0; $i < $iLength; $i++)
            {
                    $sResult .= $aCharacters[rand(0, sizeof($aCharacters) - 1)];
            }

            return $sResult;
    }

    public function doSetup($order_id, $amount)
    {
        $sOrderId = $order_id;
        $fAmount = $amount;

        $oIdeal = new IdealLite();

        // Bepaal de URL waar de bezoeker naar toe wordt gestuurd nadat de ideal betaling is afgerond (of bij fouten)
        $sCurrentUrl = strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/')) . '://' . $_SERVER['SERVER_NAME'] . '/') . substr($_SERVER['SCRIPT_NAME'], 1);
        $sReturnUrl = substr($sCurrentUrl, 0, strrpos($sCurrentUrl, '/') + 1);

        $oIdeal->setUrlSuccess(Yii::app()->request->hostInfo .'/'. Yii::app()->controller->createUrl('sales/payment/return/', array('order_id'=>$order->id, 'status'=>'success')));
        $oIdeal->setUrlError(Yii::app()->request->hostInfo .'/'. Yii::app()->controller->createUrl('sales/payment/return/', array('order_id'=>$order->id, 'status'=>'error')));
        $oIdeal->setUrlCancel(Yii::app()->request->hostInfo .'/'. Yii::app()->controller->createUrl('sales/payment/return/', array('order_id'=>$order->id, 'status'=>'cancel')));

        // Set order details
        $oIdeal->setAmount($fAmount); // Bedrag (in EURO's)
        $oIdeal->setOrderId($sOrderId); // Unieke order referentie (tot 16 karakters)
        $oIdeal->setOrderDescription('Test betaling: ' . number_format($fAmount, 2, ',', '')); // Order omschrijving (tot 32 karakters)

        // Customize submit button
        $oIdeal->setButton('Afrekenen met iDEAL');

        return $oIdeal->createForm();
    }


    // Execute payment
    public function doTransaction($issuer_id, $order_id)
    {
        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        if($transaction===null)
        {
            // create transaction
            $transaction = new Transaction;
            $transaction->order_id = $order->id;
            $transaction->order_code = $this->randomCode(32);
            $transaction->transaction_id = $this->randomCode(32);
            $transaction->transaction_code = $this->randomCode(32);
            $transaction->amount = $order->totalPrice;
            $transaction->status = Transaction::STATUS_OPEN;
            $transaction->description = 'Webshop bestelling #' . $order->id;
            $transaction->payment_url = $this->createUrl('/sales/payment/index');
            $transaction->success_url = $this->createUrl('/sales/payment/callback');
            $transaction->pending_url = $transaction->success_url;
            $transaction->failure_url = $transaction->payment_url;
        }

        //HERE we have a transaction
        if($transaction->status==Transaction::STATUS_SUCCESS)
            throw new CHttpException (500, 'Transaction already completed');

        if($transaction->status==Transaction::STATUS_OPEN && !empty($transaction->url))
            Yii::app()->controller->redirect($transaction->url);


        $oIdeal = new TargetPayIdeal($this->_layout_code);
        $oIdeal->setIdealAmount(round($transaction->amount * 100));
        $oIdeal->setIdealissuer($_POST['issuer_id']);
        $oIdeal->setIdealDescription($transaction->description);
        $oIdeal->setIdealReturnUrl(Yii::app()->request->hostInfo .'/'. Yii::app()->controller->createUrl('sales/payment/return/', array('transaction_code'=>$transaction->transaction_code)));
        //$oIdeal->setIdealReportUrl(Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('sales/payment/report/', array('transaction_code'=>$this->oRecord['transaction_code'])));
        //echo Yii::app()->request->hostInfo . Yii::app()->controller->createUrl('/sales/payment/return/', array('transaction_code'=>$transaction->transaction_code));

        list($sTransactionId, $sTransactionUrl) = $oIdeal->startPayment();

        if($sTransactionId && $sTransactionUrl)
        {
            if(empty($transaction->log) == false)
            {
                    $transaction->log .= "\n\n";
            }

            $transaction->log .= 'Executing TransactionRequest on ' . date('Y-m-d, H:i:s') . '. Recieved: ' . $sTransactionId;
            $transaction->transaction_id = $sTransactionId;
            $transaction->url = $sTransactionUrl;
            $transaction->transaction_date = time();

            if($transaction->save())
                Yii::app()->controller->redirect($transaction->url);
        }
        else
            throw new CHttpException (500, 'Incorrect execution of startPayment');
    }

    // Catch return
    public function doReturn($trxid, $order_id)
    {
        //Find the transaction to validate
        $order = Order::model()->findByPk($order_id);

        if($order===null)
            throw new CHttpException(404,'De bestelling die u wilt betalen kan niet gevonden worden.');

        # Init the class
        $oIdeal = new TargetPayIdeal ( $this->_layout_code );
        $status = $oIdeal->validatePayment($trxid, 0, 1);  //1 = TEST MODE


        $sOrderId = (empty($_GET['order']) ? '' : $_GET['order']);
        $sStatus = (empty($_GET['status']) ? 'error' : $_GET['status']);

        // Send conformation to user
        if(strcasecmp($sStatus, 'success') === 0)
            $html .= '<p>Uw betaling is met succes ontvangen.</p>';
        else
            $html .= '<p>Er is een fout opgetreden bij het verwerken van uw betaling.</p>';

        $html .= '<p><a href="index.php">Verder testen</a></p>';


        if($status == "SUCCESS")
        {
            $this->updateOrder($transaction->order_id);
        }

    }

    /**
     * update the order status when the iDeal transaction was success
     */
    protected function updateOrder($order_id)
    {
        $model = Order::model()->findByPk($order_id);
        $history = new OrderHistory;
        $history->order_id = $model->id;
        $history->status = Order::STATUS_PROCESSING;
        $model->status = Order::STATUS_PROCESSING;

        if($history->validate() && $model->validate())
        {
            //TODO: save multiple record in a transaction
            $history->save();
            $model->save();
            Yii::app()->user->setFlash('orderSaved', 'Uw iDEAL betaling is successvol afgerond');
        }
    }
}

?>