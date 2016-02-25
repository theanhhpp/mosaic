<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:51 PM
 */

namespace AppBundle\Controller\Backend;

use AppBundle\Entity\Pattern;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PatternController extends Controller
{
    /**
     * @Route("/admin/pattern/index", name="admin_pattern_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Pattern')->findAll();

        return $this->render('backend/pattern_list.html.twig', array('PATTERNS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_pattern_list'));
    }


    /**
     * @Route("/admin/pattern/delete/{id}", name="admin_pattern_delete")
     */
    public function deleteAction($id, Request $request) {

        $message = '';
        $em = $this->getDoctrine()->getManager();

        $pattern = $em->getRepository('AppBundle:Pattern')->find($id);

        if (!$pattern) {
            return $this->redirectToRoute('admin_pattern_list');
        }

        $form = $this->createFormBuilder($pattern)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($pattern);
            $em->flush();

            return $this->redirectToRoute('admin_pattern_list');
        }


        return $this->render('backend/pattern_delete.html.twig', array('message' => $message, 'PATTERN' => $pattern, 'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/pattern/add", name="admin_pattern_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $pattern = new Pattern();

        $form = $this->createFormBuilder($pattern)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('img', 'hidden')
            ->add('img_file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pattern);
            $em->flush();

            return $this->redirectToRoute('admin_pattern_list');
        }

        return $this->render('backend/pattern_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));
    }


    /**
     * @Route("/admin/pattern/edit/{id}", name="admin_pattern_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $pattern = $em->getRepository('AppBundle:Pattern')->find($id);

        if (!$pattern) {
            return $this->redirectToRoute('admin_pattern_list');
        }

        $form = $this->createFormBuilder($pattern)
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

        return $this->render('backend/pattern_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));


    }
}
