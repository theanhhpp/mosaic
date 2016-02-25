<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/17/2015
 * Time: 10:49 AM
 */

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PostController extends Controller
{
    /**
     * @Route("/admin/intro/index", name="admin_intro_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT p.id , p.name_en, p.name_vi, p.img FROM AppBundle\Entity\Post p ')->getResult();


        return $this->render('backend/post_list.html.twig', array('POSTS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_intro_list'));
    }

    /**
     * @Route("/admin/intro/edit/{id}", name="admin_intro_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('AppBundle:Post')->find($id);

        if (!$post) {
            return $this->redirectToRoute('admin_intro_list');
        }

        $form = $this->createFormBuilder($post)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('seo_title_en', 'text')
            ->add('seo_title_vi', 'text')
            ->add('seo_desc_en', 'text')
            ->add('seo_desc_vi', 'text')
            ->add('short_desc_en', 'textarea')
            ->add('short_desc_vi', 'textarea')
            ->add('content_en', 'ckeditor', array())
            ->add('content_vi', 'ckeditor', array())
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

        return $this->render('backend/post_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));

    }
}