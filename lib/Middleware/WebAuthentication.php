<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Middleware;


use Slim\Middleware;
use Xibo\Helper\ApplicationState;

/**
 * Class WebAuthentication
 * @package Xibo\Middleware
 */
class WebAuthentication extends Middleware
{
    /**
     * Uses a Hook to check every call for authorization
     * Will redirect to the login route if the user is unauthorized
     *
     * @throws \RuntimeException if there isn't a login route
     */
    public function call()
    {
        $app = $this->app;

        // Create a user
        $app->user = $app->userFactory->create();

        // Create a function which we will call should the request be for a protected page
        // and the user not yet be logged in.
        $redirectToLogin = function () use ($app) {
            // Do we need SSL/STS?
            $whiteListLoadBalancers = $app->configService->getSetting('WHITELIST_LOAD_BALANCERS');
            $originIp = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            $forwardedProtoHttps = (
                strtolower($app->request()->headers('HTTP_X_FORWARDED_PROTO', 'http')) === 'https'
                && $originIp != ''
                && (
                    $whiteListLoadBalancers === '' || in_array($originIp, explode(',', $whiteListLoadBalancers))
                )
            );

            if ($app->request()->getScheme() == 'https' || $forwardedProtoHttps) {
                if ($app->configService->getSetting('ISSUE_STS', 0) == 1)
                    $app->response()->header(
                        'strict-transport-security',
                        'max-age=' . $app->configService->getSetting('STS_TTL', 600)
                    );
            }

            if ($app->request->isAjax()) {
                $state = $app->state;
                /* @var ApplicationState $state */
                // Return a JSON response which tells the App to redirect to the login page
                $app->response()->header('Content-Type', 'application/json');
                $state->Login();
                echo $state->asJson();
                $app->stop();
            }
            else {
                // Redirect to login
                $app->redirect($app->urlFor('login'));
            }
        };

        // Define a callable to check the route requested in before.dispatch
        $isAuthorised = function () use ($app, $redirectToLogin) {
            /** @var \Xibo\Entity\User $user */
            $user = $app->user;

            // Get the current route pattern
            $resource = $app->router->getCurrentRoute()->getPattern();

            // Pass the page factory into the user object, so that it can check its page permissions
            $user->setChildAclDependencies($app->userGroupFactory, $app->pageFactory);

            // Check to see if this is a public resource (there are only a few, so we have them in an array)
            if (!in_array($resource, $app->publicRoutes)) {
                $app->public = false;

                // Need to check
                if ($user->hasIdentity() && !$app->session->isExpired()) {

                    // Replace our user with a fully loaded one
                    $user = $app->userFactory->getById($user->userId);

                    // Pass the page factory into the user object, so that it can check its page permissions
                    $user->setChildAclDependencies($app->userGroupFactory, $app->pageFactory);

                    // Load the user
                    $user->load();

                    // Configure the log service with the logged in user id
                    $app->logService->setUserId($user->userId);

                    // Do they have permission?
                    $user->routeAuthentication($resource);

                    // We are authenticated, override with the populated user object
                    $app->user = $user;
                }
                else {
                    // Store the current route so we can come back to it after login
                    $app->flash('priorRoute', $app->request()->getRootUri() . $app->request()->getResourceUri());

                    if ($user->hasIdentity()) {
                        $user->touch();
                    }

                    $redirectToLogin();
                }
            }
            else {
                $app->public = true;

                // If we are expired and come from ping/clock, then we redirect
                if ($app->session->isExpired() && ($resource == '/login/ping' || $resource == 'clock')) {

                    if ($user->hasIdentity()) {
                        $user->touch();
                    }

                    $redirectToLogin();
                }
            }
        };

        $app->hook('slim.before.dispatch', $isAuthorised);

        $this->next->call();
    }
}