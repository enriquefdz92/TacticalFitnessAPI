<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



$app->group('/myMembership', function ($app) {
    $app->get('/', function ($request, $response) {
        $validate = new validation($this->app, $request);
        echo $validate->APIAccessValidation()["idRol"];
        $jsonResponse = array();
        $responseCode = 200;
        $jsonResponse["status"] = true;

        // Route for /users/{id:[0-9]+}/reset-password
        // Reset the password for user identified by $args['id']
        // return $response->withJson($user, $responseCode);
    });
});
