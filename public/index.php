<?php
header("Content-Type: application/json; charset=UTF-8");
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

$errorResponse = (object) [
    'status' => false,
    'message' => 'An error occured on our side, please contact one of the administrators. '
];
$GLOBALS['errorResponse'] = $errorResponse;
if (isset(apache_request_headers()["Authorization"])){
    // acciones
    $token =str_replace("Bearer ","",apache_request_headers()["Authorization"]);
    $app->token = $token;
} else {
    // otras acciones
}
$app->add(function (Request $request, Response $response, $next) {
    if($request->getMethod() !== 'OPTIONS') {
        return $next($request, $response);
    }
    $response = $response->withHeader('Access-Control-Allow-Origin', '68.70.164.3');
    $response = $response->withHeader('Access-Control-Allow-Methods', $request->getHeaderLine('Access-Control-Request-Method'));
    $response = $response->withHeader('Access-Control-Allow-Headers', $request->getHeaderLine('Access-Control-Request-Headers'));
    $response = $response->withHeader('Content-Type', 'application/json; charset=UTF-8');

    return $next($request, $response);
});

require '../src/routes/usersCheck.php';
require '../src/routes/membership.php';
require '../src/routes/pageSetup.php';
require '../src/routes/usuarios_progreso.php';
require '../src/routes/login.php';
require '../src/routes/enrollment.php';
$app->any('/phpinfo', function (Request $request, Response $response) {
    echo phpinfo(); ;
});
$app->run();
