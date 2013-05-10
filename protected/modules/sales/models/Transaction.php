<?php

/**
 * This is the model class for table "transaction".
 *
 * The followings are the available columns in table 'transaction':
 * @property integer $id
 * @property integer $order_id
 * @property string $order_code
 * @property string $transaction_id
 * @property string $transaction_code
 * @property string $methode
 * @property integer $transaction_date
 * @property double $amount
 * @property string $description
 * @property integer $status
 * @property string $url
 * @property string $payment_url
 * @property string $success_url
 * @property string $pending_url
 * @property string $failure_url
 * @property string $params
 * @property string $log
 *
 * The followings are the available model relations:
 * @property Order $order
 */
class Transaction extends CActiveRecord
{
    const STATUS_SUCCESS_VERIFIED = 100;
    const STATUS_SUCCESS = 101;
    const STATUS_PENDING = 102;
    const STATUS_OPEN = 103;
    const STATUS_CANCELLED = 104;
    const STATUS_FAILURE = 105;
    const STATUS_EXPIRED = 106;


	/**
	 * Returns the static model of the specified AR class.
	 * @return Transaction the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('order_id', 'required'),
			array('order_id, transaction_date, status', 'numerical', 'integerOnly'=>true),
			array('amount', 'numerical'),
			array('order_code, transaction_id, transaction_code, methode, description', 'length', 'max'=>100),
			array('url, payment_url, success_url, pending_url, failure_url', 'length', 'max'=>255),
			array('params, log', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, order_id, order_code, transaction_id, transaction_code, methode, transaction_date, amount, description, status, url, payment_url, success_url, pending_url, failure_url, params, log', 'safe', 'on'=>'search'),
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
			'order_id' => 'Order',
			'order_code' => 'Order Code',
			'transaction_id' => 'Transaction',
			'transaction_code' => 'Transaction Code',
			'methode' => 'Methode',
			'transaction_date' => 'Transaction Date',
			'amount' => 'Amount',
			'description' => 'Description',
			'status' => 'Status',
			'url' => 'Url',
			'payment_url' => 'Payment Url',
			'success_url' => 'Success Url',
			'pending_url' => 'Pending Url',
			'failure_url' => 'Failure Url',
			'params' => 'Params',
			'log' => 'Log',
		);
	}

}