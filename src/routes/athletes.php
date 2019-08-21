<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/api/athletes', function (Request $request, Response $response)  use ($app) {
    $validate = new validation($app, $request);
    $loggedUser = $validate->APIAccessValidation();
    $jsonResponse = array();
    $responseCode=200;
 
    try {
        $db = new db();
        $db = $db->connect();
        $sql = "select * from vw_users";
        $stmt = $db->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        $jsonResponse["status"] = "true";
        $jsonResponse["message"] = "route: /athletes";
        $jsonResponse["data"] = $usuarios;
        
    } catch (PDOException $e) {
        $jsonResponse["status"] = "false";
        $jsonResponse["message"] = $e->getMessage();
        $jsonResponse["data"] = "{}";
        $responseCode = 500;
    }

    return $response->withJson($jsonResponse, $responseCode);
});

$app->get('/api/athletes/{id}', function (Request $request, Response $response) use ($app) {

    $validate = new validation($app, $request);
    $validate->APIAccessValidation();
    echo $validate->getRoute();
    exit;
    $sql = "select  * from users where id =" . $request->getAttribute('id');
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuario = $stmt->fetch();
        $db = null;
        $jsonResponse = array();
        
        if($usuario == null){
            $jsonResponse["status"] = false;
            $jsonResponse["message"] = "not found";
            $jsonResponse["data"] = $usuario;
        }else{
            $jsonResponse["status"] = true;
            $jsonResponse["message"] = "found";
            $jsonResponse["data"] = $usuario;
        }
        
        echo json_encode($jsonResponse);
    } catch (PDOException $e) {
        echo '{"error": {"text": "' . $e->getMessage() . '"}';
    }
});
