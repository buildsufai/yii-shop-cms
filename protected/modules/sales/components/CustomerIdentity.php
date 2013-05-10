<?php

/**
 * CustomerIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class CustomerIdentity extends CUserIdentity
{

    private $_id;

    /**
     * Authenticates a user.
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {
        $record = Customer::model()->findByAttributes(array('email' => $this->username));

        if ($record === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$record->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id = $record->id;
            $this->setState('customer', $record);
            $this->setState('role', User::ROLE_USER);
            $this->errorCode = self::ERROR_NONE;
        }
        return!$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }

}