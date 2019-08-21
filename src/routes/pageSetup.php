<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


// GET AUTOCOMPLETE
$app->get('/api/menus', function (Request $request, Response $response)  use ($app){
$validate = new validation();
$user = $validate->APIAccessValidation($this->token);

$user_rollID=$user["idRol"];

    $sql = "SELECT * FROM DynamicMenu where MENU_PARENT =''
     and OBJECT_ID in (select menuItemID from menuAccess where rolID =$user_rollID)";
    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();
        $stmt = $db->query($sql);
        $menuItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($menuItems as $key => $value)
        {
          $sql = 'SELECT * FROM DynamicMenu where MENU_PARENT ="'. $menuItems[$key]['MENU_ID'].'"
          and OBJECT_ID in (select menuItemID from menuAccess where rolID ='.$user_rollID.')' ;
          $stmt = $db->query($sql);
          $menuItems[$key]['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
         // $menuItems[$key]['items'] = $sql ;
        }

        $db = null;
        $jsonResponse = array();
        $jsonResponse["status"] = "true";
        $jsonResponse["message"] = "true";
        $jsonResponse["data"] = $menuItems;
        echo json_encode($jsonResponse);
    } catch (PDOException $e) {
        echo '{"error": {"text": "' . $e->getMessage() . '"}';
    }
});

$app->any('/api/test',function(){
    echo 'test';
});