<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    $resultado = [];
    $datosSerie = [];
    $link = new ConexionSistema();
    
    $filtro = [
        0 => ['tipo' => 'i', 'dato' => $_POST['COMPETICION']]  
    ];  
    $datosSerieAbierta = $link->consulta( '  SELECT MRC_PRUEBA.ORDEN "NUM_PRUEBA",
                                                    MRC_PRUEBA.ID "ID_PRUEBA",
                                                    MRC_PRUEBA.ABIERTA "EST_PRUEBA",
                                                    MRC_SERIE.ORDEN "NUM_SERIE",
                                                    MRC_SERIE.ABIERTA "EST_SERIE"
                                               FROM MRC_PRUEBA,
                                                    MRC_SERIE
                                              WHERE MRC_PRUEBA.ID = MRC_SERIE.PRUEBA
                                                AND MRC_PRUEBA.COMPETICION = MRC_SERIE.COMPETICION
                                                AND MRC_PRUEBA.COMPETICION = ?
                                                AND MRC_SERIE.ABIERTA < 2
                                              ORDER
                                                 BY MRC_PRUEBA.ORDEN,
                                                    MRC_SERIE.ORDEN
                                              LIMIT 1;', $filtro);
    
    if ($link->hayError()) {
        die(json_encode(['success' => true, 'root' => $link->getListaErrores()]));
    }
    
    $link->close();
    unset($link);

    $datosSerie = $datosSerieAbierta[0];
    $resultado["SER_ORDEN" ] = $datosSerie["NUM_SERIE" ];
    $resultado["SER_PRUEBA"] = $datosSerie["NUM_PRUEBA"];
    
    
    $manejador = ControladorDinamicoTabla::set('MRC_COMPETICION');
    if ($manejador->give(["ID" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["COM_NOMBRE"]  = $listado[0]["NOMBRE" ];
    $resultado["COM_PISCINA"] = $listado[0]["PISCINA"];

    unset($manejador);

    $manejador = ControladorDinamicoTabla::set('MRC_PRUEBA');
    if ($manejador->give(["ID" => $datosSerie['ID_PRUEBA'], "COMPETICION" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["PRU_ORDEN"    ] = $listado[0]["ORDEN"    ];
    $resultado["PRU_ESTILO"   ] = $listado[0]["ESTILO"   ];
    $resultado["PRU_DISTANCIA"] = $listado[0]["DISTANCIA"];

    unset($manejador);

    $manejador = ControladorDinamicoTabla::set('MRC_SERIE');
    if ($manejador->give(["ORDEN" => $datosSerie['NUM_SERIE'], "CALLE" => $_POST['CALLE'], "PRUEBA" => $datosSerie['ID_PRUEBA'], "COMPETICION" => $_POST['COMPETICION']]) != 0) {
        die(json_encode(['success' => false, 'root' => $manejador->getListaErrores()]));
    }

    $listado = $manejador->getArray();

    $resultado["SER_ORDEN"  ] = $listado[0]["ORDEN"    ];
    $resultado["SER_NADADOR"] = $listado[0]["NADADOR"  ];
    $resultado["SER_CLUB"   ] = $listado[0]["CLUB"     ];
    $resultado["SER_ESTADO" ] = $datosSerie["EST_SERIE"];

    unset($manejador);
    
    echo json_encode(['success' => true, 'root' => $resultado]);

?>