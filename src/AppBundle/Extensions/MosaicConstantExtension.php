<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 10/29/2015
 * Time: 5:17 PM
 */
namespace AppBundle\Extensions;

use AppBundle\Extensions\MosaicConstant;

class MosaicConstantExtension extends \Twig_Extension
{
    public function getGlobals()
    {
        $class = new \ReflectionClass('AppBundle\Extensions\MosaicConstant');
        $constants = $class->getConstants();

        return array(
            'MosaicConstant' => $constants
        );
    }

    public function getName()
    {
        return 'mosaic_constant';
    }
}