<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    $resultado = [];

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
    
    $link->close();
    unset($link);

    $datosSerieAbierta = $datosSerieAbierta[0];
    
    
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

    unset($manejador);

    $manejador = ControladorDinamicoTabla::set('MRC_SERIE');
    if ($manejador->give(["ORDEN" => $datosSerieAbierta['ORDEN'], "CALLE" => $_POST['CALLE'], "PRUEBA" => $datosSerieAbierta['PRUEBA'], "COMPETICION" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["SER_ORDEN"  ] = $listado[0]["ORDEN"  ];
    $resultado["SER_NADADOR"] = $listado[0]["NADADOR"];
    $resultado["SER_CLUB"   ] = $listado[0]["CLUB"   ];

    unset($manejador);
    
    echo json_encode(['success' => true, 'root' => $resultado]);

?>