<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */


use Xibo\Service\ConfigService;

DEFINE('XIBO', true);
define('PROJECT_ROOT', realpath(__DIR__ . '/..'));

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

error_reporting(0);
ini_set('display_errors', 0);


 // I included these headers at the top of my header.php file that is included everywhere in my website files.


// header("X-XSS-Protection: 1; mode=block");
// header("X-Frame-Options: SAMEORIGIN");
// header('X-Content-Type-Options: nosniff');
//header("content_security_policy: default-src 'self' style-src 'self' 'unsafe-inline';");

//header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:;media-src 'self' blob:;script-src 'self' 'unsafe-inline' 'unsafe-eval'; object-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");

// <meta http-equiv="Content-Security-Policy" content="default-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">

ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', 'On');
ini_set('session.cookie_httponly', 'On');

header("Cache-Control: no-cache, no-store, pre-check=0, post-check=0, max-age=0, s-maxage=0, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); //HTTP 1.0.
header("Expires: 0"); //Proxies

// Hide X-Powered-By with expose_php 

header('Server: ');

// Remove X-Powered-By header:

header('X-Powered-By: ');

//Remove X-Generator header (if using a framework):

header('X-Generator: ');



header("Content-Security-Policy: default-src 'self'; img-src 'self' data: blob:;media-src 'self' blob:;script-src 'self' 'unsafe-inline' 'unsafe-eval'; object-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");


// Cross-Origin Resource Sharing (CORS)
header("Access-Control-Allow-Origin: *");

// X-Frame-Options
header("X-Frame-Options: SAMEORIGIN");

// X-Content-Type-Options
header("X-Content-Type-Options: nosniff");

// X-XSS-Protection
header("X-XSS-Protection: 1; mode=block");

// Strict-Transport-Security (HSTS)
//header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

// Referrer-Policy
header("Referrer-Policy: no-referrer");

header('Feature-Policy: geolocation \'self\'; microphone \'none\'; camera \'none\'');



// Disable TRACE method
if ($_SERVER['REQUEST_METHOD'] == 'TRACE') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit;
}

$allowed_hosts = ['demods.oasys.co', 'demods.oasys.co'];
if (!in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
    header("HTTP/1.1 400 Bad Request");
    exit;
}


require PROJECT_ROOT . '/vendor/autoload.php';

// Should we show the installer?
if (!file_exists('settings.php')) {
    // Check to see if the install app is available
    if (file_exists(PROJECT_ROOT . '/web/install/index.php')) {
        header('Location: install/');
        exit();
    } else {
        // We can't do anything here - no install app and no settings file.
        die('Not configured');
    }
}

// Create a logger
$logger = new \Xibo\Helper\AccessibleMonologWriter(array(
    'name' => 'WEB',
    'handlers' => [
        new \Xibo\Helper\DatabaseLogHandler()
    ],
    'processors' => array(
        new \Xibo\Helper\LogProcessor(),
        new \Monolog\Processor\UidProcessor(7)
    )
), false);

// Slim Application
$app = new \RKA\Slim(array(
    'debug' => false,
    'log.writer' => $logger
));
$app->setName('web');

// Twig templates
$twig = new \Slim\Views\Twig();
$twig->parserOptions = array(
    'debug' => true,
    'cache' => PROJECT_ROOT . '/cache'
);
$twig->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new \Xibo\Twig\TransExtension(),
    new \Xibo\Twig\ByteFormatterTwigExtension(),
    new \Xibo\Twig\UrlDecodeTwigExtension(),
    new \Xibo\Twig\DateFormatTwigExtension()
);

// Configure the template folder
$twig->twigTemplateDirs = [PROJECT_ROOT . '/views'];

$app->view($twig);

// Config
$app->configService = ConfigService::Load(PROJECT_ROOT . '/web/settings.php');

//
// Middleware (onion, outside inwards and then out again - i.e. the last one is first and last);
//
$app->add(new \Xibo\Middleware\Actions());

// Theme Middleware
$app->add(new \Xibo\Middleware\Theme());

// Authentication middleware
if ($app->configService->authentication != null && $app->configService->authentication instanceof \Slim\Middleware)
    $app->add($app->configService->authentication);
else
    $app->add(new \Xibo\Middleware\WebAuthentication());

// Standard Xibo middleware
$app->add(new \Xibo\Middleware\CsrfGuard());
$app->add(new \Xibo\Middleware\State());
$app->add(new \Xibo\Middleware\Storage());
$app->add(new \Xibo\Middleware\Xmr());

// Handle additional Middleware
\Xibo\Middleware\State::setMiddleWare($app);
//
// End Middleware
//

// Configure the Slim error handler
$app->error(function (\Exception $e) use ($app) {
    $app->container->get('\Xibo\Controller\Error')->handler($e);
});

// Configure a not found handler
$app->notFound(function () use ($app) {
    $app->container->get('\Xibo\Controller\Error')->notFound();
});

// All application routes
require PROJECT_ROOT . '/lib/routes-web.php';
require PROJECT_ROOT . '/lib/routes.php';

// Run App
try {
    $app->run();
}
catch (Exception $e) {
    echo 'Fatal Error - sorry this shouldn\'t happen. ';
    echo $e->getMessage();
}
