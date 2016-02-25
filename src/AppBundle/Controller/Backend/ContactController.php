<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/14/2015
 * Time: 3:40 PM
 */

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContactController extends Controller
{
    /**
     * @Route("/admin/contact/index", name="admin_contact_list")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $data = $em->createQuery('SELECT q.id , q.name, q.subject,q.email FROM AppBundle\Entity\Contact q ')->getResult();


        return $this->render('backend/contact_list.html.twig', array('CONTACTS' => $data, 'SIDEBAR_LINK_CLASS' => 'admin_contact_list'));
    }


    /**
     * @Route("/admin/contact/show/{id}", name="admin_contact_show")
     */
    public function detailsAction($id )
    {
        $em = $this->getDoctrine()->getManager();

       $data = $em->getRepository('AppBundle:Contact')->find($id);

        return $this->render('backend/contact_show.html.twig', array('CONTACTS' => $data));
    }

    /**
     * @Route("/admin/contact/delete/{id}", name="admin_contact_delete")
     */
    public function deleteAction($id, Request $request) {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository('AppBundle:Contact')->find($id);

        if (!$contact) {
            return $this->redirectToRoute('admin_glaze_list');
        }

        $form = $this->createFormBuilder($contact)
            ->add('id','hidden')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($contact);

            $em->flush();

            return $this->redirectToRoute('admin_contact_list');
        }


        return $this->render('backend/contact_delete.html.twig', array('message' => $message, 'CONTACTS' => $contact,'form'=>$form->createView()));
    }
}