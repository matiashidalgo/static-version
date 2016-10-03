<?php
/**
 * Class for keep options for version type
 *
 * @category  Project
 * @package   Module_Name
 * @author    Matias Hidalgo <matias.hidalgo@redboxdigital.com>
 * @copyright Copyright (c) 2016 Redbox Digital (http://www.redboxdigital.com)
 */

class Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion
{

    CONST STATIC_QUERY_STRING = 1;
    CONST DYNAMIC_QUERY_STRING = 2;
    CONST STATIC_FILE_RENAME = 3;
    CONST DYNAMIC_FILE_RENAME = 4;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::STATIC_QUERY_STRING, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Static Query String after Url')),
            array('value' => self::DYNAMIC_QUERY_STRING, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Dynamic Query String after Url')),
            /*array('value' => self::STATIC_FILE_RENAME, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Static File Rename')),
            array('value' => self::DYNAMIC_FILE_RENAME, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Dynamic File Rename')),*/
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::STATIC_QUERY_STRING => Mage::helper('mhidalgo_staticversion')->__('Static Query String after Url'),
            self::DYNAMIC_QUERY_STRING => Mage::helper('mhidalgo_staticversion')->__('Dynamic Query String after Url'),
            /*self::STATIC_FILE_RENAME => Mage::helper('mhidalgo_staticversion')->__('Static File Rename'),
            self::DYNAMIC_FILE_RENAME => Mage::helper('mhidalgo_staticversion')->__('Dynamic File Rename'),*/
        );
    }

}