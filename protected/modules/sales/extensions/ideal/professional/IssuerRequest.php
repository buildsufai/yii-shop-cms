<?php

class IssuerRequest extends IdealRequest {

    public function __construct() {
        parent::__construct();
    }

    // Execute request (Lookup issuer list)
    public function doRequest() {
        if ($this->checkConfiguration()) {
            $sCacheFile = false;

            if ($this->sCachePath) {
                $sCacheFile = $this->sCachePath . 'issuerrequest.cache';

                if (file_exists($sCacheFile) == false) {
                    // Attempt to create cache file
                    if (@touch($sCacheFile)) {
                        @chmod($sCacheFile, 0777);
                    }
                }

                if (file_exists($sCacheFile) && is_readable($sCacheFile) && is_writable($sCacheFile)) {
                    if (filemtime($sCacheFile) > strtotime('-24 Hours')) {
                        // Read data from cache file
                        if ($sData = file_get_contents($sCacheFile)) {
                            return unserialize($sData);
                        }
                    }
                } else {
                    $sCacheFile = false;
                }
            }

            $sTimestamp = gmdate('Y-m-d') . 'T' . gmdate('H:i:s') . '.000Z';
            $sMessage = $this->removeSpaceCharacters($sTimestamp . $this->sMerchantId . $this->sSubId);

            $sToken = $this->getCertificateFingerprint();
            $sTokenCode = $this->getSignature($sMessage);

            $sXmlMessage = '<?xml version="1.0" encoding="UTF-8" ?>' . $this->LF
                    . '<DirectoryReq xmlns="http://www.idealdesk.com/Message" version="1.1.0">' . $this->LF
                    . '<createDateTimeStamp>' . $this->escapeXml($sTimestamp) . '</createDateTimeStamp>' . $this->LF
                    . '<Merchant>' . $this->LF
                    . '<merchantID>' . $this->escapeXml($this->sMerchantId) . '</merchantID>' . $this->LF
                    . '<subID>' . $this->escapeXml($this->sSubId) . '</subID>' . $this->LF
                    . '<authentication>SHA1_RSA</authentication>' . $this->LF
                    . '<token>' . $this->escapeXml($sToken) . '</token>' . $this->LF
                    . '<tokenCode>' . $this->escapeXml($sTokenCode) . '</tokenCode>' . $this->LF
                    . '</Merchant>' . $this->LF
                    . '</DirectoryReq>';

            $sXmlReply = $this->postToHost($this->sAquirerUrl, $sXmlMessage, 10);
            //Yii::log($sXmlReply, CLogger::LEVEL_ERROR);
            if ($sXmlReply) {
                if ($this->parseFromXml('errorCode', $sXmlReply)) { // Error found
                    // Add error to error-list
                    $this->setError($this->parseFromXml('errorMessage', $sXmlReply) . ' - ' . $this->parseFromXml('errorDetail', $sXmlReply), $this->parseFromXml('errorCode', $sXmlReply), __FILE__, __LINE__);
                } else {
                    $aIssuerShortList = array();
                    $aIssuerLongList = array();

                    while (strpos($sXmlReply, '<issuerID>')) {
                        $sIssuerId = $this->parseFromXml('issuerID', $sXmlReply);
                        $sIssuerName = $this->parseFromXml('issuerName', $sXmlReply);
                        $sIssuerList = $this->parseFromXml('issuerList', $sXmlReply);

                        if (strcmp($sIssuerList, 'Short') === 0) { // Short list
                            // Only support ABN Amro Bank when in HTTPS mode.
                            // if((strcasecmp(substr($_SERVER['SERVER_PROTOCOL'], 0, 5), 'HTTPS') === 0) || (stripos($sIssuerName, 'ABN') === false)) {
                                $aIssuerShortList[$sIssuerId] = $sIssuerName;
                            //}
                        } else { // Long list
                            $aIssuerLongList[$sIssuerId] = $sIssuerName;
                        }

                        $sXmlReply = substr($sXmlReply, strpos($sXmlReply, '</issuerList>') + 13);
                    }

                    $aIssuerList = array_merge($aIssuerShortList, $aIssuerLongList);

                    // Save data in cache?
                    if ($sCacheFile) {
                        if ($oHandle = fopen($sCacheFile, 'w')) {
                            fwrite($oHandle, serialize($aIssuerList));
                            fclose($oHandle);
                        }
                    }

                    return $aIssuerList;
                }
            }
        }

        return false;
    }

}

?>
