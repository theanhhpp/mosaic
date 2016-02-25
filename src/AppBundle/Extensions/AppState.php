<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 10/26/2015
 * Time: 10:29 PM
 */
namespace AppBundle\Extensions;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Post;
use AppBundle\Utility\DeviceDetector;


class AppState
{
    protected $session;
    protected $em;
    protected $defaultLocale;

    function __construct(Session $session, EntityManager $em, $defaultLocale = 'en')
    {
        $this->session = $session;
        $this->em = $em;
        $this->defaultLocale = $defaultLocale;
    }

    public function getCommonData()
    {
        $data = array();
        $locale = '';

        // process locale
        if ($this->session && $this->session->get('_locale'))
        {
            $locale = $this->session->get('_locale');
        }
        else
        {
            $locale = $this->defaultLocale;
        }
        $data[MosaicConstant::LOCALE] = $locale;

        // current url
        $data[MosaicConstant::CURRENT_DOMAIN] = 'http://'.$_SERVER['HTTP_HOST'];

        // get categories
        $tiles = array();
        $mosaics = array();

        $categories = $this->em->getRepository('AppBundle:Category')->findAll();
        if ($categories)
        {
            foreach($categories as $category)
            {
                $category->setLocale($locale);
                if ($category->category_type == MosaicConstant::TILE)
                    $tiles[] = $category;
                else
                    $mosaics[] = $category;
            }
        }
        $data[MosaicConstant::TILE] = $tiles;
        $data[MosaicConstant::MOSAIC] = $mosaics;

        // Get static post
        $static_post = array();

        // Get introduce post
        $tmp = $this->em->createQuery('SELECT p.name_en, p.name_vi, p.short_desc_en, p.short_desc_vi FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_INTRODUCE)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->short_desc_en = $tmp['short_desc_en'];
        $post->short_desc_vi = $tmp['short_desc_vi'];
        $post->setLocale($locale);
        $static_post[MosaicConstant::POST_ID_INTRODUCE] = $post;

        // Get delivery policy
        $tmp = $this->em->createQuery('SELECT p.name_en, p.name_vi FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_DELIVERY_POLICY)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->setLocale($locale);
        $static_post[MosaicConstant::POST_ID_DELIVERY_POLICY] = $post;

        // Get factory tour
        $tmp = $this->em->createQuery('SELECT p.name_en, p.name_vi FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_FACTORY_TOUR)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->setLocale($locale);
        $static_post[MosaicConstant::POST_ID_FACTORY_TOUR] = $post;

        // Get why choose us
        $tmp = $this->em->createQuery('SELECT p.name_en, p.name_vi FROM AppBundle\Entity\Post p where p.id = '.MosaicConstant::POST_ID_WHY_CHOOSE_US)->getResult()[0];
        $post = new Post();
        $post->name_en = $tmp['name_en'];
        $post->name_vi = $tmp['name_vi'];
        $post->setLocale($locale);
        $static_post[MosaicConstant::POST_ID_WHY_CHOOSE_US] = $post;

        $data[MosaicConstant::POST_STATIC] = $static_post;


        // process device
        $detect = new DeviceDetector();
        if ($detect->isTablet())
        {
            $data[MosaicConstant::DEVICE] = MosaicConstant::DEVICE_TABLET;
        }
        else if ($detect->isMobile())
        {
            $data[MosaicConstant::DEVICE] = MosaicConstant::DEVICE_MOBILE;
        }
        else
        {
            $data[MosaicConstant::DEVICE] = MosaicConstant::DEVICE_DESKTOP;
        }


        // get color
        $color = array();

        $colors = $this->em->getRepository('AppBundle:Color')->findAll();
        if ($colors)
        {
            foreach($colors as $co)
            {
                $co->setLocale($locale);
                $color[] = $co;
            }
        }
        $data[MosaicConstant::MOSAIC_COLOR] = $color;


        // get Glaze
        $glaze =array();

        $glazes = $this->em->getRepository('AppBundle:Glaze')->findAll();
        if ($glazes)
        {
            foreach($glazes as $gla)
            {
                $gla->setLocale($locale);
                $glaze[] = $gla;
            }
        }
        $data[MosaicConstant::MOSAIC_GLAZE] = $glaze;


        // get Pattern
        $pattern =array();

        $patterns = $this->em->getRepository('AppBundle:Pattern')->findAll();
        if ($patterns)
        {
            foreach($patterns as $pat)
            {
                $pat->setLocale($locale);
                $pattern[] = $pat;
            }
        }
        $data[MosaicConstant::MOSAIC_PATTERN] = $pattern;


        // get size
        $data[MosaicConstant::MOSAIC_SIZE] =  $this->em->getRepository('AppBundle:Size')->findAll();

        return $data;
    }
}