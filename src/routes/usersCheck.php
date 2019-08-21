<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET AUTOCOMPLETE
$app->get('/usuarios/usersCheck/autocomplete', function(Request $request, Response $response){
    $sql = "select  rfid as id, concat(nombre,' ',apellido) as label from users where rfid is not null";
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

// GET TODAYS ASSISTANCE
$app->get('/usuarios/usersCheck/todaysAssistance', function(Request $request, Response $response){
    $sql = "select 
    a.rfid as id, 
    concat(nombre,' ', apellido) as nombre,
    IF((u.`days_overdue` = 0), 'Vence <strong>HOY</strong>', 
    IF((u.`days_overdue` > 0), CONCAT('Vence en ', ABS(u.`days_overdue`), ' días'), 
    CONCAT('Venció hace ', ABS(u.`days_overdue`), ' días'))) AS `membresia`,
    u.picRoute as img,
    IF((u.`days_overdue` >= 0), 'membresia-activa', 'membresia-inactiva') AS `style`,
    IF((u.`days_overdue` >= 0), 'Membresía Activa', 'Membresía Vencida') AS `messageModal`,
    IF((u.`days_overdue` >= 0), '', 'vencida') AS `class`,
    IF((u.`days_overdue` >= 0), 1, 0) AS `active`
     from asistencia a, vw_users u  
     where a.userID = u.id and 
     DATE_FORMAT(fecha, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d') 
     order by idasistencia asc";
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

// GET AUTOCOMPLETE
$app->get('/usuarios/usersCheck/setAssistance/{rfid}', function(Request $request, Response $response){
    $id = $request->getAttribute('rfid');
    $sql = 'call sp_asistencia("' . $id . '")'; 
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $sql = "select 
        a.rfid as id, 
        concat(nombre,' ', apellido) as nombre,
        IF((u.`days_overdue` = 0), 'Vence <strong>HOY</strong>', 
        IF((u.`days_overdue` > 0), CONCAT('Vence en ', ABS(u.`days_overdue`), ' días'), 
        CONCAT('Venció hace ', ABS(u.`days_overdue`), ' días'))) AS `membresia`,
        u.picRoute as img,
        IF((u.`days_overdue` >= 0), 'membresia-activa', 'membresia-inactiva') AS `style`,
        IF((u.`days_overdue` >= 0), 'Membresía Activa', 'Membresía Vencida') AS `messageModal`,
        IF((u.`days_overdue` >= 0), '', 'vencida') AS `class`,
        IF((u.`days_overdue` >= 0), 1, 0) AS `active`
         from asistencia a, vw_users u  
         where a.userID = u.id and 
         u.rfid=".$id. " and
         DATE_FORMAT(fecha, '%Y-%m-%d') = DATE_FORMAT(NOW(), '%Y-%m-%d') 
         order by idasistencia asc";
        $db= null;
        $stmt=null;
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
        exit;
    }
});

// GET AUTOCOMPLETE
$app->get('/usuarios/usersCheck/setAssistance2/{rfid}', function(Request $request, Response $response){
    $id = $request->getAttribute('rfid');
    $sql = 'call sp_asistencia("' . $id . '")'; 
    try{
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
         $stmt = $db->query($sql);
         $usuarios = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $db = null;
        $jsonResponse = array();
        $jsonResponse["status"]="true";
        $jsonResponse["message"]="true";
        $jsonResponse["data"]=$usuarios;
        echo json_encode($jsonResponse);
    } catch(PDOException $e){
        echo '{"error": {"text": "'.$e->getMessage().'"}';
        exit;
    }
});








