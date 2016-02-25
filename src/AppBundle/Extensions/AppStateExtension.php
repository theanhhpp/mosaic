<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 10/26/2015
 * Time: 10:39 PM
 */
namespace AppBundle\Extensions;

use AppBundle\Extensions\AppState;

class AppStateExtension extends \Twig_Extension
{
    protected $appState;

    function __construct(AppState $appState) {
        $this->appState = $appState;
    }

    public function getGlobals() {
        return array(
            'appstate' => $this->appState
        );
    }

    public function getName()
    {
        return 'appstate';
    }
}