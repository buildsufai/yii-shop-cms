<?php
class TransactionRequest extends IdealRequest {

    protected $sOrderId;
    protected $sOrderDescription;
    protected $iOrderAmount;
    protected $sReturnUrl;
    protected $sIssuerId;
    protected $sEntranceCode;
    // Transaction info
    protected $sTransactionId;
    protected $sTransactionUrl;

    public function __construct() {
        parent::__construct();

        if (defined('IDEAL_RETURN_URL')) {
            $this->setReturnUrl(IDEAL_RETURN_URL);
        }

        // Random EntranceCode
        $this->sEntranceCode = sha1(rand(1000000, 9999999));
    }

    public function setOrderId($sOrderId) {
        $this->sOrderId = substr($sOrderId, 0, 16);
    }

    public function setOrderDescription($sOrderDescription) {
        $this->sOrderDescription = substr($this->escapeSpecialChars($sOrderDescription), 0, 32);
    }

    public function setOrderAmount($fOrderAmount) {
        $this->iOrderAmount = round($fOrderAmount * 100);
    }

    public function setReturnUrl($sReturnUrl) {
        // Fix for ING Bank, urlescape [ and ]
        $sReturnUrl = str_replace('[', '%5B', $sReturnUrl);
        $sReturnUrl = str_replace(']', '%5D', $sReturnUrl);

        $this->sReturnUrl = substr($sReturnUrl, 0, 512);
    }

    // ID of the selected bank
    public function setIssuerId($sIssuerId) {
        $this->sIssuerId = $sIssuerId;
    }

    // A random generated entrance code
    public function setEntranceCode($sEntranceCode) {
        $this->sEntranceCode = substr($sEntranceCode, 0, 40);
    }

    // Retrieve the transaction URL recieved in the XML response of de IDEAL SERVER
    public function getTransactionUrl() {
        return $this->sTransactionUrl;
    }

    // Execute request (Setup transaction)
    public function doRequest() {
        if ($this->checkConfiguration() && $this->checkConfiguration(array('sOrderId', 'sOrderDescription', 'iOrderAmount', 'sReturnUrl', 'sReturnUrl', 'sIssuerId', 'sEntranceCode')))
        {
            $sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
            $sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sIssuerId . $this->sMerchantId . $this->sSubId . $this->sReturnUrl . $this->sOrderId . $this->iOrderAmount . 'EUR' . 'nl' . $this->sOrderDescription . $this->sEntranceCode);
            $sToken = $this->getCertificateFingerprint();
            $sTokenCode = $this->getSignature($sMessage);

            $sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
                    . '<AcquirerTrxReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
                    . '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) . '</createDateTimeStamp>' . $this->LF
                    . '<Issuer>' . $this->LF
                    . '<issuerID>' . $this->escapeXml($this->sIssuerId) . '</issuerID>' . $this->LF
                    . '</Issuer>' . $this->LF
                    . '<Merchant>' . $this->LF
                    . '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
                    . '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
                    . '<authentication>SHA1_RSA</authentication>' . $this->LF
                    . '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
                    . '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
                    . '<merchantReturnURL>' . $this->escapeXml($this->sReturnUrl) . '</merchantReturnURL>' . $this->LF
                    . '</Merchant>' . $this->LF
                    . '<Transaction>' . $this->LF
                    . '<purchaseID>' . $this->escapeXml($this->sOrderId) . '</purchaseID>' . $this->LF
                    . '<amount>' . $this->escapeXml($this->iOrderAmount) . '</amount>' . $this->LF
                    . '<currency>EUR</currency>' . $this->LF
                    . '<expirationPeriod>PT30M</expirationPeriod>' . $this->LF
                    . '<language>nl</language>' . $this->LF
                    . '<description>' . $this->escapeXml($this->sOrderDescription) . '</description>' . $this->LF
                    . '<entranceCode>' . $this->escapeXml($this->sEntranceCode) . '</entranceCode>' . $this->LF
                    . '</Transaction>' . $this->LF
                    . '</AcquirerTrxReq>';

            $sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);
            Yii::log($sXmlReply, CLogger::LEVEL_ERROR);
            if ($sXmlReply) {
                if ($this->parseFromXml('errorCode', $sXmlReply)) { // Error found
                    // Add error to error-list
                    $this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
                } else {
                    $this->sTransactionId = $this->parseFromXml('transactionID', $sXmlReply);
                    $this->sTransactionUrl = html_entity_decode($this->parseFromXml('issuerAuthenticationURL', $sXmlReply));

                    return $this->sTransactionId;
                }
            }
        }

        return false;
    }

    // Start transaction
    public function doTransaction() {
        if ((sizeof($this->aErrors) == 0) && $this->sTransactionId && $this->sTransactionUrl) {
            header('Location: ' . $this->sTransactionUrl);
            exit;
        }

        $this->setError('Please setup a valid transaction request first.', false, __FILE__, __LINE__);
        return false;
    }

}

?>
