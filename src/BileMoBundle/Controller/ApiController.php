<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Entity\User;
use BileMoBundle\Exception\ResourceValidationException;
use BileMoBundle\Form\LoginType;
use BileMoBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ApiController extends Controller
{
    /**
     * @Route("/api/token", name="bile_mo_api_token")
     */
    public function newTokenAction(UserInterface $user, JWTTokenManagerInterface $jwtManager)
    {
        return new JsonResponse(['jwtToken' => $jwtManager->create($user)]);
    }

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
        $this->redirectToRoute('bile_mo_homepage');
        throw new \Exception('This should not be reached !');
    }

    /**
     * @Rest\View(StatusCode = 201)
     * @throws ResourceValidationException
     * @ParamConverter(converter="fos_rest.request_body")
     */
    public function showAction(ConstraintViolationList $violations)
    {
        if (count($violations)) {
            $message = 'The JSON sent contains invalid data. Here are the errors you need to correct: ';
            foreach ($violations as $violation) {
                $message .= sprintf("Field %s: %s ", $violation->getPropertyPath(), $violation->getMessage());
            }

            throw new ResourceValidationException($message);
        }
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