<?php
class IdealRequest {

    protected $aErrors = array();
    // Security settings
    protected $sSecurePath;
    protected $sCachePath;
    protected $sPrivateKeyPass;
    protected $sPrivateKeyFile;
    protected $sPrivateCertificateFile;
    protected $sPublicCertificateFile;
    // Account settings
    protected $bABNAMRO = false; // ABN has some issues
    protected $sAquirerName;
    protected $sAquirerUrl;
    protected $bTestMode = false;
    protected $sMerchantId;
    protected $sSubId;
    // Constants
    protected $LF = "\n";
    protected $CRLF = "\r\n";

    public function __construct() {
        $this->sPrivateKeyFile = 'private.key';
        $this->sPrivateCertificateFile = 'private.cer';

        $this->setSecurePath(Yii::app()->controller->module->secure_path);

        if (defined('IDEAL_CACHE_PATH')) {
            $this->setCachePath(IDEAL_CACHE_PATH);
        }

        $this->setPrivateKey(Yii::app()->controller->module->private_key_pass);
        $this->sPrivateKeyFile = Yii::app()->controller->module->private_key_file;
        $this->sPrivateCertificateFile = Yii::app()->controller->module->private_cert;
        $this->setAquirer(Yii::app()->controller->module->aquirer, Yii::app()->controller->module->test_mode);
        $this->setMerchant(Yii::app()->controller->module->merchant_id); 
    }

    // Should point to directory with .cer and .key files
    public function setSecurePath($sPath) {
        $this->sSecurePath = $sPath;
    }

    // Should point to directory where cache is strored
    public function setCachePath($sPath = false) {
        $this->sCachePath = $sPath;
    }

    // Set password to generate signatures
    public function setPrivateKey($sPrivateKeyPass, $sPrivateKeyFile = false, $sPrivateCertificateFile = false) {
        $this->sPrivateKeyPass = $sPrivateKeyPass;

        if ($sPrivateKeyFile) {
            $this->sPrivateKeyFile = $sPrivateKeyFile;
        }

        if ($sPrivateCertificateFile) {
            $this->sPrivateCertificateFile = $sPrivateCertificateFile;
        }
    }

    // Set MerchantID id and SubID
    public function setMerchant($sMerchantId, $sSubId = 0) {
        $this->sMerchantId = $sMerchantId;
        $this->sSubId = $sSubId;
    }

    // Set aquirer (Use: Rabobank, ING Bank or ABN Amro)
    public function setAquirer($sAquirer, $bTestMode = false) {
        $this->sAquirerName = $sAquirer;
        $this->bTestMode = $bTestMode;

        if (stripos($sAquirer, 'rabo') !== false) { // Rabobank
            $this->sPublicCertificateFile = 'rabobank.cer';
            $this->sAquirerUrl = 'ssl://ideal' . ($bTestMode ? 'test' : '') . '.rabobank.nl:443/ideal/iDeal';
        } elseif (stripos($sAquirer, 'ing') !== false) { // ING Bank
            $this->sPublicCertificateFile = 'ingbank.cer';
            $this->sAquirerUrl = 'ssl://ideal' . ($bTestMode ? 'test' : '') . '.secure-ing.com:443/ideal/iDeal';
        } elseif (stripos($sAquirer, 'abn') !== false) { // ABN AMRO
            $this->bABNAMRO = true;
            $this->sPublicCertificateFile = 'abnamro' . ($bTestMode ? '.test' : '') . '.cer';
            $this->sAquirerUrl = '';

            // With ABN AMRO, the AcquirerUrl depends on the request type
            $sClass = get_class($this);

            if (strcasecmp($sClass, 'issuerrequest') === 0) {
                if ($bTestMode) {
                    $this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Directory.aspx';
                } else {
                    $this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/issuerInformation/getIssuerInformation.xml';
                }
            } elseif (strcasecmp($sClass, 'transactionrequest') === 0) {
                if ($bTestMode) {
                    $this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Transaction.aspx';
                } else {
                    $this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/acquirerTrxRegistration/getAcquirerTrxRegistration.xml';
                }
            } elseif (strcasecmp($sClass, 'statusrequest') === 0) {
                if ($bTestMode) {
                    $this->sAquirerUrl = 'ssl://itt.idealdesk.com:443/ITTEmulatorAcquirer/Status.aspx';
                } else {
                    $this->sAquirerUrl = 'ssl://idealm.abnamro.nl:443/nl/acquirerStatusInquiry/getAcquirerStatusInquiry.xml';
                }
            }
        } elseif (stripos($sAquirer, 'sim') !== false) { // IDEAL SIMULATOR
            $this->sPublicCertificateFile = 'simulator.cer';
            $this->sAquirerUrl = 'ssl://www.ideal-simulator.nl:443/professional/';
        } else { // Unknown issuer
            $this->setError('Unknown aquirer. Please use "Rabobank", "ING Bank", "ABN Amro" or "Simulator".', false, __FILE__, __LINE__);
            return false;
        }
    }

