<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';
require '../src/config/validate.php';
require '../src/models/user.php';
 

$configuration = [
    'settings' => [
        'displayErrorDetails' => true,
        'determineRouteBeforeAppMiddleware' => true
    ],];
$c = new \Slim\Container($configuration);
$app = new \Slim\App($c);
$token =str_replace("Bearer ","",apache_request_headers()["Authorization"]);
$app->token = $token;

require '../src/routes/usersCheck.php';
require '../src/routes/athletes.php';
require '../src/routes/pageSetup.php';
require '../src/routes/usuarios_progreso.php';
require '../src/routes/login.php';
$app->any('/', function (Request $request, Response $response) {
    echo 'hola';
});
$app->run();
