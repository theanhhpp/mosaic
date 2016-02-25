<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:51 PM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Color;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ColorController extends Controller
{
    /**
     * @Route("/admin/color/index", name="admin_color_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Color')->findAll();

        return $this->render('backend/color_list.html.twig', array('COLORS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_color_list'));
    }


    /**
     * @Route("/admin/color/delete/{id}", name="admin_color_delete")
     */
    public function deleteAction($id, Request $request) {

        $message = '';
        $em = $this->getDoctrine()->getManager();

        $color = $em->getRepository('AppBundle:Color')->find($id);

        if (!$color) {
            return $this->redirectToRoute('admin_color_list');
        }

        $form = $this->createFormBuilder($color)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($color);
            $em->flush();

            return $this->redirectToRoute('admin_color_list');
        }


        return $this->render('backend/color_delete.html.twig', array('message' => $message, 'COLOR' => $color, 'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/color/add", name="admin_color_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $color = new Color();

        $form = $this->createFormBuilder($color)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('color_code', 'text')
            ->add('img', 'hidden')
            ->add('img_file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($color);
            $em->flush();

            return $this->redirectToRoute('admin_color_list');
        }

        return $this->render('backend/color_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }


    /**
     * @Route("/admin/color/edit/{id}", name="admin_color_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $color = $em->getRepository('AppBundle:Color')->find($id);

        if (!$color) {
            return $this->redirectToRoute('admin_color_list');
        }

        $form = $this->createFormBuilder($color)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('color_code', 'text')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/color_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }
}
