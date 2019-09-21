<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
// TODO
// INIT LOGIN
// - Validate User
// - Create Token
// - Save last login
// - Return user info
// GET MENU ACCESS 

$app->post('/login', function (Request $request, Response $response) {

    $user = $request->getParam('user');
    $password = $request->getParam('password');
    $loginSQL = 'SELECT * FROM `users` WHERE USRNAME = ? and PSWD = ?';

    try {
        $db = new db();
        $db = $db->connect();
        $stmt = $db->prepare($loginSQL);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $responseJson = (object) [
                'status' => false,
                'message' => 'Wrong User/Password'
            ];
            return $response->withJson( json_encode($responseJson));
            exit;
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userID = $row['id'];
                $newToken = "Bearer " . base64_encode(random_bytes(32));
                return $response->withJson(saveToken($userID, $newToken, $row));
            }
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        return $response->withJson( json_encode($GLOBALS['errorResponse']));
    }
});


function saveToken($user, $token, $data)
{
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $sql = 'INSERT INTO `API_TOKENS`(userID,token,date) values (?,?,now())
                        ON DUPLICATE KEY UPDATE 
                        token=? , date = now()';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $token);
        $stmt->bindParam(3, $token);
        $stmt->execute();


        $responseJson = (object) [
            'status' => true,
            'message' => 'Logged in',
            'token' => $token,
            'nombre' => $data["nombre"],
            'apellido' => $data["apellido"],
            'imgurl' => $data["picRoute"]
        ];
        return $responseJson;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return$GLOBALS['errorResponse'];
    }
}
