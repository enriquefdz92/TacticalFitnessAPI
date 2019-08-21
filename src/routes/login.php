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
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($loginSQL);
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $password);
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            echo '{ "status" : "false", "message" : "Wrong User/Password"}';
            exit;
        } else {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userID = $row['id'];
                $newToken="Bearer " . base64_encode(random_bytes(32));
                saveToken($userID, $newToken);
               
            }
        }
    } catch (PDOException $e) {
        echo '{"error": {"text": "' . $e->getMessage() . '"}';
    }
});


function saveToken($user, $token){
    try{
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

                echo '{ "status" : "true", "message" : "Successfuly logged in", "token": "' . $token . '"}';
    } catch(PDOException $e){
        echo '{"error": {"text": "' . $e->getMessage() . '"}';
    }
   
}