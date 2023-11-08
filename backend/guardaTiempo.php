<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    $manejador = ControladorDinamicoTabla::set('MRC_SERIE');
    if ($manejador->give(["ORDEN" => $_POST['ORDEN'], "CALLE" => $_POST['CALLE'], "PRUEBA" => $_POST['PRUEBA']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();
    $listado[0]["TIEMPO"] = $_POST['TIEMPO'];
    if ($manejador->save($listado[0]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    unset($manejador);
    
    echo json_encode(['success' => true, 'root' => 'guardado']);

?>