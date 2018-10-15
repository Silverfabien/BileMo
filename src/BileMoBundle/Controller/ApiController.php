<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Exception\ResourceValidationException;
use BileMoBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ApiController extends Controller
{
    /**
     * @Route("/api/token", name="connexion_api_page")
     */
    public function newTokenAction(UserInterface $user, JWTTokenManagerInterface $jwtManager)
    {
        return new JsonResponse(['jwtToken' => $jwtManager->create($user)]);
    }

    /**
     * @Route("/users", name="connexion_page")
     */
    public function loginAction()
    {
        $loginForm = $this->createForm(UserType::class, ['username' => $this->get('security.authentication_utils')->getLastUsername()]);

        return $this->render('@BileMo/Default/login.html.twig', ['loginForm' => $loginForm->createView()]);
    }

    /**
     * @Route("/logout", name="deconnexion_page")
     * @throws \Exception
     */
    public function logoutAction()
    {
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
}