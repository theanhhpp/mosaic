<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:51 PM
 */

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CategoryController extends Controller
{
    /**
     * @Route("/admin/category/index", name="admin_category_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->getRepository('AppBundle:Category')->findAll();

        return $this->render('backend/category_list.html.twig', array('CATEGORIES' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_category_list'));
    }


    /**
     * @Route("/admin/category/edit/{id}", name="admin_category_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')->find($id);

        if (!$category) {
            return $this->redirectToRoute('admin_category_list');
        }

        $form = $this->createFormBuilder($category)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('category_type','hidden')
            ->add('seo_title_en', 'text')
            ->add('seo_title_vi', 'text')
            ->add('seo_meta_vi', 'text')
            ->add('seo_meta_en','text')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))

            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/category_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));


    }
}
