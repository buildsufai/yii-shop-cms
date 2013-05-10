<?php

/**
 * This is the model class for table "order".
 *
 * The followings are the available columns in table 'order':
 * @property integer $id
 * @property string $invoice_id
 * @property string $create_date
 * @property integer $status
 * @property string $email
 * @property string $company
 * @property string $shipping_name
 * @property string $shipping_phone_nb
 * @property string $shipping_address
 * @property string $shipping_postalcode
 * @property string $shipping_city
 * @property string $shipping_country_code
 * @property integer $customer_id
 *
 * The followings are the available model relations:
 * @property Customer $customer
 * @property OrderDetail[] $orderDetails
 */

Yii::import('ext.behaviors.WithRelatedBehavior');

class Order extends XActiveRecord
{
    const SHIPPING_POSTAL = 1;
    const SHIPPING_PICKUP = 2;

    const PAYMENT_BANK = 1;
    const PAYMENT_IDEAL = 2;
    const PAYMENT_PICKUP = 3;

    const STATUS_PENDING = 0;
    const STATUS_DENIED = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_PROCESSING = 3;
    const STATUS_REVERSED = 4;
    const STATUS_COMPLETE = 5;
    const STATUS_SHIPPED = 6;


		public function getVat()
		{
			$vat = 0.21;
			if($this->isNewRecord)
				return $vat;
			if(date("Ymd", strtotime($this->create_date)) < '20121001')
				$vat = 0.19;
			return $vat;
		}
		
    /**
     * Returns the static model of the specified AR class.
     * @return Order the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function afterConstruct() {
        parent::afterConstruct();
        $s_methods = $this->getShippingMethodes();
        if(count($s_methods) == 1)
            $this->shipping_methode = key($s_methods);
    }

    public function getShippingMethodes()
    {
        return array(
            self::SHIPPING_POSTAL => 'Post verzenden',
           // self::SHIPPING_PICKUP => 'Bij ons afhalen',
        );
    }
    public function getShippingText()
    {
        $values = $this->shippingMethodes;
        return isset($values[$this->shipping_methode]) ? $values[$this->shipping_methode] : "unknown type ({$this->shipping_methode})";
    }

    public function getPaymentMethodes()
    {
        return array(
            //self::PAYMENT_IDEAL => 'IDeal betaling',
            self::PAYMENT_BANK => 'Bank betaling vooraf (iDEAL)',
            self::PAYMENT_PICKUP => 'Betaling bij afhalen',
        );
    }
    public function getPaymentText()
    {
        $values = $this->paymentMethodes;
        return isset($values[$this->payment_methode]) ? $values[$this->payment_methode] : "unknown type ({$this->payment_methode})";
    }
    public function getInvoiceIdText()
    {
        return 'F.'.date("Y", strtotime($this->create_date)).".".$this->invoice_id;
    }

    public function setStatus($value)
    {
        if($value == self::STATUS_PROCESSING || $value == self::STATUS_COMPLETE || $value == self::STATUS_SHIPPED && $this->invoice_id == null)
        {
            $this->invoice_id = $this->generateInvoiceId();
        }

        $this->status = $value;
    }
    private function generateInvoiceId() {

            $sql = 'SELECT MAX(`invoice_id`) as invoice_id FROM `'. $this->tableName(). '`';
            $row = $this->getDbConnection()->createCommand($sql)->queryRow();

            if ($row['invoice_id'] != null) {
                    $invoice_id = (int)$row['invoice_id'] + 1; //een ophogen
            } else {
                    $invoice_id = 1000; // Begin factuur nummer bij 1000
            }

            return $invoice_id;
    }


    public function getStatusTypes()
    {
        return array(
            self::STATUS_PENDING => 'Afwachting van betaling',  // Betaling is nog niet geregistreerd
            self::STATUS_DENIED => 'Afgewezen',                 // Bestelling is geweigerd
            self::STATUS_CANCELLED => 'Geannuleerd',            // Klant heeft bestelling zelf geannuleerd
            self::STATUS_PROCESSING => 'Wordt verwerkt',         // Bestelling die is betaald
            self::STATUS_REVERSED => 'Retour',                  // Gebruiker heeft bestelling retour verzonden
            self::STATUS_COMPLETE => 'Ligt voor u klaar',                // Bestelling ligt klaar om op te halen
            self::STATUS_SHIPPED => 'Verzonden',                // Bestelling is door winkel verzonden
        );
    }
    public function getStatusText()
    {
        $values = $this->statusTypes;
        return isset($values[$this->status]) ? $values[$this->status] : "unknown type ({$this->status})";
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'order';
    }

    /**
     * Push all the items in the cart on the order before saving it
     * @return boolean whether the record should be saved.
     */
    protected function beforeValidate()
    {
        if (parent::beforeValidate())
        {

            $orderDetails = array();
            foreach(Yii::app()->shoppingCart->getPositions() as $product)
            {
                $detail = new OrderDetail();
                $detail->id = $product->id;
                $detail->sku = $product->sku;
                $detail->order_id = $this->id;
                $detail->product_id = $product->id;
								$detail->vat_percentage = ($product->getVatPercentage()*100);
                $detail->name = $product->name;
                $detail->price = $product->getPrice();
                $detail->quantity = $product->quantity;
                $orderDetails[] = $detail;
            }
            $this->orderDetails = $orderDetails;
            
            if ($this->isNewRecord)
            {
                $this->create_date = date('Y-m-d H:m:s');
            }
            return true;
        }
        else
            return false;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('create_date, status, email, shipping_name, shipping_phone_nb, shipping_address, shipping_postalcode, shipping_city, shipping_country_code, customer_id, shipping_methode', 'required'),
            array('status, customer_id, shipping_methode', 'numerical', 'integerOnly'=>true),
            array('invoice_id, shipping_phone_nb, payment_status', 'length', 'max'=>45),
            array('email, shipping_address, shipping_city', 'length', 'max'=>128),
            array('company', 'length', 'max'=>100),
            array('shipping_name', 'length', 'max'=>150),
            array('shipping_postalcode', 'length', 'max'=>10),
            array('shipping_country_code', 'length', 'max'=>2),
            //array('payment_methode', 'pickupPayment'),
            array('comment', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, invoice_id, create_date, status, email, company, shipping_name, shipping_phone_nb, shipping_address, shipping_postalcode, shipping_city, shipping_country_code, customer_id', 'safe', 'on'=>'search'),
        );
    }

