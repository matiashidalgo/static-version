<?php
/**
 * Helper class for get and generate version functionality
 *
 * @category  StaticVersion
 * @package   Mhidalgo_StaticVersion
 * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
 * @copyright Copyright (c) 2016 Matias Hidalgo (http://www.mhidalgo.xyz)
 */

/**
 * Class Mhidalgo_StaticVersion_Helper_Data
 *
 * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
 */
class Mhidalgo_StaticVersion_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /** Constant for Config */
    CONST XML_PATH_STATIC_VERSION_CONFIG = 'dev/mhidalgo_staticversion/';

    /**
     * Function for get Static Versioned url
     * @param string    $url
     * @param bool      $merged
     *
     * @author Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return string
     */
    public function getStaticVersioned($url,$merged = false)
    {
        if ($this->isEnabled()) {
            if (!$merged || ($merged && $this->applyForMerged())) {
                switch ($this->getVersionTrackType()) {
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::STATIC_QUERY_STRING:
                        $version = '?';
                        $version .= $this->getVersionQueryStringParam().'=';
                        if ($this->generateHashForQueryString()) {
                            $version .= md5($this->getStaticVersion());
                        } else {
                            $version .= $this->getStaticVersion();
                        }
                        $url .= $version;
                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::DYNAMIC_QUERY_STRING:
                        $version = '?';
                        $version .= $this->getVersionQueryStringParam().'=';
                        if ($this->generateHashForQueryString()) {
                            $version .= md5($this->getDynamicVersion($url));
                        } else {
                            $version .= $this->getDynamicVersion($url);
                        }
                        $url .= $version;
                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::STATIC_FILE_RENAME:

                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::DYNAMIC_FILE_RENAME:

                        break;
                    default:
                        $transportObject = new Varien_Object(array('url' => $url,'merged' => $merged,'helper' => $this));
                        Mage::dispatchEvent('mhidalgo_staticversion_static_versioned_custom', array('transport' => $transportObject));
                        $url = $transportObject->getUrl();
                        break;
                }
            }
        }
        return $url;
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG.'enable');
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return bool
     */
    public function applyForMerged()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG.'apply_for_merged');
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return mixed
     */
    public function getVersionTrackType()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG.'version_track_type');
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return mixed
     */
    public function getVersionQueryStringParam()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG.'version_querystring_param');
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return bool
     */
    public function generateHashForQueryString()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG.'generate_hash_for_querystring');
    }

    /**
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return mixed
     */
    public function getStaticVersion()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG.'version');
    }

    /**
     * @param $url
     *
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return int|mixed
     */
    public function getDynamicVersion($url)
    {
        return $this->_getLastModTimeFromUrl($url);
    }

    /**
     * @param $url
     *
     * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
     * @return int|mixed
     */
    protected function _getLastModTimeFromUrl($url)
    {
        $base_dir = Mage::getBaseDir();
        $base_url = Mage::getBaseUrl();
        /** Support for CDN and for store code sub path */
        if (strpos($url, Mage::getBaseUrl()) === 0) {
            # pass
        } else if (strpos($url, Mage::getBaseUrl('media')) === 0) {
            $base_dir = Mage::getBaseDir('media');
            $base_url = Mage::getBaseUrl('media');
        } else if (strpos($url, Mage::getBaseUrl('skin')) === 0) {
            $base_dir = Mage::getBaseDir('skin');
            $base_url = Mage::getBaseUrl('skin');
        } else if (strpos($url, Mage::getBaseUrl('js')) === 0) {
            $base_dir = Mage::getBaseDir() . DS . 'js';
            $base_url = Mage::getBaseUrl('js');
        }
        $file = $base_dir . DS . trim(str_replace($base_url, '', $url), DS);

        return file_exists($file) ? filemtime($file) : $this->getStaticVersion();
    }

}