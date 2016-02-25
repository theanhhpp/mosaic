<?php

namespace AppBundle\Controller\FrontEnd;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;

class ProductController extends BaseController
{
    /**
     * @Route("/product-list/{catId}/{type}/{typeId}/{search}", defaults={"catId": 0, "type": 0, "typeId": 0, "search": ""}, name="product_list")
     */
    public function indexAction($catId, $type, $typeId, $search)
    {
        $locale = $this->getLocale();

        $em = $this->getDoctrine()->getEntityManager();

        //get product
        $products =array();

        $data = $em->getRepository('AppBundle:Product')->findAll();

        if ($data)
        {
            foreach($data as $dat)
            {
                $dat->setLocale($locale);
                $dat->category->setLocale($locale);

                $products[] = $dat;
            }
        }

        // get product glaze
        $query = $em->getConnection()->prepare('SELECT glaze_id, product_id FROM tbl_product_glaze order by glaze_id');
        $query->execute();
        $productGlaze = $query->fetchAll();

        // get product pattern
        $query = $em->getConnection()->prepare('SELECT pattern_id, product_id FROM tbl_product_pattern order by pattern_id');
        $query->execute();
        $productPattern = $query->fetchAll();

        // get product color
        $query = $em->getConnection()->prepare('SELECT color_id, product_id FROM tbl_product_color order by color_id');
        $query->execute();
        $productColor = $query->fetchAll();

        // get product size
        $query = $em->getConnection()->prepare('SELECT size_id, product_id FROM tbl_product_size order by size_id');
        $query->execute();
        $productSize = $query->fetchAll();

        // replace this example code with whatever you need
        return $this->render('frontend/product_list.html.twig'
                            , array(
                                'PRODUCTS' => $products
                                ,'PRODUCTS_STRING' => json_encode($products)
                                ,'PRODUCTS_GLAZE' => json_encode($productGlaze)
                                ,'PRODUCTS_PATTERN' => json_encode($productPattern)
                                ,'PRODUCTS_COLOR' => json_encode($productColor)
                                ,'PRODUCTS_SIZE' => json_encode($productSize)
                                ,'FILTER_CATEGORY' => $catId
                                ,'FILTER_TYPE' => $type
                                ,'FILTER_TYPEID' => $typeId
                                ,'FILTER_KEYWORD' => $search
                            ));
    }


    /**
     * @Route("/product/{id}/{slug}", name="product_detail")
     */
    public function detailAction($id, $slug)
    {
        $locale = $this->getLocale();
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (!$product)
            return $this->redirectToRoute('product_list');

        $product->setLocale($locale);
        foreach($product->color as $co)
            $co->setLocale($locale);

        $images = $em->getRepository('AppBundle:ProductImg')->findBy(array('product_row_id' => $product->id));


        // get related product
        $relatedProducts= $em->getRepository('AppBundle:Product')->findBy(array('category' => $product->category));

        $data = array();
        foreach($relatedProducts as $pro)
        {
            if ($pro->id != $product->id)
            {
                $pro->setLocale($locale);
                $data[] = $pro;
            }

            if (count($data) == 5)
                break;
        }


        // replace this example code with whatever you need
        return $this->render('frontend/product.html.twig', array('PRODUCT' => $product, 'IMAGES' => $images, 'RELATED_PRODUCT' => $data));
    }


    /**
     * @Route("/search", name="product_search")
     */
    public function searchAction(Request $request)
    {
        $keyword = $request->query->get('txtKeyword');
        $type = $request->query->get('hdType');
        $val = $request->query->get('hdVal');

        if ($type == 0)
            return $this->redirectToRoute('product_list', array('search' => $keyword));
        else if ($type == 1)
            return $this->redirectToRoute('product_list', array('catId' => $val));
        else
            return $this->redirectToRoute('product_list', array('type' => $type, 'typeId' => $val));

    }
}
