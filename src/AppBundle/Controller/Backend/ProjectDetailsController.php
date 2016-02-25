<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/30/2015
 * Time: 1:24 AM
 */
namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\ProjectDetails;


class ProjectDetailsController extends Controller
{
    /**
     * @Route("/admin/projectdetails/index", name="admin_project_details_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT p.id , p.name_en, p.name_vi, p.img FROM AppBundle\Entity\ProjectDetails p ')->getResult();


        return $this->render('backend/projectdetails_list.html.twig', array('PROJECT_DETAILS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_project_details_list'));
    }

    /**
     * @Route("/admin/projectdetails/add", name="admin_projectdetails_add")
     */

    public function addAction(Request $request)
    {

        $message = '';
        $em = $this->getDoctrine()->getManager();
        $data = $em->createQuery('SELECT p.id , p.name_en FROM AppBundle\Entity\Project p ')->getResult();
        $projectdetails = new ProjectDetails();

        $form = $this->createFormBuilder($projectdetails)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('desc_en','textarea')
            ->add('desc_vi','textarea')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projectdetails);

            var_dump($request->request->all());
            exit();
            $em->flush();
            $message = $this->get('translator')->trans("message.add_success", array(), 'admin');
        }

        return $this->render('backend/projectdetails_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
            'PROJECTS' => $data
        ));
    }
}