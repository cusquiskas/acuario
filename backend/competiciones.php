<?php
    session_start();
    error_reporting(E_ALL & ~E_NOTICE);

    require_once '../conex/conf.php';  //información crítica del sistema
    require_once '../conex/dao.php';   //control de comunicación con la base de datos MySQL
    require_once '../tabla/controller.php';   //genera la clase de una tabla dinámicamente bajo petición
 
    header('Content-Type: application/json; charset=utf-8');

    
    $link = new ConexionSistema();
    
    $filtro = [
        0 => ['tipo' => 's', 'dato' => $_POST['nadador']],
        1 => ['tipo' => 's', 'dato' => $_POST['nadador']],
        2 => ['tipo' => 's', 'dato' => $_POST['piscina']],
        3 => ['tipo' => 's', 'dato' => $_POST['piscina']],
        4 => ['tipo' => 's', 'dato' => $_POST['distancia']],
        5 => ['tipo' => 's', 'dato' => $_POST['distancia']],
        6 => ['tipo' => 's', 'dato' => $_POST['estilo']],
        7 => ['tipo' => 's', 'dato' => $_POST['estilo']]
    ];  
    $listado = $link->consulta("SELECT COMPETICION.FECHA, 
                                     COMPETICION.PRUEBA,
                                     NADADOR.NOMBRE 'NADADOR',
                                     COMPETICION.DISTANCIA,
                                     ESTILOS.NOMBRE 'ESTILO',
                                     CONCAT(COMPETICION.TIEMPO DIV 60, ':', COMPETICION.TIEMPO MOD 60) 'TIEMPO',
                                     BAÑERA.NOMBRE 'PISCINA',
                                     PISCINA.NOMBRE 'INSTALACION'
                                 FROM COMPETICION,
                                     BAÑERA,
                                     PISCINA,
                                     ESTILOS,
                                     NADADOR
                               WHERE COMPETICION.BAÑERA = BAÑERA.ID
                                 AND COMPETICION.PISCINA = PISCINA.ID
                                 AND COMPETICION.ESTILO = ESTILOS.ID
                                 AND COMPETICION.NADADOR = NADADOR.ID
                                 AND (NADADOR.ID = ? OR ? = '')
                                 AND (PISCINA.ID = ? OR ? = '')
                                 AND (COMPETICION.DISTANCIA = ? OR ? = '')
                                 AND (ESTILOS.ID = ? OR ? = '')
                               ORDER 
                                  BY COMPETICION.TIEMPO;", $filtro);
    if ($link->hayError()) {
        die(json_encode(['success' => true, 'root' => $link->getListaErrores()]));
    }
    $link->close();
    unset($link);

    echo json_encode(['success' => true, 'root' => $listado, 'filtros' => $_POST]);


?>