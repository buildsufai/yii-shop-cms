<?php

/**
 * QuestionForm class.
 * QuestionForm is the data structure for keeping
 * question form data. It is used by the 'question' action of 'SiteController'.
 */
class RegisterForm extends CFormModel
{
    public $name;
    public $email;
    public $phone_nb;

    public $address;
    public $postalcode;
    public $place;
    public $country;
    
    public $username;
    public $password;
    public $password_repeat;

    public $agree_terms;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            // name, email, subject and body are required
            array('name, email, phone_nb, address, postalcode, place, country, username, password, password_repeat', 'required'),
            // email has to be a valid email address
            array('password', 'compare', 'compareAttribute' => 'password_repeat'),
            array('email', 'email'),
            array('agree_terms', 'compare', 'compareValue' => true, 'message' => 'You must agree to the terms and conditions' ),
            array('agree_terms', 'boolean'),
        );
    }

    /**
     * Declares customized attribute labels.
     * If not declared here, an attribute would have a label that is
     * the same as its name with the first letter in upper case.
     */
    public function attributeLabels()
    {
        return array(
            'name' => Yii::t('lang', 'Name'),
            'email' => Yii::t('lang', 'E-Mail'),
            'phone_nb' => Yii::t('lang', 'Phone number'),

            'address' => Yii::t('lang', 'Address'),
            'postalcode' => Yii::t('lang', 'Zip code'),
            'place' => Yii::t('lang', 'Place'),
            'country' => Yii::t('lang', 'Country'),
            
            'username' => 'Gebruikersnaam',
            'password' => 'Wachtwoord',
            'password_repeat' => 'Herhaal wachtwoord',

            'agree_terms' => 'Ik ga akkoord met de algemene voorwaarden.',
        );
    }

}