<?php

namespace AppBundle\Controller\FrontEnd;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Extensions\MosaicConstant;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $locale = $this->getLocale();

        $data = array();
        $em = $this->getDoctrine()->getManager();


        // Get construction consulting
        $tmp = $em->createQuery('SELECT p.name_en, p.name_vi, p.img FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_CONSTRUCTION_CONSULTING)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->img = $tmp['img'];
        $post->setLocale($locale);
        $data[MosaicConstant::POST_ID_CONSTRUCTION_CONSULTING] = $post;

        // Get product as require
        $tmp = $em->createQuery('SELECT p.name_en, p.name_vi, p.img FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_PRODUCT_AS_REQUIRE)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->img = $tmp['img'];
        $post->setLocale($locale);
        $data[MosaicConstant::POST_ID_PRODUCT_AS_REQUIRE] = $post;

        // Get guarantee
        $tmp = $em->createQuery('SELECT p.name_en, p.name_vi, p.img FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_GUARANTEE_POLICY)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->img = $tmp['img'];
        $post->setLocale($locale);
        $data[MosaicConstant::POST_ID_GUARANTEE_POLICY] = $post;

        $projects = $this->getProjectGroup();

        return $this->render('frontend/index.html.twig', array('DATA_CONTENT' => $data, 'PROJECT_GROUPS' => $projects));
    }

    /**
     * @Route("/in-studio", name="in_studio")
     */
    public function inStudioAction()
    {
        return $this->render('frontend/inStudio.html.twig', array());
    }


    /**
     * @Route("/tile", name="mosaic_tile_list")
     */
    public function tileAction()
    {
        $projects = $this->getProjectGroup();

        return $this->render('frontend/category.html.twig', array(
            'CAT_TYPE' => MosaicConstant::TILE,
            'PROJECT_GROUPS' => $projects
        ));
    }

    /**
     * @Route("/mosaic", name="mosaic_art_list")
     */
    public function mosaicAction()
    {
        $projects = $this->getProjectGroup();

        return $this->render('frontend/category.html.twig', array(
            'CAT_TYPE' => MosaicConstant::MOSAIC,
            'PROJECT_GROUPS' => $projects
        ));
    }

    /**
     * @Route("/category/{id}/{slug}", name="category_detail")
     */
    public function categoryDetailAction($id, $slug)
    {
        if ($id == 1)
        {
            $projects = $this->getProjectGroup();

            return $this->render('frontend/category_detail.html.twig', array('CAT_ID' => $id, 'PROJECT_GROUPS' => $projects));
        }
        else
        {
            return $this->redirectToRoute('product_list', array('catId' => $id));
        }
    }


}
