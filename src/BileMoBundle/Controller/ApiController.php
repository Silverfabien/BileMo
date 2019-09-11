<?php

namespace BileMoBundle\Controller;

use BileMoBundle\Exception\ResourceValidationException;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Swagger\Annotations as SWG;

class ApiController extends Controller
{
    /**
     * Creating the token via the ApiKey in the User table
     *
     * Recovery of the ApiKey in the User table and put in the X-AUTH-TOKEN header
     * for the generation of the Token valid for a duration of 1 hour
     *
     * @Route("/api/token", name="bile_mo_api_token", methods={"GET"})
     *
     * @SWG\Parameter(
     *     name="X-AUTH-TOKEN",
     *     in="header",
     *     type="string",
     *     description="Generating the Token from ApiKey"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Token",
     *     @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="jwtToken", type="string")
     *     )
     * )
     *
     * @SWG\Tag(name="Authentication")
     */
    public function newTokenAction(UserInterface $user, JWTTokenManagerInterface $jwtManager)
    {
        return new JsonResponse(['jwtToken' => $jwtManager->create($user)]);
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