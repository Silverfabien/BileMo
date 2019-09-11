<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\User;
use BileMoBundle\Form\ChangePseudoType;
use BileMoBundle\Form\LoginType;
use BileMoBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/login", name="bile_mo_login")
     */
    public function loginAction()
    {
        $loginForm = $this->createForm(LoginType::class,
            ['username' => $this->get('security.authentication_utils')->getLastUsername()]
        );

        return $this->render('@BileMo/Default/login.html.twig', ['loginForm' => $loginForm->createView()]);
    }

    /**
     * @Route("/logout", name="bile_mo_logout")
     * @throws \Exception
     */
    public function logoutAction()
    {
        throw new \Exception('This should not be reached !');
    }

    /**
     * @Route("/register", name="bile_mo_register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $formRegister = $this->getDoctrine()->getManager();
            $formRegister->persist($user);
            $formRegister->flush();

            return $this->redirectToRoute('bile_mo_login', ['id' => $user->getId()]);
        }

        return $this->render('@BileMo/Default/register.html.twig',
            ['user' => $user, 'registerForm' => $form->createView()]
        );
    }

    /**
     * @Route("/account/{username}", name="bile_mo_account_page")
     */
    public function accountAction(Request $request, User $user)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "You must be logged in to access this page !");

        /** PARTIE EDIT */

        $editForm = $this->createForm(ChangePseudoType::class, $user);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('bile_mo_account_page', ['username' => $user->getUsername()]);
        }

        /** Regénération du ApiKey */
        $regenerateApiKeyForm = $this->createFormBuilder()->getForm();
        $regenerateApiKeyForm->handleRequest($request);

        if($regenerateApiKeyForm->isSubmitted() && $regenerateApiKeyForm->isValid())
        {
            $user->generateApiKey();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bile_mo_account_page', ['username' => $user->getUsername()]);
        }

        return $this->render('@BileMo/Default/account.html.twig',
            ['edit_form' => $editForm->createView(), 'regenerate_api_key' => $regenerateApiKeyForm->createView()]
        );
    }
}