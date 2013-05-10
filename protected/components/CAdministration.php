<?php

class CAdministration extends CApplicationComponent
{
    const CACHE_KEY = 'A4K.Administration';

    public $configTableName = 'administration';
    public $connectionID = 'db';
    public $cacheID = 'cache';
    public $strictMode = false;
    public $administration_id = null;
    public $_administrations = array();
    public $subdomain;
    public $domain;
    public $urlRulesPath;

    private $_config;

    const HQ_ID = 1;

    public function init()
    {
        $this->_getConfig();

        Yii::app()->setLanguage($this->_config->language);

        if(file_exists("{$this->urlRulesPath}/{$this->_config->language}.php"))
            Yii::app()->urlManager->rules = include("{$this->urlRulesPath}/{$this->_config->language}.php");
        else if(file_exists("{$this->urlRulesPath}/en_gb.php"))
            Yii::app()->urlManager->rules = include("{$this->urlRulesPath}/en_gb.php");

        if (false !== Yii::app()->urlManager->cacheID && null !== ($cache = Yii::app()->getComponent(Yii::app()->urlManager->cacheID)))
        {
            $keyPrefix = $cache->keyPrefix;
            $cache->keyPrefix = "{$keyPrefix}.{$this->_config->language}";
            Yii::app()->urlManager->init();
            $cache->keyPrefix = $keyPrefix;
        }
        else
        {
            Yii::app()->urlManager->init();
        }
    }

    public function __get($key)
    {
        if ($this->_config === null)
            $this->_getConfig();
        if (empty($this->_config->{$key}))
        {
            if ($this->strictMode)
                throw new CException("Unable to get value - no entry present with key \"{$key}\".");
            else
                return null;
        }

        return (null === $this->_config->{$key} && isset($this->_config->{$key})) ? null : $this->_config->{$key};
    }

    public function isHQ()
    {
        if ($this->_config === null)
            $this->_getConfig();
        return $this->_config->id == self::HQ_ID;
    }

    public function getList()
    {
        if ($this->_config === null)
            $this->_getConfig();
        return $this->_administrations;
    }

    /**
     * Load the right administration from database and cache it
     * @param CDbConnection $db The yii database connection
     * @param <type> $cache
     */
    private function _getConfig()
    {
        if (!Yii::app()->cache || !($this->_config = Yii::app()->cache->get(self::CACHE_KEY)))
        {
            $found = true;
            if (true === (bool)preg_match("/^(?<protocol>(http|https):\/\/)(((?<subdomain>[a-z]+)\.)*)((.*\.)*(?<domain>.+\.[a-z]+))$/", Yii::app()->request->hostInfo, $matches))
            {
                $this->subdomain = $matches['subdomain'];
                $this->domain = $matches['domain'];
            }
            //else
            //    throw new CException("Unable to parse host info - please request this page with a domain instead of an ip-address!");
            if(Administration::model()->hasAttribute('domain'))
                $this->_administrations = $administrations = Administration::model()->findAllByAttributes(array('domain'=>$this->domain, 'active'=>1));
            else
                $this->_administrations = $administrations = Administration::model()->findAllByPk(self::HQ_ID);

            if(count($administrations) == 1)
            {
                $this->_config = $administrations[0];
            }
            elseif(count($administrations) > 1)
            {
                
                if($this->subdomain == 'www' || $this->subdomain == '')
                {
                    $this->_config = $administrations[0];
                    $found = false;
                    
                    if(!strpos($_SERVER['REQUEST_URI'], "picklocation")!==false)
                        Yii::app()->request->redirect(Yii::app()->createUrl('site/picklocation'));
                    //Yii::app()->end();
                    //Yii::app()->getController()->render('application.views.site.picklocation', array('administrations'=>$administrations));
                    
                }
                else
                {
                    foreach($administrations as $administration)
                    {
                        if($administration->subdomain == $this->subdomain)
                        {
                             $this->_config = $administration;
                        }
                    }

                }
            }
            else
            {
                //TODO: might not be very safe to do this
                //throw new CHttpException(404, 'Website administration not found');
                $this->_config = Administration::model()->findByPk(self::HQ_ID);
                
                //if(Yii::app()->controller->route == '/site/distributors')
                    //Yii::app()->request->redirect(Yii::app()->controller->createUrl('/site/distributors'));
            }

            if (false !== Yii::app()->cache && $found)
            {
                Yii::app()->cache->set(self::CACHE_KEY, $this->_config);
            }
        }
    }

}

?>