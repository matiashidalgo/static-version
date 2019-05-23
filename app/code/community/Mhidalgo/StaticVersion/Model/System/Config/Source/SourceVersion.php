<?php
/**
 * Class for keep options for version type
 *
 * @category  StaticVersion
 * @package   Mhidalgo_StaticVersion
 * @author    Matias Hidalgo <me@mhidalgo.tk>
 * @copyright Copyright (c) 2017 Matias Hidalgo (http://www.mhidalgo.tk)
 */

class Mhidalgo_StaticVersion_Model_System_Config_Source_SourceVersion
{

    CONST STATICAL = 1;
    CONST DYNAMIC = 2;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::STATICAL, 'label'=> Mage::helper('mhidalgo_staticversion')->__('Static')),
            array('value' => self::DYNAMIC, 'label'=> Mage::helper('mhidalgo_staticversion')->__('Dynamic'))
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
            self::STATICAL => Mage::helper('mhidalgo_staticversion')->__('Static'),
            self::DYNAMIC => Mage::helper('mhidalgo_staticversion')->__('Dynamic'),
        );
    }

}
