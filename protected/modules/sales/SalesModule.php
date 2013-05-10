<?php

class SalesModule extends CWebModule
{
    public $gateway = 'professional'; //targetpay professional, simulator
    public $aquirer = 'Simulator'; // Use Rabobank, ABN Amro, ING Bank or Simulator
    public $merchant_id = '123456789';
    public $secure_path = '';
    public $private_cert = 'private.cer';
    public $private_key_pass = 'Password';
    public $private_key_file = 'private.key';
    public $sub_id='0';
    public $test_mode=true;
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'sales.models.*',
			'sales.components.*',
		));
                $this->defaultController = "Checkout";
                //$this->loginUrl = array('/sales/account/login');
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
