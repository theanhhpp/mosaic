<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/16/2015
 * Time: 4:05 PM
 */
namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Entity\News;

class NewsController extends Controller
{
    /**
     * @Route("/admin/", name="admin_news_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT n.id , n.name_en, n.name_vi, n.img, n.status FROM AppBundle\Entity\News n ORDER BY n.update_datetime DESC ')->getResult();

        return $this->render('backend/news_list.html.twig', array('NEWS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_news_list'));
    }


    /**
     * @Route("/admin/news/add", name="admin_news_add")
     */
    public function addAction(Request $request)
    {
        $news = new News();
        $news->status = false;

        $form = $this->createFormBuilder($news)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('status','checkbox', array('required' => false))
            ->add('seo_title_en', 'text')
            ->add('seo_title_vi', 'text')
            ->add('seo_desc_en', 'text')
            ->add('seo_desc_vi', 'text')
            ->add('short_desc_en', 'textarea')
            ->add('short_desc_vi', 'textarea')
            ->add('content_en', 'ckeditor', array())
            ->add('content_vi', 'ckeditor', array())
            ->add('img_file', 'file', array('required' => true))
            ->add('img_cover_file', 'file', array('required' => false))
            ->getForm();

        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $news->update_datetime = new \DateTime('now');
            $em->persist($news);

            $em->flush();

            return $this->redirectToRoute('admin_news_list');
        }

        return $this->render('backend/news_add.html.twig', array(
            'form' => $form->createView()
        ));
    }


    /**
     * @Route("/admin/news/edit/{id}", name="admin_news_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('AppBundle:News')->find($id);

        if (!$news) {
            return $this->redirectToRoute('admin_news_list');
        }

        $form = $this->createFormBuilder($news)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('status','checkbox', array('required' => false))
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
            $news->update_datetime = new \DateTime('now');
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/news_edit.html.twig', array(
            'form' => $form->createView(),
            'message' => $message,
        ));

    }


    /**
     * @Route("/admin/news/delete/{id}", name="admin_news_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $news = $em->getRepository('AppBundle:News')->find($id);

        if (!$news) {
            return $this->redirectToRoute('admin_news_list');
        }

        $form = $this->createFormBuilder($news)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($news);

            $em->flush();

            return $this->redirectToRoute('admin_news_list');
        }


        return $this->render('backend/news_delete.html.twig', array('message' => $message, 'NEWS' => $news,'form'=>$form->createView()));
    }

}