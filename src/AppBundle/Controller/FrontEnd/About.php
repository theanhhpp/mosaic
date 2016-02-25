<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 12/14/2015
 * Time: 4:24 PM
 */
namespace AppBundle\Controller\FrontEnd;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Enquiry;
use AppBundle\Form\EnquiryType;

class About extends Controller{
    /**
     * @Route("/contact-us", name="contact_us")
     */

   /* public function contactUsAction(Request $request)
    {
        $enquiry = new Enquiry();
        $form = $this->createForm(new EnquiryType(), $enquiry);

        $form->handleRequest($request);
        if ($request->getMethod() == 'POST') {


            if ($form->isValid()) {
                // Perform some action, such as sending an email
                $mailer = $this->get('mailer');
                $message = $mailer->createMessage()
                    ->setSubject('test TH-mosaic')
                    ->setFrom('testnghiep@gmail.com')
                    ->setTo('nghiepnt94@gmail.com')
                    ->setBody($this->renderView('Mail/contactEmail.txt.twig', array('enquiry' => $enquiry)));

                $mailer->send($message);

                $this->get('session')->getFlashBag()->set('send-notice', 'contact.us.sendmail');

                // Redirect - This is important to prevent users re-posting
                // the form if they refresh the page
                return $this->redirect($this->generateUrl('contact_us'));

            }
        }

        return $this->render('frontend/contactUs.html.twig', array(
            'form' => $form->createView()
        ));
    } */

   public function contactUsAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createFormBuilder($contact)
            ->add('name', 'text')
            ->add('email', 'text')
            ->add('subject', 'text')
            ->add('body', 'textarea')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);

            $em->flush();
            $this->get('session')->getFlashBag()->set('send-notice', 'contact.us.sendmail');

            return $this->redirectToRoute('contact_us');
        }


        return $this->render('frontend/contactUs.html.twig', array('form' => $form->createView()
        ));
    }
}