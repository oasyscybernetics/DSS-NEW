<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


namespace Xibo\Middleware;

use League\OAuth2\Server\ResourceServer;
use Slim\Middleware;
use Xibo\Exception\AccessDeniedException;

class ApiAuthenticationOAuth extends Middleware
{
    public function call()
    {
        $app = $this->app;

        // oAuth Resource
        $sessionStorage = new \Xibo\Storage\ApiSessionStorage($app->store);
        $accessTokenStorage = new \Xibo\Storage\ApiAccessTokenStorage($app->store);
        $clientStorage = new \Xibo\Storage\ApiClientStorage($app->store);
        $scopeStorage = new \Xibo\Storage\ApiScopeStorage($app->store);

        $server = new \League\OAuth2\Server\ResourceServer(
            $sessionStorage,
            $accessTokenStorage,
            $clientStorage,
            $scopeStorage
        );

        // DI in the server
        $app->server = $server;

        $isAuthorised = function() use ($app) {
            // Validate we are a valid auth
            /* @var ResourceServer $server */
            $server = $this->app->server;

            $server->isValidRequest(false);

            /* @var \Xibo\Entity\User $user */
            $user = null;

            // What type of access has been requested?
            if ($server->getAccessToken()->getSession()->getOwnerType() == 'user')
                $user = $app->userFactory->getById($server->getAccessToken()->getSession()->getOwnerId());
            else
                $user = $app->userFactory->loadByClientId($server->getAccessToken()->getSession()->getOwnerId());

            $user->setChildAclDependencies($app->userGroupFactory, $app->pageFactory);

            $user->load();

            // Block access by retired users.
            if ($user->retired === 1) {
                throw new AccessDeniedException('Sorry this account does not exist or cannot be authenticated.');
            }

            $this->app->user = $user;

            // Get the current route pattern
            $resource = $app->router->getCurrentRoute()->getPattern();

            // Allow public routes
            if (!in_array($resource, $app->publicRoutes)) {
                $app->public = false;

                // Do they have permission?
                $this->app->user->routeAuthentication(
                    $resource,
                    $app->request()->getMethod(),
                    $server->getAccessToken()->getScopes()
                );
            } else {
                $app->public = true;
            }
        };

        $app->hook('slim.before.dispatch', $isAuthorised);

        // Call the next middleware
        $this->next->call();
    }
}