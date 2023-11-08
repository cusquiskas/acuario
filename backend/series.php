<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    
    $link = new ConexionSistema();
    
    
    $filtro = [
        0 => ['tipo' => 'i', 'dato' => $_POST['COMPETICION']]  
    ];  
    $datosSerieAbierta = $link->consulta( "SELECT DISTINCT ORDEN,
                                        PRUEBA, ABIERTA
                                   FROM MRC_SERIE
                                  WHERE COMPETICION = ? 
                                    AND ABIERTA < 2
                                  ORDER 
                                     BY ABIERTA DESC, 
                                        ORDEN ASC
                                  LIMIT 1;", $filtro);
    
    if ($link->hayError()) {
        die(json_encode(['success' => true, 'root' => $link->getListaErrores()]));
    }
    $datosSerieAbierta = $datosSerieAbierta[0];
    $filtro = [
        0 => ['tipo' => 'i', 'dato' => $_POST['COMPETICION']],
        1 => ['tipo' => 'i', 'dato' => $datosSerieAbierta['PRUEBA']],
        2 => ['tipo' => 'i', 'dato' => $datosSerieAbierta['ORDEN']]        
    ];  
    $listado = $link->consulta( "SELECT CALLE,
                                        NADADOR,
                                        CLUB,
                                        CONCAT(TIEMPO DIV 60, ':', LPAD(TIEMPO MOD 60, 5, '0')) 'TIEMPO'
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

    $resultado = [];
    $resultado["NADADORES"] = $listado;

    $manejador = ControladorDinamicoTabla::set('MRC_COMPETICION');
    if ($manejador->give(["ID" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["COM_NOMBRE"]  = $listado[0]["NOMBRE" ];
    $resultado["COM_PISCINA"] = $listado[0]["PISCINA"];

    unset($manejador);

    $manejador = ControladorDinamicoTabla::set('MRC_PRUEBA');
    if ($manejador->give(["ID" => $datosSerieAbierta['PRUEBA'], "COMPETICION" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["PRU_ORDEN"    ] = $listado[0]["ORDEN"    ];
    $resultado["PRU_ESTILO"   ] = $listado[0]["ESTILO"   ];
    $resultado["PRU_DISTANCIA"] = $listado[0]["DISTANCIA"];
    $resultado["SER_ORDEN"    ] = $datosSerieAbierta["ORDEN"];
    $resultado["SER_ABIERTA"  ] = $datosSerieAbierta["ABIERTA"];

    unset($manejador);


    echo json_encode(['success' => true, 'root' => $resultado]);

    
?>