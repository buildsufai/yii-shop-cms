<?php
/**
 * LoginMenu
 *
 * @version 0.01
 * @author Michael de Hart <info@cloudengineering.nl>
 */
Yii::import('zii.widgets.CPortlet');

class LoginMenu extends CPortlet
{
	/**
	 * @var boolean whether to enable remember login feature. Defaults to false.
	 * If you set this to true, please make sure you also set CWebUser.allowAutoLogin
	 * to be true in the application configuration.
	 */
	public $enableRememberMe=true;
        /**
	 * @var string user identity class. Defaults to 'application.modules.sales.components.CustomerIdentity'.
	 */
	public $identityClass='application.modules.sales.components.CustomerIdentity';
        
        /**
	 * Renders the body content in the portlet.
	 * This is required by XPortlet.
	 */
	protected function renderContent()
	{
		$user=new CustomerLoginForm($this->identityClass);
		if(isset($_POST['CustomerLoginForm']))
		{
			$user->attributes=$_POST['CustomerLoginForm'];
			if($user->validate() && $this->login($user))
				$this->controller->refresh();
		}
                if(Yii::app()->customer->isGuest)
                    $this->render('loginForm',array('user'=>$user));
                else
                    $this->render('loggedIn');
	}
        
        /**
	 * Logs in a user.
	 * @param XLoginForm the login form
	 * @return boolean whether the login is successful
	 */
	protected function login($user)
	{
		$class=Yii::import($this->identityClass);
		$identity=new $class($user->email,$user->password);
		if($identity->authenticate())
		{
			if($this->enableRememberMe && $user->rememberMe)
				$duration=3600*24*30;   // 30 days
			else
				$duration=0;
			Yii::app()->user->login($identity,$duration);
			return true;
		}
		else
			$user->addError('password','Incorrect password.');
	}
}