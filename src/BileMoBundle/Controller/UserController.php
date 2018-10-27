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

    /**
     * @Route("/account/{username}", name="bile_mo_account_page")
     */
    public function accountAction(Request $request, User $user)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        /** PARTIE EDIT */

        $editForm = $this->createForm(ChangePseudoType::class, $user);
        $editForm->handleRequest($request);

        if($editForm->isSubmitted() && $editForm->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('bile_mo_account_page', ['username' => $user->getUsername()]);
        }

        /** Regération du ApiKey */
        $regenerateApiKeyForm = $this->createFormBuilder()->getForm();
        $regenerateApiKeyForm->handleRequest($request);

        if($regenerateApiKeyForm->isSubmitted() && $regenerateApiKeyForm->isValid())
        {
            $user->generateApiKey();
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bile_mo_account_page', ['username' => $user->getUsername()]);
        }

        /** PARTIE DELETE */

        $deleteForm = $this->createDeleteForm($user);
        $deleteForm->handleRequest($request);

        if($deleteForm->isSubmitted() && $deleteForm->isValid())
        {
            $formDelete = $this->getDoctrine()->getManager();
            session_destroy();
            $formDelete->remove($user);
            $formDelete->flush();

            return $this->redirectToRoute('bile_mo_accueil_page');
        }

        return $this->render('@BileMo/Default/account.html.twig', ['edit_form' => $editForm->createView(), 'delete_form' => $deleteForm->createView(),
            'regenerate_api_key' => $regenerateApiKeyForm->createView()]);
    }

    /**
     * @Route("/liste_des_membres", name="bile_mo_list_member_page")
     */
    public function listUserAction()
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $user = $this->getDoctrine()->getManager()->getRepository(User::class);
        $listMembers = $user->findAll();

        return $this->render('@BileMo/Default/listMember.html.twig', ['listMembers' => $listMembers]);
    }

    /**
     * @Route("/liste_des_membres/{username}", name="bile_mo_user_account_page")
     */
    public function userAccountAction($username)
    {
        #$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, "Vous devez être connecté pour acceder à cette page !");

        $accountUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username' => $username]);

        return $this->render('@BileMo/Default/accountUser.html.twig', ['accountUser' => $accountUser]);
    }

    public function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bile_mo_account_page', ['username' => $user->getUsername()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}