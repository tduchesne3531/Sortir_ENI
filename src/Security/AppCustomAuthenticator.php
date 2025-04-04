<?php

namespace App\Security;

use App\Repository\UserRepository;
use Exception;
use phpDocumentor\Reflection\Location;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppCustomAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private readonly UrlGeneratorInterface $urlGenerator, private readonly UserRepository $userRepository
    )
    {

    }

    public function authenticate(Request $request): Passport
    {
        $identifier = $request->request->get('identifier');
        $password = $request->request->get('password');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $identifier);

        return new Passport(
            // Ajout de la possibilité de se connecter avec email ou pseudo
            new UserBadge($identifier, function ($identifier) {
                return $this->userRepository->findOneBy(['email' => $identifier])
                    ?? $this->userRepository->findOneBy(['pseudo' => $identifier]);
            }),
            new PasswordCredentials($password),
            [
                // Empeche les attaques de type Cross Site Request Forgery
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                // Ajout d'un token RememberMe (si demandé lors de la connexion)
                new RememberMeBadge(),
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->urlGenerator->generate('app_home'));

    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
