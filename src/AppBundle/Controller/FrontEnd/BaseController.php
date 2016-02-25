<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/12/2015
 * Time: 5:47 PM
 */
namespace AppBundle\Controller\FrontEnd;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends Controller
{
    public function getLocale()
    {
        $locale = $this->container->getParameter('locale');
        if ($this->get('session') && $this->get('session')->get('_locale'))
        {
            $locale = $this->get('session')->get('_locale');
        }

        return $locale;
    }


    public  function getProjectGroup()
    {
        $locale = $this->getLocale();

        $em = $this->getDoctrine()->getEntityManager();

        //get project_group
        $projects =array();

        $pro_grs = $em->getRepository('AppBundle:ProjectGroup')->findAll();
        if ($pro_grs)
        {
            foreach($pro_grs as $pro)
            {
                $pro->setLocale($locale);
                $projects[] = $pro;
            }
        }

        return $projects;
    }
}