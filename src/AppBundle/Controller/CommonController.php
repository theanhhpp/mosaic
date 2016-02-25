<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommonController extends Controller
{
    /**
     * @Route("/lang/{locale}", name="common_lang")
     */
    public function langAction($locale)
    {
        $this->get('session')->set('_locale', $locale);

        $uri = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
        if(!$uri)
            $uri = '/';

        return $this->redirect($uri);
    }
}
