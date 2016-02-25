<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:51 PM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Glaze;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class GlazeController extends Controller
{
    /**
     * @Route("/admin/glaze/index", name="admin_glaze_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Glaze')->findAll();

        return $this->render('backend/glaze_list.html.twig', array('GLAZES' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_glaze_list',));
    }


    /**
     * @Route("/admin/glaze/delete/{id}", name="admin_glaze_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $glaze = $em->getRepository('AppBundle:Glaze')->find($id);

        if (!$glaze) {
            return $this->redirectToRoute('admin_glaze_list');
        }

        $form = $this->createFormBuilder($glaze)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($glaze);

            $em->flush();

            return $this->redirectToRoute('admin_glaze_list');
        }


        return $this->render('backend/glaze_delete.html.twig', array('message' => $message, 'GLAZE' => $glaze,'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/glaze/add", name="admin_glaze_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $glaze = new Glaze();

        $form = $this->createFormBuilder($glaze)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('img', 'hidden')
            ->add('img_file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($glaze);

            $em->flush();

            return $this->redirectToRoute('admin_glaze_list');
        }

        return $this->render('backend/glaze_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }


    /**
     * @Route("/admin/glaze/edit/{id}", name="admin_glaze_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $glaze = $em->getRepository('AppBundle:Glaze')->find($id);

        if (!$glaze) {
            return $this->redirectToRoute('admin_glaze_list');
        }

        $form = $this->createFormBuilder($glaze)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/glaze_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));


    }
}