    public function pickupPayment($attribute,$params)
    {
        if(!$this->hasErrors())  // we only want to validate when no input errors
        {
            if($this->shipping_methode == self::SHIPPING_POSTAL && $this->payment_methode == self::PAYMENT_PICKUP)
            {
                $this->addError('payment_methode','Bij verzending van uw bestelling kunt u niet betalen bij afhalen.');
            }
        }
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'customer' => array(self::BELONGS_TO, 'Customer', 'customer_id'),
            'orderDetails' => array(self::HAS_MANY, 'OrderDetail', 'order_id', 'index' => 'id'),
            'orderHistories' => array(self::HAS_MANY, 'OrderHistory', 'order_id', 'order'=>'date_added DESC'),
        );
    }
    
    public function behaviors()
    {
        return array(
            'withRelated' => array(
              'class'=>'ext.behaviors.WithRelatedBehavior',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Order nr',
            'invoice_id' => 'Factuur nummer',
            'create_date' => 'Bestel datum',
            'status' => 'Status',
            'payment_status'=>'iDEAL status',
            'email' => 'Email',
            'company' => 'Bedrijfsnaam',
            'shipping_name' => 'Naam',
            'shipping_phone_nb' => 'Telefoon nr',
            'shipping_address' => 'Adres',
            'shipping_postalcode' => 'Postcode',
            'shipping_city' => 'Plaatsnaam',
            'shipping_country_code' => 'Land',
            'customer_id' => 'Klant',
            'shipping_methode'=>'Verzend methode',
            'payment_methode'=>'Betaal methode',
            'shipping_costs'=> 'Verzend kosten',
            'comment'=> 'Opmerkingen bij uw bestelling',
            'totalPrice'=>'Totaal prijs',
            'subTotalPrice'=>'Sub totaal',
        );
    }
		
		public function isPayed()
		{
			return ($this->status == self::STATUS_COMPLETE || $this->status == self::STATUS_SHIPPED || $this->status == self::STATUS_PROCESSING);
		}
		
		public function isPayable()
		{
			return ($this->status == self::STATUS_PENDING);
		}

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('invoice_id',$this->invoice_id,true);
        $criteria->compare('create_date',$this->create_date,true);
        $criteria->compare('status',$this->status);
        $criteria->compare('email',$this->email,true);
        $criteria->compare('company',$this->company,true);
        $criteria->compare('shipping_name',$this->shipping_name,true);
        $criteria->compare('shipping_phone_nb',$this->shipping_phone_nb,true);
        $criteria->compare('shipping_address',$this->shipping_address,true);
        $criteria->compare('shipping_postalcode',$this->shipping_postalcode,true);
        $criteria->compare('shipping_city',$this->shipping_city,true);
        $criteria->compare('shipping_country_code',$this->shipping_country_code,true);
        $criteria->compare('customer_id',$this->customer_id);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
            'sort'=>array(
                'defaultOrder'=>'create_date DESC',
              )
        ));
    }

    protected function afterSave()
    {
        parent::afterSave();
        if($this->isNewRecord)
            $this->sendMail();
    }

    private function sendMail()
    {
        $title = "Uw bestelling bij " . Yii::app()->webshop->name;

        $old_layout = Yii::app()->controller->layout;
        Yii::app()->controller->layout = 'mailing';
        $message = Yii::app()->controller->render('application.modules.sales.views.mailing.order_placed', array('title'=>$title, 'model'=>$this), true);
        Yii::app()->controller->layout = $old_layout;

        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = Yii::app()->webshop->email;
        Yii::app()->mailer->FromName = Yii::app()->webshop->name;
        Yii::app()->mailer->AddReplyTo(Yii::app()->webshop->email);
        Yii::app()->mailer->AddAddress($this->email);
				Yii::app()->mailer->AddBCC(Yii::app()->webshop->email);
        Yii::app()->mailer->CharSet = 'UTF-8';
        Yii::app()->mailer->Subject = $title;
        Yii::app()->mailer->MsgHTML($message);
        return Yii::app()->mailer->Send();
    }

    public function saveState() {
        Yii::app()->customer->setState(__CLASS__, $this);
        return true;
    }
    
    public function getCountDetails()
    {
        return count($this->details);
    }

    public function getTotalPrice()
    {
    	return $this->subTotalPrice + $this->shippingCosts;
    }

    public function getSubTotalPrice()
    {
        $result = 0;
    	foreach($this->orderDetails as $detail)
        {
            $result += $detail->totalPrice;
        }

        return $result;
    }

    public function getBtwPrice()
    {
        return $this->totalPrice - ($this->totalPrice / (1+$this->getVat()) );
    }

    public function getBtwAmount()
    {
        return ($this->totalPrice / (1+$this->getVat()) );
    }

    protected function getTotalWeight()
    {
        $totalweight = 0;
        
        foreach($this->orderDetails as $detail)
        {
            $totalweight += $detail->product->weight;
        }
        return $totalweight;
    }
    
    private function getCostByWeight()
    {
        $cost = 0;
        $totalweight = $this->getTotalWeight();
        $costs = ShippingCost::model()->findAll(array('order'=>'weight ASC'));
        foreach($costs as $shippingcost)
        {
            if ($totalweight >= $shippingcost->weight && $cost < $shippingcost->price)
                $cost = $shippingcost->price;
        }
        return $cost;
    }

    public function getShippingCosts()
    {
        $cost = 0;
        if($this->shipping_methode == self::SHIPPING_PICKUP) //NO SHIPPING
            return $cost;
        if(isset(Yii::app()->params['free_shipping_price']) && $this->getTotalPrice() > Yii::app()->params['free_shipping_price']) //FREE SHIPPING?
            return $cost;
        
        return $this->getCostByWeight();
    }
    
    public function getShippingCostsText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->getShippingCosts(), 'EUR');
    }

    public function getSubTotalPriceText()
    {
    	//if(Yii::app()->params['btw_included'])
    		return Yii::app()->numberFormatter->formatCurrency($this->subTotalPrice, 'EUR');
    	//else
    		//return Yii::app()->numberFormatter->formatCurrency($this->totalPrice + ($this->totalPrice * 19) / 100, 'EUR');
    }

    public function getTotalPriceText()
    {
    	return Yii::app()->numberFormatter->formatCurrency($this->totalPrice, 'EUR');
    }
    
    public function getBtwPriceText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->getBtwPrice(), 'EUR');
    }
    
    public function getBtwAmountText()
    {
        return Yii::app()->numberFormatter->formatCurrency($this->getBtwAmount(), 'EUR');
    }
} 