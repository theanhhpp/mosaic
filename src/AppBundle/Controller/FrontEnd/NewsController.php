<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/3/2015
 * Time: 11:31 AM
 */

namespace AppBundle\Controller\FrontEnd;

use AppBundle\Entity\News;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Extensions\MosaicConstant;
use Symfony\Component\Validator\Constraints\DateTime;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Doctrine\ORM\Tools\Pagination\Paginator;


class NewsController extends BaseController
{
    /**
     * @Route("/news-list/{page}", defaults={"page" = 1}, name="news_list")
     */
    public function newsAction($page)
    {
        // replace this example code with whatever you need
        $locale = $this->getLocale();
        $limit = 7;
        $from = ($page - 1) * $limit;
        $em = $this->getDoctrine()->getManager();
        $totalPage = 1;

        $query = $em->createQuery('SELECT count(n.id) FROM AppBundle\Entity\News n WHERE n.status = 1');
        $total = $query->getSingleScalarResult();
        if ($total < $from or $total > ($from + $limit))
        {
            $page = 1;
            $from = 0;
        }

        if ($total % $limit == 0)
            $totalPage = floor($total / $limit);
        else
            $totalPage = floor ($total / $limit) + 1;

        $query = $em->createQuery('SELECT n.id, n.name_vi, n.name_en, n.short_desc_en, n.short_desc_vi, n.img FROM AppBundle\Entity\News n WHERE n.status = 1 ORDER BY n.update_datetime DESC')->setFirstResult($from)->setMaxResults($limit);
        $news = $query->getResult();

        $data = array();

        foreach($news as $n)
        {
            $tmp = new News();

            $tmp->id = $n['id'];
            $tmp->img = $n['img'];
            $tmp->name_en = $n['name_en'];
            $tmp->name_vi = $n['name_vi'];
            $tmp->short_desc_en = $n['short_desc_en'];
            $tmp->short_desc_vi = $n['short_desc_vi'];

            $tmp->setLocale($locale);

            $data[] = $tmp;
        }

        return $this->render('frontend/news.html.twig',array('NEWS'=> $data, 'page'=> $page, 'totalPage'=> $totalPage));
    }


    /**
     * @Route("/news/{id}/{slug}", name="news_detail")
     */
    public function detailAction($id, $slug)
    {
        $locale = $this->getLocale();
        $em = $this->getDoctrine()->getEntityManager();

        // Get static post by id
        $news = $em->getRepository('AppBundle:News')->find($id);

        if ($news and $news->status)
        {
            $news->setLocale($locale);

            // replace this example code with whatever you need
            return $this->render('frontend/news_detail.html.twig', array('NEWS' => $news));
        }
        else
            return $this->redirectToRoute('news_list');
    }
}