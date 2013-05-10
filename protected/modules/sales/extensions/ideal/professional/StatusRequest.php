<?php
class StatusRequest extends IdealRequest {

    // Account info
    protected $sAccountCity;
    protected $sAccountName;
    protected $sAccountNumber;
    // Transaction info
    protected $sTransactionId;
    protected $sTransactionStatus;

    public function __construct() {
        parent::__construct();
    }

    // Set transaction id
    public function setTransactionId($sTransactionId) {
        $this->sTransactionId = $sTransactionId;
    }

    // Get account city
    public function getAccountCity() {
        if (!empty($this->sAccountCity)) {
            return $this->sAccountCity;
        }

        return '';
    }

    // Get account name
    public function getAccountName() {
        if (!empty($this->sAccountName)) {
            return $this->sAccountName;
        }

        return '';
    }

    // Get account number
    public function getAccountNumber() {
        if (!empty($this->sAccountNumber)) {
            return $this->sAccountNumber;
        }

        return '';
    }

    // Execute request
    public function doRequest() {
        if ($this->checkConfiguration() && $this->checkConfiguration(array('sTransactionId'))) {
            $sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
            $sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sMerchantId . $this->sSubId . $this->sTransactionId);
            $sToken = $this->getCertificateFingerprint();
            $sTokenCode = $this->getSignature($sMessage);

            $sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
                    . '<AcquirerStatusReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
                    . '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) . '</createDateTimeStamp>' . $this->LF
                    . '<Merchant>'
                    . '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
                    . '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
                    . '<authentication>SHA1_RSA</authentication>' . $this->LF
                    . '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
                    . '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
                    . '</Merchant>' . $this->LF
                    . '<Transaction>'
                    . '<transactionID>' . $this->escapeXml($this->sTransactionId) . '</transactionID>' . $this->LF
                    . '</Transaction>'
                    . '</AcquirerStatusReq>';

            $sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);

            if ($sXmlReply) {
                if ($this->parseFromXml('errorCode', $sXmlReply)) { // Error found
                    // Add error to error-list
                    $this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
                } else {
                    $sTimestamp = $this->parseFromXml('createDateTimeStamp', $sXmlReply);
                    $sTransactionId = $this->parseFromXml('transactionID', $sXmlReply);
                    $sTransactionStatus = $this->parseFromXml('status', $sXmlReply);

                    $sAccountNumber = $this->parseFromXml('consumerAccountNumber', $sXmlReply);
                    $sAccountName = $this->parseFromXml('consumerName', $sXmlReply);
                    $sAccountCity = $this->parseFromXml('consumerCity', $sXmlReply);

                    $sMessage = $this->removeSpaceCharacters($sTimestamp . $sTransactionId . $sTransactionStatus . $sAccountNumber);

                    $sSignature = base64_decode($this->parseFromXml('signatureValue', $sXmlReply));
                    $sFingerprint = $this->parseFromXml('fingerprint', $sXmlReply);

                    if (strcasecmp($sFingerprint, $this->getCertificateFingerprint(true)) !== 0) {
                        // Invalid Fingerprint
                        $this->setError('Unknown fingerprint.', false, __FILE__, __LINE__);
                    } elseif ($this->verifySignature($sMessage, $sSignature) == false) {
                        // Invalid Fingerprint
                        $this->setError('Bad signature.', false, __FILE__, __LINE__);
                    } else {
                        // $this->sTransactionId = $sTransactionId;
                        $this->sTransactionStatus = strtoupper($sTransactionStatus);

                        $this->sAccountCity = $sAccountCity;
                        $this->sAccountName = $sAccountName;
                        $this->sAccountNumber = $sAccountNumber;

                        return $this->sTransactionStatus;
                    }
                }
            }
        }

        return false;
    }

}

?>
