<?php

namespace AppBundle\Controller\FrontEnd;

use AppBundle\Entity\Post;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PostController extends BaseController
{
    /**
     * @Route("/th-mosaic/{id}/{slug}", name="introduce_post")
     */
    public function introduceAction($id, $slug)
    {
        $locale = $this->getLocale();
        $em = $this->getDoctrine()->getEntityManager();

        // Get static post by id
        $post = $em->getRepository('AppBundle:Post')->find($id);

        if ($post)
        {
            $post->setLocale($locale);

            // replace this example code with whatever you need
            return $this->render('frontend/intro_post.html.twig', array('POST' => $post));
        }
        else
            return $this->redirectToRoute('homepage');
    }
}