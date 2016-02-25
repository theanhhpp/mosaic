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
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductImg;



class ProductController extends Controller
{
    /**
     * @Route("/admin/product/index", name="admin_product_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = array();

        $data = $em->createQuery('SELECT p.id , p.name_en, p.name_vi,p.code, p.img FROM AppBundle\Entity\Product p ')->getResult();

        return $this->render('backend/product_list.html.twig', array('PRODUCTS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_product_list'));
    }


    /**
     * @Route("/admin/product/edit/{id}", name="admin_product_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (!$product) {
            return $this->redirectToRoute('admin_product_list');
        }

        $images = $em->getRepository('AppBundle:ProductImg')->findBy(array('product_row_id' => $product->id));

        $form = $this->createFormBuilder($product)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('code','text')
            ->add('desc_en','textarea', array('required' => false))
            ->add('desc_vi','textarea', array('required' => false))
            ->add('seo_title_en','text', array('required' => false))
            ->add('seo_title_vi','text', array('required' => false))
            ->add('seo_meta_vi','text', array('required' => false))
            ->add('seo_meta_en','text', array('required' => false))
            ->add('deliver_vi','text', array('required' => false))
            ->add('deliver_en','text', array('required' => false))
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->add('category', 'entity', array('class' => 'AppBundle:Category'
                ,'expanded' => false
                ,'multiple' => false
                ,'choice_label' => 'name_en'
                )
            )
            ->add('color', 'entity', array('class' => 'AppBundle:Color'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->add('glaze', 'entity', array('class' => 'AppBundle:Glaze'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->add('pattern', 'entity', array('class' => 'AppBundle:Pattern'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )

            ->add('size', 'entity', array('class' => 'AppBundle:Size'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'size_value'
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/product_edit.html.twig', array(
            'form' => $form->createView(),
            'ProductImages' => $images,
            'message' => $message,
        ));
    }

    /**
     * @Route("/admin/product/add", name="admin_product_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $product = new Product();

        $form = $this->createFormBuilder($product)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('code','text')
            ->add('desc_en','textarea', array('required' => false))
            ->add('desc_vi','textarea', array('required' => false))
            ->add('seo_title_en','text', array('required' => false))
            ->add('seo_title_vi','text', array('required' => false))
            ->add('seo_meta_vi','text', array('required' => false))
            ->add('seo_meta_en','text', array('required' => false))
            ->add('deliver_vi','textarea', array('required' => false))
            ->add('deliver_en','textarea', array('required' => false))
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => true))
            ->add('category', 'entity', array('class' => 'AppBundle:Category'
                ,'expanded' => false
                ,'multiple' => false
                ,'choice_label' => 'name_en'
                )
            )
            ->add('color', 'entity', array('class' => 'AppBundle:Color'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->add('glaze', 'entity', array('class' => 'AppBundle:Glaze'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->add('pattern', 'entity', array('class' => 'AppBundle:Pattern'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->add('size', 'entity', array('class' => 'AppBundle:Size'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'size_value'
                )
            )

            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);

            $em->flush();

            return $this->redirectToRoute('admin_product_edit', array('id' => $product->id));
        }

        return $this->render('backend/product_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message
        ));
    }


    /**
     * @Route("/admin/product/delete/{id}", name="admin_product_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository('AppBundle:Product')->find($id);

        if (!$product) {
            return $this->redirectToRoute('admin_product_list');
        }

         $images = $em->getRepository('AppBundle:ProductImg')->findBy(array('product_row_id' => $product->id));

        $form = $this->createFormBuilder($product)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($product);

            $em->flush();

            return $this->redirectToRoute('admin_product_list');
        }


        return $this->render('backend/product_delete.html.twig', array('message' => $message, 'PRODUCT' => $product, 'ProductImages' => $images, 'form'=>$form->createView()));
    }

    /**
     * @Route("/admin/product/upload", name="admin_product_upload")
     */
    public function uploadAction(Request $request)
    {
        $webPathDir = $this->container->getParameter('product_img');
        $thumbWidth = $this->container->getParameter('product_img_thumb_width');
        $thumbHeight = $this->container->getParameter('product_img_thumb_height');
        $rootDir = realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$webPathDir);

        $em = $this->getDoctrine()->getManager();


        $productImg = new ProductImg();

        $form = $this->createFormBuilder($productImg)
            ->add('product_row_id', 'hidden')
            ->add('file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($productImg->product_row_id > 0)
        {
            $product = $em->getRepository('AppBundle:Product')->find($productImg->product_row_id);

            if ($product)
            {
                if ($productImg->upload($rootDir, $webPathDir, $thumbWidth, $thumbHeight))
                {
                    if ($productImg->img != '' && $productImg->img_thumb != '')
                    {
                        $em->persist($productImg);

                        $em->flush();
                    }
                    else
                        $productImg->id = -2; // Invalid project id
                }
                else
                    $productImg->id = -2; // Invalid project id
            }
            else
                $productImg->id = 0; // Invalid project id
        }
        else
            $productImg->id = 0; // Invalid project id



        $response = new Response();
        $response->setContent(json_encode($productImg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/admin/product/remove_upload/{id}", defaults={"id": 0}, name="admin_product_remove_upload")
     */
    public function removeUploadAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $productImg = new ProductImg();
        $productImg->id = 0; // Invalid project id

        if ($id && $id > 0)
        {
            $productImg = $em->getRepository('AppBundle:ProductImg')->find($id);

            if ($productImg)
            {
                //unlink(realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$projectImg->img));
                //unlink(realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$projectImg->img_thumb));

                $em->remove($productImg);
                $em->flush();

                $productImg = new ProductImg();
                $productImg->id = $id;
            }
        }


        $response = new Response();
        $response->setContent(json_encode($productImg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
