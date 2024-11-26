<?php
/*
 * Oasys Digital Signage
 * 
 * 
 */
define('PROJECT_ROOT', realpath(__DIR__ . '/..'));
require_once PROJECT_ROOT . '/vendor/autoload.php';

$twig = new Twig_Environment(new \Twig\Loader\FilesystemLoader([PROJECT_ROOT . '/views', PROJECT_ROOT . '/modules']), [
    'cache'       => PROJECT_ROOT . '/cache',
    'auto_reload' => true
]);

$twig->addExtension(new \Slim\Views\TwigExtension());
$twig->addExtension(new \Xibo\Twig\TransExtension());
$twig->addExtension(new \Xibo\Twig\ByteFormatterTwigExtension());
$twig->addExtension(new \Xibo\Twig\UrlDecodeTwigExtension());
$twig->addExtension(new \Xibo\Twig\DateFormatTwigExtension());


foreach (glob(PROJECT_ROOT . '/views/*.twig') as $file) {
    echo var_export($file, true) . PHP_EOL;

    $twig->loadTemplate(str_replace(PROJECT_ROOT . '/views/', '', $file));
}
foreach (glob(PROJECT_ROOT . '/modules/*.twig') as $file) {
    echo var_export($file, true) . PHP_EOL;

    $twig->loadTemplate(str_replace(PROJECT_ROOT . '/modules/', '', $file));
}