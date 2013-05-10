<?php

/**
 * This is the model class for table "customer".
 *
 * The followings are the available columns in table 'customer':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $register_date
 * @property string $company
 * @property string $name
 * @property string $phone_nb
 * @property string $address
 * @property string $postalcode
 * @property string $city
 * @property string $country_code
 * @property integer $newsletter
 * @property string $ip
 *
 * The followings are the available model relations:
 * @property Order[] $orders
 */
class Customer extends CActiveRecord
{
    public $rememberMe = false;
    public $agree_terms;
    
    public $password_repeat;
    public $new_password;
    public $old_password;

    public $real_password;

    private $_identity;

    const STATUS_DISABLED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;


    /**
     * Returns the static model of the specified AR class.
     * @return Customer the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function getStatusTypes()
    {
        return array(
            self::STATUS_ACTIVE => 'Actief',
            //self::STATUS_PENDING => 'Ongevalideerd',
            self::STATUS_DISABLED => 'Uitgeschakeld',
        );
    }

    public function getStatusText()
    {
        $statusTypes = $this->statusTypes;
        return isset($statusTypes[$this->status]) ? $statusTypes[$this->status] : "unknown type ({$this->status})";
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'customer';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email, password, register_date, name, phone_nb, address, postalcode, city', 'required'),
            //array('newsletter', 'numerical', 'integerOnly'=>true),
            array('email, address, city', 'length', 'max'=>128),
            array('email', 'email'),
            array('email', 'unique', 'message' => 'Dit email adres bestaat al'),

            array('password', 'length', 'max'=>32),
            array('password_repeat', 'compare', 'compareAttribute' => 'password',  'on'=>'register'),
            array('password_repeat', 'required', 'on'=>'register'),

            //change password on account page
            array('old_password', 'changePassword' , 'on'=>'account'),
            array('password_repeat', 'compare', 'compareAttribute' => 'new_password',  'on'=>'account', 'message'=>'Wachtwoord moet exact herhaald worden.'),
            array('new_password', 'length', 'min'=>6, 'max'=>32, 'allowEmpty'=>true),

            array('company', 'length', 'max'=>100),
            array('name', 'length', 'max'=>150),
            array('phone_nb, ip', 'length', 'max'=>45),
            array('postalcode', 'length', 'max'=>10),
            array('country_code', 'length', 'max'=>2),
            array('agree_terms', 'compare', 'compareValue' => true, 'message' => 'Om je aan te melden moet je de algemene voorwaarden accepteren', 'on'=>'register' ),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, agree_terms, email, password, register_date, company, name, phone_nb, address, postalcode, city, country_code, newsletter, ip', 'safe', 'on'=>'search'),
        );
    }

    public function changePassword($attribute,$params)
    {
        if(!$this->hasErrors())  // we only want to validate when no input errors
        {
            if($this->validatePassword( $this->password ) == $this->hashPassword($this->old_password))
            {
                if(!empty($this->new_password))
                {
                    $this->password = $this->hashPassword($this->new_password);
                }
                else
                    $this->addError('new_password','Wachtwoord mag niet leeg zijn.'); //nieuw wachtwoord mag niet leeg zijn als het gewijzigd word
            }
            elseif(empty($this->old_password) && !empty($this->new_password))
            {
                $this->addError('old_password','Uw oude wachtwoord is niet correct.');
            }
            elseif(empty($this->old_password) && empty($this->new_password))
            {
                //NOP: password will not be changed when all fields are empty
            }
            else
                $this->addError('old_password','Uw oude wachtwoord is niet correct.');
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
            'orders' => array(self::HAS_MANY, 'Order', 'customer_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email adres',
            'password' => 'Wachtwoord',
            'new_password' => 'Nieuw wachtwoord',
            'password_repeat' => 'Wachtwoord herhalen',
            'old_password' => 'Oude wachtwoord',
            'register_date' => 'Registeer datum',
            'company' => 'Bedrijfsnaam',
            'name' => 'Voor- en achternaam',
            'phone_nb' => 'Telefoon nummer',
            'address' => 'Adres',
            'postalcode' => 'Postcode',
            'city' => 'Plaats',
            'country_code' => 'Land',
            'newsletter' => 'Newsletter',
            'ip' => 'IP Adres',
            'agree_terms'=>'Ik ga akkoord met de <a href="'.Yii::app()->controller->createUrl('/page/algemene-voorwaarden').'">algemene voorwaarden </a>',
        );
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
        $criteria->compare('email',$this->email,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('register_date',$this->register_date,true);
        $criteria->compare('company',$this->company,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('phone_nb',$this->phone_nb,true);
        $criteria->compare('address',$this->address,true);
        $criteria->compare('postalcode',$this->postalcode,true);
        $criteria->compare('city',$this->city,true);
        $criteria->compare('country_code',$this->country_code,true);
        $criteria->compare('newsletter',$this->newsletter);
        $criteria->compare('ip',$this->ip,true);

        return new CActiveDataProvider(get_class($this), array(
            'criteria'=>$criteria,
						'sort'=>array(
                'defaultOrder'=>'id DESC',
              )
        ));
    }


    protected function beforeValidate()
    {
        if (parent::beforeValidate())
        {
            if ($this->isNewRecord)
            {
                $this->register_date = date('Y-m-d H:i:s');
                $this->ip = Yii::app()->request->userHostAddress;
            }
            return true;
        }
        else
            return false;
    }
    protected function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->isNewRecord)
            {
                $this->real_password = $this->password;
                $this->password = $this->hashPassword($this->password);
            }
            return true;
        }
        else
            return false;
    }


    protected function afterSave()
    {
        parent::afterSave();
        if($this->isNewRecord)
        {
            $this->mailRegisterSuccess();
        }
    }

    private function mailRegisterSuccess()
    {
        $title = "Welkom bij " . Yii::app()->webshop->name;

        $old_layout = Yii::app()->controller->layout;
        Yii::app()->controller->layout = 'mailing';
        $message = Yii::app()->controller->render('application.modules.sales.views.mailing.register_success', array('title'=>$title, 'model'=>$this), true);
        Yii::app()->controller->layout = $old_layout;
        
        Yii::app()->mailer->IsMail();
        Yii::app()->mailer->From = Yii::app()->webshop->email;
        Yii::app()->mailer->FromName = Yii::app()->webshop->name;
        Yii::app()->mailer->AddReplyTo(Yii::app()->webshop->email);
        Yii::app()->mailer->AddAddress($this->email);
        Yii::app()->mailer->Subject = $title;
        Yii::app()->mailer->MsgHTML($message);
        return Yii::app()->mailer->Send();
    }

    /**
     * Logs in the customer using the given email and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
            if($this->_identity===null)
            {
                    $this->_identity=new CustomerIdentity($this->email,$this->password);
                    $this->_identity->authenticate();
            }
            if($this->_identity->errorCode===CustomerIdentity::ERROR_NONE)
            {
                    $duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
                    Yii::app()->customer->login($this->_identity,$duration);
                    return true;
            }
            else
                    return false;
    }
/*
    public function validatePasswordOld($password)
    {
        return $this->hashPassword($password) === $this->password;
    } */
    // This funstion validates a plain text password with an encrypted password
    public function validatePassword($plain)
    {
        if (!empty($plain) && !empty($this->password))
        {
            // split apart the hash / salt
            $stack = explode(':', $this->password);

            if (sizeof($stack) != 2)
                return false;

            if (md5($stack[1] . $plain) == $stack[0])
                return true;
        }

        return false;
    }

    function hashPassword($plain)
    {
        $password = '';

        for ($i=0; $i<10; $i++)
            $password .= mt_rand();

        $salt = substr(md5($password), 0, 2);
        $password = md5($salt . $plain) . ':' . $salt;

        return $password;
    }
    /*
    public function hashPassword($password)
    {
        return md5($password);
    } */
} 