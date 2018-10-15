<?php

namespace BileMoBundle\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/token")
     */
    public function newTokenAction(UserInterface $user, JWTTokenManagerInterface $jwtManager)
    {
        return new JsonResponse(['jwtToken' => $jwtManager->create($user)]);
    }

    /**
     * @Route("/users")
     */
    public function userAction()
    {

    }
}