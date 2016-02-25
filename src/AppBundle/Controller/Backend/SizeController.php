<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:51 PM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Size;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SizeController extends Controller
{
    /**
     * @Route("/admin/size/index", name="admin_size_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Size')->findAll();

        return $this->render('backend/size_list.html.twig', array('SIZES' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_size_list',));
    }


    /**
     * @Route("/admin/size/delete/{id}", name="admin_size_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $size = $em->getRepository('AppBundle:Size')->find($id);

        if (!$size) {
            return $this->redirectToRoute('admin_size_list');
        }

        $form = $this->createFormBuilder($size)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($size);

            $em->flush();

            return $this->redirectToRoute('admin_size_list');
        }


        return $this->render('backend/size_delete.html.twig', array('message' => $message, 'SIZE' => $size,'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/size/add", name="admin_size_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $size = new Size();

        $form = $this->createFormBuilder($size)
            ->add('size_value', 'text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($size);

            $em->flush();

            return $this->redirectToRoute('admin_size_list');
        }

        return $this->render('backend/size_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }


    /**
     * @Route("/admin/size/edit/{id}", name="admin_size_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $size = $em->getRepository('AppBundle:Size')->find($id);

        if (!$size) {
            return $this->redirectToRoute('admin_size_list');
        }

        $form = $this->createFormBuilder($size)
            ->add('id', 'hidden')
            ->add('size_value', 'text')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/size_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));


    }
}