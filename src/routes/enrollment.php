<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/membership', function (Request $request, Response $response) {

    $id = $request->getParam('id');
    $email = $request->getParam('email');

    $membershipSQL = 'SELECT * FROM `users` WHERE rfid = ? and email = ?';

    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($membershipSQL);
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $email);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $responseJson = (object) [
                'status' => false,
                'message' => 'Membership not found.'
            ];
            echo json_encode($responseJson);
            exit;
        } else {
          
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['mem_processing']==true || !isset($row['mem_processing'])){
                        $responseJson = (object) [
                            'status' => false,
                            'message' => 'Your Membership activation process has already started, please verify your email.'.$row['mem_processing'],
                        ];
                        echo json_encode($responseJson);
                        exit;
                    }
                    $responseJson = (object) [
                        'status' => true,
                        'message' => 'Membership found.',

                        'name' => $row['nombre'],
                        'lastname' => $row['apellido'],
                        'birthdate' => $row['fecha_nacimiento'],
                        'phone' => $row['telefono'],
                        'email' => $row['email'],
                        'cName' => $row['poc_name'],
                        'cPhone' => $row['poc_phone'],

                    ];
                    return $response->withJson(json_encode($responseJson));
                    exit;
                }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return $response->withJson(json_encode($GLOBALS['errorResponse']));
    }
});
