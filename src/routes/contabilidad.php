<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;




// Get Reporte Anual
$app->get('/contabilidad/reporteAnual', function (Request $request, Response $response) {
    $ano = $request->getParam('ano');
    $sql = 'SELECT  Months.m AS month, ifnull(sum(datos.gasto),0) AS gastos, ifnull(sum(datos.ingresos),0) as ingresos FROM 
    (
        SELECT 1 as m 
        UNION SELECT 2 as m 
        UNION SELECT 3 as m 
        UNION SELECT 4 as m 
        UNION SELECT 5 as m 
        UNION SELECT 6 as m 
        UNION SELECT 7 as m 
        UNION SELECT 8 as m 
        UNION SELECT 9 as m 
        UNION SELECT 10 as m 
        UNION SELECT 11 as m 
        UNION SELECT 12 as m
    ) as Months
    LEFT JOIN (
    SELECT   Month(fecha) as mes,YEAR(fecha), sum(gasto) as gasto,sum(ingreso)  as ingresos FROM vw_contabilidad 
    WHERE  YEAR(fecha) = '.$ano.'
            GROUP BY Month(fecha),YEAR(fecha))
            as datos on Months.m =datos.mes
            GROUP BY
            Months.m
            order by 
            Months.m';

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($usuarios);
    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});

$app->get('/contabilidad/reporteMensual', function (Request $request, Response $response) {
    $ano = $request->getParam('ano');
    $mes = $request->getParam('mes');
    if($ano == $mes && $ano==null){
        $ano = 2019;
        $mes=3;
    }
    $sql = 'SELECT  fecha,comentario,sum(gasto) as gasto, sum(ingreso) as ingreso FROM vw_contabilidad 
    where MONTH(fecha) = '.$mes.' AND YEAR(fecha) = '.$ano.' 
    group by fecha,comentario
    ORDER BY fecha DESC ';

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $stmt = $db->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo json_encode($usuarios);
    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});