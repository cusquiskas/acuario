<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    $link = new ConexionSistema();
    
    $filtro = [
        0 => ['tipo' => 'i', 'dato' => $_POST['ORDEN']],  
        1 => ['tipo' => 'i', 'dato' => $_POST['PRUEBA']],
        2 => ['tipo' => 'i', 'dato' => $_POST['COMPETICION']],
    ];  
    $datosSerieAbierta = $link->consulta ( '  SELECT ABIERTA
                                               FROM MRC_SERIE
                                              WHERE ORDEN       = ?
                                                AND PRUEBA      = ?
                                                AND COMPETICION = ?;', $filtro);
    
    if ($link->hayError()) {
        die(json_encode(['success' => true, 'root' => $link->getListaErrores()]));
    }
    
    $link->close();
    unset($link);

    echo json_encode(['success' => true, 'root' => ($datosSerieAbierta[0]["ABIERTA"] == 2)?true:false]);

?>