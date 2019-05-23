<?php
/**
 * Helper class for get and generate version functionality
 *
 * @category  StaticVersion
 * @package   Mhidalgo_StaticVersion
 * @author    Matias Hidalgo <me@mhidalgo.tk>
 * @copyright Copyright (c) 2017 Matias Hidalgo (http://www.mhidalgo.tk)
 */

/**
 * Class Mhidalgo_StaticVersion_Helper_Data
 *
 * @author    Matias Hidalgo <me@mhidalgo.tk>
 */
class Mhidalgo_StaticVersion_Helper_Data
    extends Mage_Core_Helper_Abstract
{
    /** Constant for Config */
    CONST XML_PATH_STATIC_VERSION_CONFIG = 'dev/mhidalgo_staticversion/';

    /**
     * Function for get Static Versioned url
     *
     * @param string $url
     * @param bool   $merged
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return string
     */
    public function getStaticVersioned($url, $merged = false)
    {
        if ($this->isEnabled()) {
            if (!$merged || ($merged && $this->applyForMerged())) {
                /** Get Version Number for given url */
                switch ($this->getVersionSource()) {
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_SourceVersion::DYNAMIC:
                        $versionNumber = $this->_getHashedVersion($this->getDynamicVersion($url));
                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_SourceVersion::STATICAL:
                    default:
                        $versionNumber = $this->_getHashedVersion($this->getStaticVersion());
                        break;
                }

                /** Apply version track based on Type */
                switch ($this->getVersionTrackType()) {
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::QUERY_STRING:
                        $url = $this->getQueryStringUrl($url, $versionNumber);
                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::FILE_RENAME:
                        $url = $this->getRenamedUrl($url, $versionNumber);
                        break;
                    case Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion::CUSTOM:
                    default:
                        $transportObject = new Varien_Object(
                            array(
                                'url' => $url,
                                'version_number' => $versionNumber,
                                'merged' => $merged,
                                'helper' => $this
                            )
                        );
                        Mage::dispatchEvent(
                            'mhidalgo_staticversion_static_versioned_custom',
                            array('transport' => $transportObject)
                        );
                        $url = $transportObject->getUrl();
                        break;
                }
            }
        }

        return $url;
    }

    /**
     * Function to check if static version module is enabled
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG . 'enable');
    }

    /**
     * Function to check if static version must be applied for merged content
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    public function applyForMerged()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG . 'apply_for_merged');
    }

    /**
     * Function to retrieve version track type
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return mixed
     */
    public function getVersionTrackType()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG . 'version_track_type');
    }

    /**
     * Function to retrieve version source
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return mixed
     */
    public function getVersionSource()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG . 'version_source');
    }

    /**
     * Function to retrieve custom query string param
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return mixed
     */
    public function getVersionQueryStringParam()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG . 'version_querystring_param');
    }

    /**
     * Function to check if hashing version is required
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    public function isHashingRequired()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_STATIC_VERSION_CONFIG . 'generate_hash');
    }

    /**
     * Function to retrieve Static Version
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return mixed
     */
    public function getStaticVersion()
    {
        return Mage::getStoreConfig(self::XML_PATH_STATIC_VERSION_CONFIG . 'version');
    }

    /**
     * Function to get Dynamic Version for a given Url
     *
     * @param $url
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return int|mixed
     */
    public function getDynamicVersion($url)
    {
        return $this->_getLastModTimeFromUrl($url);
    }

    /**
     * Function to get last modification time from a file in a given URL
     *
     * @param $url
     *
     * @author    Matias Hidalgo <me@mhidalgo.tk>
     * @return int|mixed
     */
    protected function _getLastModTimeFromUrl($url)
    {
        $baseDir = Mage::getBaseDir();
        $baseUrl = Mage::getBaseUrl();
        /** Support for CDN and for store code sub path */
        if (strpos($url, Mage::getBaseUrl()) === 0) {
            # pass
        } else if ($this->_isMediaUrl($url)) {
            $baseDir = Mage::getBaseDir('media');
            $baseUrl = Mage::getBaseUrl('media');
        } else if ($this->_isSkinUrl($url)) {
            $baseDir = Mage::getBaseDir('skin');
            $baseUrl = Mage::getBaseUrl('skin');
        } else if ($this->_isJsUrl($url)) {
            $baseDir = Mage::getBaseDir() . DS . 'js';
            $baseUrl = Mage::getBaseUrl('js');
        }

        $file = $baseDir . DS . trim(str_replace($baseUrl, '', $url), DS);

        return file_exists($file) ? filemtime($file) : $this->getStaticVersion();
    }

    /**
     * Function to check if an url belongs to Media folder
     *
     * @param $url
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    protected function _isMediaUrl($url)
    {
        return strpos($url, Mage::getBaseUrl('media')) === 0;
    }

    /**
     * Function to check if an url belongs to Skin folder
     *
     * @param $url
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    protected function _isSkinUrl($url)
    {
        return strpos($url, Mage::getBaseUrl('skin')) === 0;
    }

    /**
     * Function to check if an url belongs to Js folder
     *
     * @param $url
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return bool
     */
    protected function _isJsUrl($url)
    {
        return strpos($url, Mage::getBaseUrl('js')) === 0;
    }

    /**
     * Function to inject a version number into a given url
     *
     * @param $url
     * @param $version
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return string
     */
    public function getRenamedUrl($url,$version)
    {
        $base_url = Mage::getBaseUrl();
        if ($this->_isMediaUrl($url)) {
            $base_url = Mage::getBaseUrl('media');
        } else if ($this->_isSkinUrl($url)) {
            $base_url = Mage::getBaseUrl('skin');
        } else if ($this->_isJsUrl($url)) {
            $base_url = Mage::getBaseUrl('js');
        }
        $newUrl = trim(str_replace($base_url, $base_url . 'version'.$version . DS, $url), DS);
        return $newUrl;
    }

    /**
     * Function to set a query param with version number provided
     *
     * @param $url
     * @param $version
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return string
     */
    public function getQueryStringUrl($url,$version)
    {
        return Mage::getUrl('',
            array(
                '_direct' => str_replace(Mage::getBaseUrl(),'',$url),
                '_query' => array($this->getVersionQueryStringParam() => $version)
            )
        );
    }

    /**
     * Function to get a Hashed version of a version number whether is necesary
     *
     * @param $version
     *
     * @author Matias Hidalgo <me@mhidalgo.tk>
     * @return int
     */
    protected function _getHashedVersion($version)
    {
        if ($this->isHashingRequired()) {
            $version = crc32($version);
        }
        return $version;
    }
}
