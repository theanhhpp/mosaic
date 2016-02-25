<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/18/2015
 * Time: 3:54 PM
 */

namespace AppBundle\Controller\FrontEnd;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Project;



class ProjectController extends BaseController
{
    /**
     * @Route("/project", name="project_list")
     */
    public function indexAction()
    {
        //get project_group
        $projects = $this->getProjectGroup();

        return $this->render('frontend/project.html.twig', array('PROJECT_GROUPS' => $projects));
    }


    /**
     * @Route("/project/{id}/{slug}", name="project_details")
     */
    public function detailAction($id, $slug)
    {
        $locale = $this->getLocale();
        $em = $this->getDoctrine()->getManager();

        $projectGroup = $em->getRepository('AppBundle:ProjectGroup')->find($id);

        if (!$projectGroup)
            return $this->redirectToRoute('project_list');

        $projectGroup->setLocale($locale);
        $maxInColumn = 0;
        $offSetColumn = 0;

        if ($projectGroup->projects)
        {
            foreach($projectGroup->projects as $p)
            {
                $p->setlocale($locale);
                $p->images = $em->getRepository('AppBundle:ProjectImg')->findBy(array('project_row_id' => $p->id));
            }

            $maxInColumn = floor(count($projectGroup->projects) / 3);
            $offSetColumn = count($projectGroup->projects) % 3;
        }


        return $this->render('frontend/project_detail.html.twig', array('PROJECT_DETAILS' => $projectGroup, 'maxInColumn' => $maxInColumn, 'offSetColumn' => $offSetColumn));
    }
}