    // Error functions
    protected function setError($sDesc, $sCode = false, $sFile = 0, $sLine = 0) {
        $this->aErrors[] = array('desc' => $sDesc, 'code' => $sCode, 'file' => $sFile, 'line' => $sLine);
    }

    public function getErrors() {
        return $this->aErrors;
    }

    public function hasErrors() {
        return (sizeof($this->aErrors) ? true : false);
    }

    // Validate configuration
    protected function checkConfiguration($aSettings = array('sSecurePath', 'sPrivateKeyPass', 'sPrivateKeyFile', 'sPrivateCertificateFile', 'sPublicCertificateFile', 'sAquirerUrl', 'sMerchantId')) {
        $bOk = true;

        for ($i = 0; $i < sizeof($aSettings); $i++) {
            // echo $aSettings[$i] . ' = ' . $this->$aSettings[$i] . '<br>';

            if (empty($this->$aSettings[$i])) {
                $bOk = false;
                $this->setError('Setting ' . $aSettings[$i] . ' was not configurated.', false, __FILE__, __LINE__);
            }
        }

        return $bOk;
    }

    // Send GET/POST data through sockets
    protected function postToHost($url, $data, $timeout = 30) {
        $__url = $url;
        $idx = strrpos($url, ':');
        $host = substr($url, 0, $idx);
        $url = substr($url, $idx + 1);
        $idx = strpos($url, '/');
        $port = substr($url, 0, $idx);
        $path = substr($url, $idx);

        $fsp = fsockopen($host, $port, $errno, $errstr, $timeout);
        $res = '';

        if ($fsp) {
            // echo "\n\nSEND DATA: \n\n" . $data . "\n\n";

            fputs($fsp, 'POST ' . $path . ' HTTP/1.0' . $this->CRLF);
            fputs($fsp, 'Host: ' . substr($host, 6) . $this->CRLF);
            fputs($fsp, 'Accept: text/html' . $this->CRLF);
            fputs($fsp, 'Accept: charset=ISO-8859-1' . $this->CRLF);
            fputs($fsp, 'Content-Length:' . strlen($data) . $this->CRLF);
            fputs($fsp, 'Content-Type: text/html; charset=ISO-8859-1' . $this->CRLF . $this->CRLF);
            fputs($fsp, $data, strlen($data));

            while (!feof($fsp)) {
                $res .= @fgets($fsp, 128);
            }

            fclose($fsp);

            // echo "\n\nRECIEVED DATA: \n\n" . $res . "\n\n";
        } else {
            $this->setError('Error while connecting to ' . $__url, false, __FILE__, __LINE__);
        }

        return $res;
    }

    // Get value within given XML tag
    protected function parseFromXml($key, $xml) {
        $begin = 0;
        $end = 0;
        $begin = strpos($xml, '<' . $key . '>');

        if ($begin === false) {
            return false;
        }

        $begin += strlen($key) + 2;
        $end = strpos($xml, '</' . $key . '>');

        if ($end === false) {
            return false;
        }

        $result = substr($xml, $begin, $end - $begin);
        return $this->unescapeXml($result);
    }

