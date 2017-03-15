<?php
/**
 * Class for keep options for version source
 *
 * @category  StaticVersion
 * @package   Mhidalgo_StaticVersion
 * @author    Matias Hidalgo <me@mhidalgo.tk>
 * @copyright Copyright (c) 2017 Matias Hidalgo (http://www.mhidalgo.tk)
 */

class Mhidalgo_StaticVersion_Model_System_Config_Source_TypeVersion
{

    CONST QUERY_STRING = 1;
    CONST FILE_RENAME = 2;
    CONST CUSTOM = 3;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::QUERY_STRING, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Query String')),
            array('value' => self::FILE_RENAME, 'label'=>Mage::helper('mhidalgo_staticversion')->__('File Rename')),
            array('value' => self::CUSTOM, 'label'=>Mage::helper('mhidalgo_staticversion')->__('Custom'))
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
            self::QUERY_STRING => Mage::helper('mhidalgo_staticversion')->__('Query String'),
            self::FILE_RENAME => Mage::helper('mhidalgo_staticversion')->__('File Rename'),
            self::CUSTOM => Mage::helper('mhidalgo_staticversion')->__('Custom')
        );
    }

}