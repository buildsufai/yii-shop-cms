<?php
/**
 * EIdeal class file.
 *
 * @author Michael de Hart
 * @version 1.0
 * @link http://www.cloudengineering.nl/
 * @copyright Copyright &copy; 2011 CloudEngineering
 *
 * This is the ideal extension for cloudshop
 *
 */
Yii::import('application.modules.sales.extensions.ideal.gateways.ideal-targetpay.*');
/**
 *
 * @author Michael de Hart
 * @package application..modules.sales.extensions.ideal
 * @since 1.0
 */
class EIdeal
{
   //***************************************************************************
   // Configuration
   //***************************************************************************

    //iDEAL simulator
   //public $marchant_id = '123456789';   // Metchant ID
   //public $sub_id = '0';                // You iDeal Sub ID
   //public $hash_key = 'Password';       // Hashkey
   // END iDEAL simulator

   //Target pay specific
   public $test_mode = true;                    // Use TEST/LIVE mode; true=TEST, false=LIVE
   public $layout_code = 'xxxxx';               // Layout code from targetpay

   // Basic gateway settings
   public $gateway_name = 'TargetPay - iDEAL';
   public $gateway_website = 'http://www.targetpay.nl/';
   public $gateway_method = 'ideal-targetpay';
   public $gateway_file = 'ideal-targetpay';
   public $gateway_validation = true;

   private $_gateway; //The gateway object

   protected $oRecord; //The order that is going to be payed using ideal

   /**
    * Init method for the application component mode.
    */
   public function init() { }

   public function __construct()
   {

        $this->_gateway = new Gateway();
        $this->_gateway->aSettings = $this->getConfig();
   }

   private function getConfig() {
        $aSettings = array();

        $aSettings['TEST_MODE'] = $this->test_mode;
        $aSettings['LAYOUT_CODE'] = $this->layout_code;

        $aSettings['GATEWAY_NAME'] = $this->gateway_name;
        $aSettings['GATEWAY_WEBSITE'] = $this->gateway_website;
        $aSettings['GATEWAY_METHOD'] = $this->gateway_method;
        $aSettings['GATEWAY_FILE'] = $this->gateway_file;
        $aSettings['GATEWAY_VALIDATION'] = $this->gateway_validation;

        return $aSettings;
   }

   // Create a random code with N digits.
    public static function randomCode($iLength = 64)
    {
            $aCharacters = array('a', 'b', 'c', 'd', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

            $sResult = '';

            for($i = 0; $i < $iLength; $i++)
            {
                    $sResult .= $aCharacters[rand(0, sizeof($aCharacters) - 1)];
            }

            return $sResult;
    }


   /**
    * Call a Gateway functions
    *
    * @param string $method the method to call
    * @param array $params the parameters
    * @return mixed
    */
    public function __call($method, $params)
    {
            if (is_object($this->_gateway) && get_class($this->_gateway)==='Gateway') return call_user_func_array(array($this->_gateway, $method), $params);
            else throw new CException('Can not call a method of a non existent object');
    }


   /**
    * Update the order status
    *
    * @param array $sView // WHAT IS THIS
    * @param string $oRecord // WHAT IS THIS
    */
    function gateway_update_order_status($oRecord, $sView)
    {
        if(in_array($sView, array('doReport', 'doValidate')))
        {
                $sComment = '';

                if(in_array($sView, array('doReport')))
                {
                        $sComment = 'Status rapportage ontvangen van Payment Service Provider.';
                }
                else // if(in_array($sView, array('doValidate')))
                {
                        $sComment = 'Status ontvangen na handmatige controle van openstaande transacties.';
                }

                // Update order status
                if(strcmp($oRecord['transaction_status'], 'SUCCESS') === 0)
                {
                        if(in_array($oRecord['transaction_method'], array('ideal-assurepay', 'ideal-mollie', 'ideal-targetpay', 'ideal-professional')))
                        {
                                $sOrderStatusId = '100'; // Verified
                        }
                        else
                        {
                                $sOrderStatusId = '101';
                        }
                }
                elseif(strcmp($oRecord['transaction_status'], 'PENDING') === 0)
                {
                        $sOrderStatusId = '102';
                }
                elseif(strcmp($oRecord['transaction_status'], 'OPEN') === 0)
                {
                        $sOrderStatusId = '103';
                }
                elseif(strcmp($oRecord['transaction_status'], 'CANCELLED') === 0)
                {
                        $sOrderStatusId = '104';
                }
                elseif(strcmp($oRecord['transaction_status'], 'EXPIRED') === 0)
                {
                        $sOrderStatusId = '105';
                }
                else // if(strcmp($oRecord['transaction_status'], 'FAILURE') === 0)
                {
                        $sOrderStatusId = '106';
                }

                // Update order status
                $sql = "UPDATE `" . DATABASE_PREFIX . "order` SET `order_status_id` = '" . $sOrderStatusId . "' WHERE (`order_id` = '" . $oRecord['order_id'] . "');";
                mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

                // Update order history
                $sql = "INSERT INTO `" . DATABASE_PREFIX . "order_history` SET `order_id` = '" . $oRecord['order_id'] . "', `order_status_id` = '" . $sOrderStatusId . "', `notify` = '0', `comment` = '" . addslashes($sComment) . "', `date_added` = NOW()";
                mysql_query($sql) or die('QUERY: ' . $sql . '<br>ERROR: ' . mysql_error() . '<br><br>FILE: ' . __FILE__ . '<br><br>LINE: ' . __LINE__);

                // Update stock?!
                // ..

                // Send confirmation email to client and/or webmaster?!
                // ..
        }
    }
}
