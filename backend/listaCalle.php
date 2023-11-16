<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    $manejador = ControladorDinamicoTabla::set('MRC_COMPETICION');
    if ($manejador->give(["ID" => $_GET["COMPETICION"]]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();
    $resultado = [];
    for ($i = ($listado[0]["CALLES"]==10)?0:1; $i <= $listado[0]["CALLES"]-($listado[0]["CALLES"]==10?1:0); $i++) {
        $resultado[] = ["NOMBRE" => "Calle $i", "ID" => "$i"];
    }
    echo json_encode(['success' => true, 'root' => $resultado]);

    unset($manejador);

?>