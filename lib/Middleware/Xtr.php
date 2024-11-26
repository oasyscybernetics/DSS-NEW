<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */

namespace Xibo\Middleware;


use Slim\Middleware;

/**
 * Class Xtr
 *  Middleware for XTR.
 *   - sets the theme
 *   - sets the module theme files
 * @package Xibo\Middleware
 */
class Xtr extends Middleware
{
    public function call()
    {
        // Inject our Theme into the Twig View (if it exists)
        $app = $this->getApplication();

        $app->configService->loadTheme();

        $app->hook('slim.before.dispatch', function() use($app) {
            // Provide the view path to Twig
            $twig = $app->view()->getInstance()->getLoader();
            /* @var \Twig_Loader_Filesystem $twig */

            // Append the module view paths
            $twig->setPaths(array_merge($app->moduleFactory->getViewPaths(), [PROJECT_ROOT . '/views', PROJECT_ROOT . '/reports', PROJECT_ROOT . '/custom']));

            // Does this theme provide an alternative view path?
            if ($app->configService->getThemeConfig('view_path') != '') {
                $twig->prependPath(str_replace_first('..', PROJECT_ROOT, $app->configService->getThemeConfig('view_path')));
            }
        });

        // Call Next
        $this->next->call();
    }
}