    // Remove space characters from string
    protected function removeSpaceCharacters($string) {
        if ($this->bABNAMRO) {
            return preg_replace('/(\f|\n|\r|\t|\v)/', '', $string);
        } else {
            return preg_replace('/\s/', '', $string);
        }
    }

    // Escape (replace/remove) special characters in string
    protected function escapeSpecialChars($string) {
        $string = str_replace(array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ð', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', '§', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', '€', 'Ð', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', '§', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'Ÿ'), array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'ed', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 's', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'EUR', 'ED', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'S', 'U', 'U', 'U', 'U', 'Y', 'Y'), $string);
        $string = preg_replace('/[^a-zA-Z0-9\-\.\,\(\)_]+/', ' ', $string);
        $string = preg_replace('/[\s]+/', ' ', $string);

        return $string;
    }

    // Escape special XML characters
    protected function escapeXml($string) {
        return utf8_encode(str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
    }

    // Unescape special XML characters
    protected function unescapeXml($string) {
        return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;'), array('<', '>', '"', '&'), utf8_decode($string));
    }

    // Security functions
    protected function getCertificateFingerprint($bPublicCertificate = false) {
        if ($fp = fopen($this->sSecurePath . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), 'r')) {
            $sRawData = fread($fp, 8192);
            fclose($fp);

            $sData = openssl_x509_read($sRawData);

            if (!openssl_x509_export($sData, $sData)) {
                $this->setError('Error in certificate ' . $this->sSecurePath . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), false, __FILE__, __LINE__);
                return false;
            }

            $sData = str_replace('-----BEGIN CERTIFICATE-----', '', $sData);
            $sData = str_replace('-----END CERTIFICATE-----', '', $sData);

            return strtoupper(sha1(base64_decode($sData)));
        } else {
            $this->setError('Cannot open certificate file: ' . ($bPublicCertificate ? $this->sPublicCertificateFile : $this->sPrivateCertificateFile), false, __FILE__, __LINE__);
        }
    }

    // Calculate signature of the given message
    protected function getSignature($sMessage) {
        $sMessage = $this->removeSpaceCharacters($sMessage);

        if ($fp = fopen($this->sSecurePath . $this->sPrivateKeyFile, 'r')) {
            $sRawData = fread($fp, 8192);
            fclose($fp);

            $sSignature = '';

            if ($sPrivateKey = openssl_get_privatekey($sRawData, $this->sPrivateKeyPass)) {

                if (openssl_sign($sMessage, $sSignature, $sPrivateKey)) {
                    openssl_free_key($sPrivateKey);
                    $sSignature = base64_encode($sSignature);
                } else {
                    $this->setError('Error while signing message.', false, __FILE__, __LINE__);
                }
            } else {
                $this->setError('Invalid password for ' . $this->sPrivateKeyFile . ' file.', false, __FILE__, __LINE__);
            }

            return $sSignature;
        } else {
            $this->setError('Cannot open private key file: ' . $this->sPrivateKeyFile, false, __FILE__, __LINE__);
        }
    }

    // Validate signature for the given data
    protected function verifySignature($sData, $sSignature) {
        $bOk = false;

        if ($fp = fopen($this->sSecurePath . $this->sPublicCertificateFile, 'r')) {
            $sRawData = fread($fp, 8192);
            fclose($fp);

            if ($sPublicKey = openssl_get_publickey($sRawData)) {
                $bOk = (openssl_verify($sData, $sSignature, $sPublicKey) ? true : false);
                openssl_free_key($sPublicKey);
            } else {
                $this->setError('Cannot retrieve key from public certificate file: ' . $this->sPublicCertificateFile, false, __FILE__, __LINE__);
            }
        } else {
            $this->setError('Cannot open public certificate file: ' . $this->sPublicCertificateFile, false, __FILE__, __LINE__);
        }

        return $bOk;
    }

}

?>
