<?php
class validation
{
    private $app;
    private $request;
    private $loggedUser;
    function __construct($app, $request)
    {
        $this->app = $app;
        $this->request = $request;
    }
    public function getRoute()
    {
        return $this->request->getAttribute('route')->getPattern();
    }
    public function APIAccessValidation()
    {
        $token = $this->app->token;
        if ($token == null) {
            echo '{"error": {"status":"false", "message": "Token not provided"}';
            exit;
        }
        $sql = "select u.* from API_TOKENS t, users u where t.userID= u.id and replace(token,'Bearer ','')='" . $token . "'";
        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->prepare($sql);
            // $stmt->bindParam(":name", "bob");
            $stmt->execute();
            $this->loggedUser = new user();
            $stmt->setFetchMode(PDO::FETCH_CLASS, '/models/user');
            $dataRow = $stmt->fetch();
            if ($dataRow == null || $dataRow == "") {
                echo '{"error": {"status":"false", "message": "API Access denied"}';
                exit;
            } else {
                $this->loggedUser->set($dataRow);
                return $this->loggedUser;
            }
        } catch (PDOException $e) {
            echo '{"error": {"text": "Validation:' . $e->getMessage() . '"}';
            exit;
        }
    }

    private function validateRouteAccess()
    {
        $hasAccess = false;
        $sql='SELECT * FROM `API_ROUTE_ACESS` WHERE ROUTE = ?
        AND (ROLE_ID = ? OR USER_ID = ?';
        
        
        try {
            // Get DB Object
            $db = new db();
            // Connect
            $db = $db->connect();

            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $this->getRoute());
            $stmt->bindParam(2, $this->loggedUser->idRol);
            $stmt->execute();
            $loggedUser = new user();
            $stmt->setFetchMode(PDO::FETCH_CLASS, '/models/user');
            $dataRow = $stmt->fetch();
            if ($dataRow == null || $dataRow == "") {
                echo '{"error": {"status":"false", "message": "API Access denied"}';
                exit;
            } else {
                $loggedUser->set($dataRow);
                return $loggedUser;
            }
        } catch (PDOException $e) {
            echo '{"error": {"text": "Validation:' . $e->getMessage() . '"}';
            exit;
        }
    }
}
