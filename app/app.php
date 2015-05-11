<?php

require_once __DIR__ .'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use LCQD\App\Provider\AppProvider;

$app = new Silex\Application();
$app['debug'] = true;

// Register Twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/Resources/views',
    'twig.options' => array(
        'cache' => __DIR__ . '/../var/cache'
    )
));

// Register Url Generator
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
/** Renault HFE application **/
$app->register(new AppProvider(), array());

return $app;
