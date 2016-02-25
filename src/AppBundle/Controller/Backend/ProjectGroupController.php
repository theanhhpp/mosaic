<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 11/24/2015
 * Time: 5:01 PM
 */
namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ProjectGroupController extends Controller
{
    /**
     * @Route("/admin/projectgroup/index", name="admin_project_group_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT p.id , p.name_en, p.name_vi, p.img FROM AppBundle\Entity\ProjectGroup p ')->getResult();


        return $this->render('backend/project_group_list.html.twig', array('PROJECT_GROUPS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_project_group_list'));
    }

    /**
     * @Route("/admin/projectgroup/edit/{id}", name="admin_project_group_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('AppBundle:ProjectGroup')->find($id);

        if (!$project) {
            return $this->redirectToRoute('admin_intro_list');
        }

        $form = $this->createFormBuilder($project)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('seo_title_en', 'text')
            ->add('seo_title_vi', 'text')
            ->add('seo_meta_en', 'text')
            ->add('seo_meta_vi', 'text')
            ->add('desc_en', 'textarea')
            ->add('desc_vi', 'textarea')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->add('img_cover', 'hidden')
            ->add('img_cover_file', 'file', array('required' => false))
            ->getForm();

        $form->handleRequest($request);



        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/project_group_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));


    }
}