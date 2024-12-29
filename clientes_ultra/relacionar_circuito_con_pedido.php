<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', 'wincrm_ultra', 'root', 'R007w1N0r3');
$mysql->connect();

$resultados = $sqlServer->select("SELECT id_data, nro_documento, cod_pedido_ultra, cod_circuito, desc_latitud, desc_longitud,
desc_distrito, desc_provincia, desc_region, desc_oferta FROM data_ultra_procesado 
where cod_pedido_pf_ultra = 0 and status_ingreso_venta = 10 and status_resultado = 'ok'");

// print_r_f($resultados);
$cantidadNoEncontrados = 0;
$total = count($resultados);

foreach($resultados as $index => $fila)
{
    $pedidosUltra = $mysql->select("SELECT P.PEDI_COD_PEDIDO, C.CLIV_NUMERO_DOCUMENTO, D.DIRN_LATITUD, D.DIRN_LONGITUD, 
    UPPER(U1.UBIV_DESCRIPCION) desc_distrito, UPPER(U2.UBIV_DESCRIPCION) desc_provincia, O.OFTV_NOMBRE
    FROM CRM_PEDIDO P
    INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
    INNER JOIN CRM_DIRECCION D ON D.DIRI_COD_DIRECCION = P.PEDI_COD_DIRECCION
    INNER JOIN CRM_UBIGEO U1 ON U1.UBIC_UBIGEO = D.DIRC_UBIGEO
    INNER JOIN CRM_UBIGEO U2 ON U2.UBIC_UBIGEO = CONCAT(LEFT(U1.UBIC_UBIGEO,6), '00')
    INNER JOIN CRM_OFERTA O ON O.OFTI_COD_OFERTA = P.OFTI_COD_OFERTA
    WHERE C.CLIV_NUMERO_DOCUMENTO = ?", [$fila['nro_documento']]);

    if(count($pedidosUltra) == 0)
    {
        $cantidadNoEncontrados++;
        continue;
    }
    // print_r_f([$index, $pedidosUltra]);

    $pedidoEntontrado = false;
    $cantPedidosEncontrados = 0;
    $dataPedido = [];

    $resultadosIgualdad = [];

    foreach($pedidosUltra as $indice => $pedido)
    {
        $pedidosUltra[$indice]['DIRN_LATITUD'] = round($pedido['DIRN_LATITUD'], 4);
        $pedidosUltra[$indice]['DIRN_LONGITUD'] = round($pedido['DIRN_LONGITUD'], 4);

        $pedido['DIRN_LATITUD'] = $pedidosUltra[$indice]['DIRN_LATITUD'];
        $pedido['DIRN_LONGITUD'] = $pedidosUltra[$indice]['DIRN_LONGITUD'];

        $fila['desc_latitud'] = round($fila['desc_latitud'], 4);
        $fila['desc_longitud'] = round($fila['desc_longitud'], 4);

        if($pedido['CLIV_NUMERO_DOCUMENTO'] === $fila['nro_documento'] and $pedido['OFTV_NOMBRE'] === $fila['desc_oferta'] and 
        $pedido['DIRN_LATITUD'] === $fila['desc_latitud'] and $pedido['DIRN_LONGITUD'] === $fila['desc_longitud'] and 
        $pedido['desc_distrito'] === $fila['desc_distrito'] and $pedido['desc_provincia'] === $fila['desc_provincia'])
        {
            $pedidoEntontrado = true;
            $dataPedido = $pedido;
            $cantPedidosEncontrados++;
        }

        $resultadosIgualdad[] = [
            'nro_documento' => $fila['nro_documento'] === $pedido['CLIV_NUMERO_DOCUMENTO'],
            'desc_oferta' => $fila['desc_oferta'] === $pedido['OFTV_NOMBRE'],
            'desc_latitud' => $fila['desc_latitud'] === $pedido['DIRN_LATITUD'],
            'desc_longitud' => $fila['desc_longitud'] === $pedido['DIRN_LONGITUD'],
            'desc_distrito' => $fila['desc_distrito'] === $pedido['desc_distrito'],
            'desc_provincia' => $fila['desc_provincia'] === $pedido['desc_provincia'],
        ];
    }

    if($cantPedidosEncontrados > 1)
    {
        echo 'Mas de un pedido encontrado para el cliente: ' . $fila['nro_documento'] . "\n";
        die;
    }
    else if($cantPedidosEncontrados === 1 and $pedidoEntontrado)
    {
        $resultProcesados = $sqlServer->select("SELECT * FROM data_ultra_procesado where nro_documento = ?", [$fila['nro_documento']]);

        if(count($resultProcesados) !== 1)
        {
            print_r_f(['no encontrado 3', $resultProcesados]);
            continue;
        }

        $sqlServer->update("UPDATE data_ultra_procesado SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$dataPedido['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
        continue;
    }

    if(count($pedidosUltra) === 1)
    {
        $exoneradosUbigeo = ['06341399'];

        $exoneradosUbigeo = [];

        $resultProcesados = $sqlServer->select("SELECT * FROM data_ultra_procesado where nro_documento = ?", [$fila['nro_documento']]);
        $pedidosUltra = $pedidosUltra[0];

        if(count($resultProcesados) === 1 and $pedidosUltra['OFTV_NOMBRE'] === $resultProcesados[0]['desc_oferta'] and
        in_array($pedidosUltra['CLIV_NUMERO_DOCUMENTO'], $exoneradosUbigeo))
        {
            $sqlServer->update("UPDATE data_ultra_procesado SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$pedidosUltra['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
            continue;
        }

        print_r_f(['no encontrado 1', $pedidosUltra, $resultProcesados]);
    }

    if(!$pedidoEntontrado)
    {
        if($index > 1)
        {
            print_r_f($resultadosIgualdad);
        }

        continue;
    }

    print_r_f(['encontrado', $pedidosUltra, $fila]);
}

print_r_f('Cantidad de no encontrados: ' . $cantidadNoEncontrados . ' de ' . $total);

print_r_f('OK :)');
