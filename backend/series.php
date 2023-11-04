<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    
    $link = new ConexionSistema();
    
    $filtro = [
        0 => ['tipo' => 'i', 'dato' => $_POST['COMPETICION']],
        1 => ['tipo' => 'i', 'dato' => $_POST['PRUEBA']],
        2 => ['tipo' => 'i', 'dato' => $_POST['ORDEN']]        
    ];  
    $listado = $link->consulta( "SELECT CALLE,
                                        NADADOR,
                                        CLUB,
                                        CONCAT(TIEMPO DIV 60, ':', TIEMPO MOD 60) 'TIEMPO'
                                   FROM MRC_SERIE
                                  WHERE COMPETICION = ? 
                                    AND PRUEBA = ?
                                    AND ORDEN = ?
                                  ORDER 
                                     BY CALLE;", $filtro);
    if ($link->hayError()) {
        die(json_encode(['success' => true, 'root' => $link->getListaErrores()]));
    }
    $link->close();
    unset($link);

    echo json_encode(['success' => true, 'root' => $listado]);


?>