<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\User;
use BileMoBundle\Form\LoginType;
use BileMoBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/login", name="bile_mo_login_page")
     */
    public function loginAction()
    {
        $loginForm = $this->createForm(LoginType::class, ['username' => $this->get('security.authentication_utils')->getLastUsername()]);

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
     * @Route("/register", name="bile_mo_register_page")
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

            return $this->redirectToRoute('bile_mo_login_page', ['id' => $user->getId()]);
        }

        return $this->render('@BileMo/Default/register.html.twig', ['user' => $user, 'registerForm' => $form->createView()]);
    }
}