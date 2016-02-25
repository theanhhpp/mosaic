<?php
/**
 * Created by PhpStorm.
 * User: BUI DUC KHANH
 * Date: 11/23/2015
 * Time: 2:25 PM
 */

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectImg;


class ProjectController extends Controller
{
    /**
     * @Route("/admin/project/index", name="admin_project_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT p.id , p.name_en, p.name_vi, p.img FROM AppBundle\Entity\Project p ')->getResult();


        return $this->render('backend/project_list.html.twig', array('PROJECT_DETAILS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_project_list'));
    }


    /**
     * @Route("/admin/project/add", name="admin_project_add")
     */
    public function addAction(Request $request)
    {
        $message = '';

        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('desc_en','textarea')
            ->add('desc_vi','textarea')
            ->add('img', 'hidden')
            ->add('img_file', 'file')
            ->add('projectGroups', 'entity', array('class' => 'AppBundle:ProjectGroup'
                                                            ,'expanded' => true
                                                            ,'multiple' => true
                                                            ,'choice_label' => 'name_en'
                                                            )
                )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);

            $em->flush();

            return $this->redirectToRoute('admin_project_edit', array('id' => $project->id));
        }

        return $this->render('backend/project_add.html.twig', array(
            'form' => $form->createView(),
            'message' => $message
        ));
    }


    /**
     * @Route("/admin/project/edit/{id}", name="admin_project_edit")
     */
    public function editAction($id, Request $request)
    {
        $message = '';

        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('AppBundle:Project')->find($id);

        if (!$project) {
            return $this->redirectToRoute('admin_project_list');
        }

        $images = $em->getRepository('AppBundle:ProjectImg')->findBy(array('project_row_id' => $project->id));

        $form = $this->createFormBuilder($project)
            ->add('id', 'hidden')
            ->add('name_en', 'text')
            ->add('name_vi', 'text')
            ->add('desc_en','textarea')
            ->add('desc_vi','textarea')
            ->add('img', 'hidden')
            ->add('img_file', 'file', array('required' => false))
            ->add('projectGroups', 'entity', array('class' => 'AppBundle:ProjectGroup'
                ,'expanded' => true
                ,'multiple' => true
                ,'choice_label' => 'name_en'
                )
            )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            $message = $this->get('translator')->trans("message.update_success", array(), 'admin');
        }

        return $this->render('backend/project_edit.html.twig', array(
            'form' => $form->createView(),
            'ProjectImages' => $images,
            'message' => $message,
        ));
    }


    /**
     * @Route("/admin/project/delete/{id}", name="admin_project_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $project = $em->getRepository('AppBundle:Project')->find($id);

        if (!$project) {
            return $this->redirectToRoute('admin_project_list');
        }

        $images = $em->getRepository('AppBundle:ProjectImg')->findBy(array('project_row_id' => $project->id));

        $form = $this->createFormBuilder($project)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($project);

            $em->flush();

            return $this->redirectToRoute('admin_project_list');
        }


        return $this->render('backend/project_delete.html.twig', array('message' => $message, 'PROJECT' => $project, 'ProjectImages' => $images, 'form'=>$form->createView()));
    }


    /**
     * @Route("/admin/project/upload", name="admin_project_upload")
     */
    public function uploadAction(Request $request)
    {
        $webPathDir = $this->container->getParameter('project_img');
        $thumbWidth = $this->container->getParameter('project_img_thumb_width');
        $thumbHeight = $this->container->getParameter('project_img_thumb_height');
        $rootDir = realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$webPathDir);

        $em = $this->getDoctrine()->getManager();


        $projectImg = new ProjectImg();

        $form = $this->createFormBuilder($projectImg)
            ->add('project_row_id', 'hidden')
            ->add('file', 'file')
            ->getForm();

        $form->handleRequest($request);

        if ($projectImg->project_row_id > 0)
        {
            $project = $em->getRepository('AppBundle:Project')->find($projectImg->project_row_id);

            if ($project)
            {
                if ($projectImg->upload($rootDir, $webPathDir, $thumbWidth, $thumbHeight))
                {
                    if ($projectImg->img != '' && $projectImg->img_thumb != '')
                    {
                        $em->persist($projectImg);

                        $em->flush();
                    }
                    else
                        $projectImg->id = -2; // Invalid project id
                }
                else
                    $projectImg->id = -2; // Invalid project id
            }
            else
                $projectImg->id = 0; // Invalid project id
        }
        else
            $projectImg->id = 0; // Invalid project id



        $response = new Response();
        $response->setContent(json_encode($projectImg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/admin/project/remove_upload/{id}", defaults={"id": 0}, name="admin_project_remove_upload")
     */
    public function removeUploadAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $projectImg = new ProjectImg();
        $projectImg->id = 0; // Invalid project id

        if ($id && $id > 0)
        {
            $projectImg = $em->getRepository('AppBundle:ProjectImg')->find($id);

            if ($projectImg)
            {
                //unlink(realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$projectImg->img));
                //unlink(realpath($this->get('kernel')->getRootDir().'/../web'.'/'.$projectImg->img_thumb));

                $em->remove($projectImg);
                $em->flush();

                $projectImg = new ProjectImg();
                $projectImg->id = $id;
            }
        }


        $response = new Response();
        $response->setContent(json_encode($projectImg));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}