<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    private RouterInterface $router;
    private UserProviderInterface $userProvider;

    public function __construct(RouterInterface $router, UserProviderInterface $userProvider)
    {
        $this->router = $router;
        $this->userProvider = $userProvider;
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->router->generate('login');
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->request->get('username', '');

        if (empty($username)) {
            throw new CustomUserMessageAuthenticationException('Nom d\'utilisateur non trouvé.');
        }

        // Vérifier si l'utilisateur existe
        try {
            $user = $this->userProvider->loadUserByIdentifier($username);
        } catch (AuthenticationException $e) {
            throw new CustomUserMessageAuthenticationException('Nom d\'utilisateur non trouvé.');
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->request->get('password', ''))
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('task_list'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        return new RedirectResponse($this->router->generate('login'));
    }
}
