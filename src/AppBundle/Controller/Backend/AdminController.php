<?php

namespace AppBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\ChangePassword;


class AdminController extends Controller
{
    /**
     * @Route("/admin/changepassword", name="admin_change_pwd")
     */
    public function changePasswdAction(Request $request)
    {
        $message = '';
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        $admin = $em->getRepository('AppBundle:User')->find($user->id);

        $changePasswordModel = new ChangePassword();

        $form = $this->createFormBuilder($changePasswordModel)
            ->add('currentPassword', 'password')
            ->add('newPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'New Password'),
                'second_options' => array('label' => 'Confirm Password'),
            ))
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action,
            // such as encoding with MessageDigestPasswordEncoder and persist

            $password = $this->get('security.password_encoder')
                ->encodePassword($admin,$changePasswordModel->getnewPassword());
            $admin->setPassword($password);
            $em->flush();

            $message = $this->get('translator')->trans("message.change_password_success", array(), 'admin');
        }


        return $this->render('backend/change_pwd.html.twig', array(
            'form' => $form->createView(),
            'message'=> $message
        ));
    }
}
