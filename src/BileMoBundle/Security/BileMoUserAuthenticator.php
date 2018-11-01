<?php

namespace BileMoBundle\Security;

use BileMoBundle\Form\LoginType;
use BileMoBundle\Form\UserType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class BileMoUserAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    /**
     * @var UserPasswordEncoder
     */
    private $encoder;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager, FormFactoryInterface $formFactory, UserPasswordEncoder $encoder, RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->formFactory = $formFactory;
        $this->encoder = $encoder;
        $this->router = $router;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     *
     * @throws AuthenticationException
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        if(!$this->encoder->isPasswordValid($user, $credentials['password']))
        {
            throw new CustomUserMessageAuthenticationException('Mauvais mot de passe');
        }

        return true;
    }

    /**
     * @param Request $request
     * @return mixed|null
     */
    public function getCredentials(Request $request)
    {
        $loginForm = $this->formFactory->create(LoginType::class);
        $loginForm->handleRequest($request);

        if($loginForm->isSubmitted() && $loginForm->isValid())
        {
            $data = $loginForm->getData();
            dump($data);
            $request->getSession()->set(Security::LAST_USERNAME, $data['username']);

            return $data;
        }

        return;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return null|UserInterface
     *
     * @throws AuthenticationException
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $userProvider->loadUserByUsername($credentials['username']);
    }

    /**
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if($request->getSession() instanceof SessionInterface)
        {
            $request->getSession()->getFlashBag()->add('danger', $exception->getMessage());
        }

        return new RedirectResponse($this->router->generate('bile_mo_login_page'));
    }

    /**
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if($request->getSession() instanceof SessionInterface)
        {
            $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
        }
        if($targetPath === null)
        {
            $targetPath = $this->router->generate('bile_mo_homepage');
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('bile_mo_login_page'));
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return true;
    }
}