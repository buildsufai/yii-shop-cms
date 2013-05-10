<?php

/**
 * This is the model class for table "order_history".
 *
 * The followings are the available columns in table 'order_history':
 * @property integer $id
 * @property string $date_added
 * @property integer $status
 * @property integer $customer_notified
 * @property integer $order_id
 *
 * The followings are the available model relations:
 * @property Order $order
 */
class OrderHistory extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return OrderHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

        public function getStatusText()
        {
            $values = Order::model()->statusTypes;
            return isset($values[$this->status]) ? $values[$this->status] : "unknown type ({$this->status})";
        }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'order_history';
	}

        /**
         * This is invoked before the record is saved.
         * @return boolean whether the record should be saved.
         */
        protected function beforeValidate()
        {
            if (parent::beforeValidate())
            {
                if ($this->isNewRecord)
                {
                    $this->date_added = date('Y-m-d H:m:s');
                }
                return true;
            }
            else
                return false;
        }

    protected function afterSave()
    {
        parent::afterSave();
        if($this->customer_notified)
        {
            if($this->status == Order::STATUS_SHIPPED)
                $this->sendMailVerzonden();
            elseif($this->status == Order::STATUS_COMPLETE)
                $this->sendMailReady();
            else
                $this->sendMailStatusChanged();
        }
    }

    private function sendMailStatusChanged()
    {
        $title = "De status van uw bestelling is gewijzigd";

        $old_layout = Yii::app()->controller->layout;
        Yii::app()->controller->layout = 'application.modules.sales.views.layouts.mailing';
        $message = Yii::app()->controller->render('application.modules.sales.views.mailing.status_changed', array('title'=>$title, 'model'=>$this), true);
        Yii::app()->controller->layout = $old_layout;

        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = Yii::app()->webshop->email;
        Yii::app()->mailer->FromName = Yii::app()->webshop->name;
        Yii::app()->mailer->AddReplyTo(Yii::app()->webshop->email);
        Yii::app()->mailer->AddAddress($this->order->customer->email);
        Yii::app()->mailer->CharSet = 'UTF-8';
        Yii::app()->mailer->Subject = $title;
        Yii::app()->mailer->MsgHTML($message);
        return Yii::app()->mailer->Send();
    }

    private function sendMailReady()
    {
        $title = "Uw bestelling ligt voor u klaar";

        $old_layout = Yii::app()->controller->layout;
        Yii::app()->controller->layout = 'application.modules.sales.views.layouts.mailing';
        $message = Yii::app()->controller->render('application.modules.sales.views.mailing.order_ready', array('title'=>$title, 'model'=>$this), true);
        Yii::app()->controller->layout = $old_layout;

        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = Yii::app()->webshop->email;
        Yii::app()->mailer->FromName = Yii::app()->webshop->name;
        Yii::app()->mailer->AddReplyTo(Yii::app()->webshop->email);
        Yii::app()->mailer->AddAddress($this->order->customer->email);
        Yii::app()->mailer->CharSet = 'UTF-8';
        Yii::app()->mailer->Subject = $title;
        Yii::app()->mailer->MsgHTML($message);
        return Yii::app()->mailer->Send();
    }

    private function sendMailVerzonden()
    {
        $title = "Uw bestelling is vandaag verzonden";

        $old_layout = Yii::app()->controller->layout;
        Yii::app()->controller->layout = 'application.modules.sales.views.layouts.mailing';
        $message = Yii::app()->controller->render('application.modules.sales.views.mailing.order_shipped', array('title'=>$title, 'model'=>$this), true);
        Yii::app()->controller->layout = $old_layout;

				//TODO: check if the email gets attached as it should
				$pdfstring =  Yii::app()->controller->renderPartial('application.modules.sales.views.account.print',array(
					'model'=>$this->order,
					'outputDest'=>'S'
				),true);
				
        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = Yii::app()->webshop->email;
        Yii::app()->mailer->FromName = Yii::app()->webshop->name;
        Yii::app()->mailer->AddReplyTo(Yii::app()->webshop->email);
        Yii::app()->mailer->AddAddress($this->order->customer->email);
				Yii::app()->mailer->AddStringAttachment($pdfstring, 'factuur.pdf');
        Yii::app()->mailer->CharSet = 'UTF-8';
        Yii::app()->mailer->Subject = $title;
        Yii::app()->mailer->MsgHTML($message);
        return Yii::app()->mailer->Send();
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date_added, status, order_id', 'required'),
			array('status, customer_notified, order_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date_added, status, customer_notified, order_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_added' => 'Datum',
			'status' => 'Status',
			'customer_notified' => 'Klant attenderen',
			'order_id' => 'Order',
		);
	}
}