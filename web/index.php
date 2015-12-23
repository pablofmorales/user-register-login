<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

\date_default_timezone_set('America/Los_Angeles');

include '../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app['view'] = $app->share(function () use ($app) {
    return new Guzzle\Http\Client();
});

$app['session'] = $app->share(function () use ($app) {
    $session = new Session();
    $session->start();
    return $session;
});

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\DoctrineServiceProvider(),
    ['db.options' => include '../src/Config/credentials.php']
);


$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => '../src/Views',
]);

$app['users.model'] = $app->share(function () use ($app) {
    return new Models\Users($app['db']);
});

$app['auth.validator'] = $app->share(function () use ($app) {
    return new Models\Validators\Authentication($app['validator'], $app['users.model']);
});

$app['users.validator'] = $app->share(function () use ($app) {
    return new Models\Validators\Users($app['validator'], $app['users.model']);
});

$app['auth'] = $app->share(function () use ($app) {
    return new Controllers\Authentication(
        $app['twig'], $app['auth.validator'], $app['users.model'], $app['session']
    );
});

$app['users'] = $app->share(function () use ($app) {
    return new Controllers\Users(
        $app['twig'], $app['users.validator'], $app['users.model']
    );
});

$app->get('/login', 'auth:login');
$app->post('/login', 'auth:login');

$app->get('/users', 'users:search');

$app->get('/signup', 'users:create');
$app->post('/signup', 'users:create');

$app->run();
