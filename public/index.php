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
$errorResponse = (object) [
    'status' => false,
    'message' => 'An error occured on our side, please contact one of the administrators. '
];
$GLOBALS['errorResponse'] = $errorResponse;
$app->token = $token;
$app->add(function (Request $request, Response $response, $next) {
    if($request->getMethod() !== 'OPTIONS') {
        return $next($request, $response);
    }

    $response = $response->withHeader('Access-Control-Allow-Origin', 'localhost');
    $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
    $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));

    return $next($request, $response);
});

require '../src/routes/usersCheck.php';
require '../src/routes/athletes.php';
require '../src/routes/pageSetup.php';
require '../src/routes/usuarios_progreso.php';
require '../src/routes/login.php';
require '../src/routes/enrollment.php';
$app->any('/phpinfo', function (Request $request, Response $response) {
    echo phpinfo(); ;
});
$app->run();
