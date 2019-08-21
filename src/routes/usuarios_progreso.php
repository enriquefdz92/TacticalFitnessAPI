<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;



// Get All usuarios
$app->get('/usuario/progreso/{id}', function(Request $request, Response $response){
    
    $id = $request->getAttribute('id');
    $sql = "SELECT c.nombre, c.skill_1,c.skill_2,c.skill_3, c.icon, ifnull(u.progresoID,0) as progreso 
FROM APP_Cat_Progreso c left join (SELECT * FROM APP_Usr_Progreso where usrID = $id ) u on  c.id= u.progresoID";
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        $jsonResponse = array();
        $jsonResponse["status"]="true";
        $jsonResponse["message"]="true";
        $jsonResponse["data"]=$usuarios;
        echo json_encode($jsonResponse);
    } catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}';
    }
});
 