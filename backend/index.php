<?php
require_once 'vendor/autoload.php';
$app = new Silex\Application();
$dotenv = new Dotenv\Dotenv('.');
$dotenv->load();
$app->register(new JDesrosiers\Silex\Provider\CorsServiceProvider(), [
    "cors.allowOrigin" => "*",
]);
$app['debug']= true;
$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});
$app->mount('/ebay', include 'ebay.php');
$app["cors-enabled"]($app);
$app->run();