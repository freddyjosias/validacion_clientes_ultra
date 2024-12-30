<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

const DB_MYSQL_WINCRM_ULTRA = 'wincrm_ultra_last';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado_last';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

$resultados = $sqlServer->select("SELECT top 10 id_data, nro_documento, cod_pedido_ultra, cod_circuito, desc_latitud, desc_longitud,
desc_distrito, desc_provincia, desc_region, desc_oferta FROM " . TABLE_DATA_ULTRA_PROCESADO . " 
where cod_pedido_pf_ultra = 0 and status_ingreso_venta = 10 and status_resultado = 'ok'");

// print_r_f($resultados);
$cantidadNoEncontrados = 0;
$total = count($resultados);

foreach($resultados as $index => $fila)
{
    if($fila['cod_circuito'] == '38989' and $fila['desc_distrito'] == 'CHACLACAYO') {
        $fila['desc_distrito'] = 'ATE';
    }

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
        // print_r_f(['no encontrado 2', $pedidosUltra, $fila]);
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
        $cantidadNoEncontrados++;
        continue;
        die;
    }
    else if($cantPedidosEncontrados === 1 and $pedidoEntontrado)
    {
        $resultProcesados = $sqlServer->select("SELECT * FROM " . TABLE_DATA_ULTRA_PROCESADO . " where nro_documento = ?", [$fila['nro_documento']]);

        $estanEnElMismoDistrito = false;

        foreach($resultProcesados as $itemProcesado)
        {
            foreach($resultProcesados as $itemProcesado2)
            {
                if($itemProcesado['desc_distrito'] == $itemProcesado2['desc_distrito'] and $itemProcesado['desc_provincia'] == $itemProcesado2['desc_provincia'] and
                $itemProcesado['id_data'] != $itemProcesado2['id_data'])
                {
                    $estanEnElMismoDistrito = true;
                    break;
                }
            }
        }

        if($estanEnElMismoDistrito or count($resultProcesados) == 0)
        {
            print_r_f(['no encontrado 3', $resultProcesados]);
            continue;
        }

        // print_r_f(['no encontrado 4', $resultProcesados]);

        $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$dataPedido['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
        continue;
    }

    if(count($pedidosUltra) === 1)
    {
        $exoneradosUbigeo = ['06543622', '07194374', '10059205', '10059673', '10064780', '10087466391', '10206992', '10220900', '10225871', '10274486', '10309014', '10316223', '10319937', '10322472', '10490515', '10495728', '10542688', '10568618', '10611044', '10612358', '10613407', '10720561', '10866870', '20063177', '20101050301', '20392926535', '20460446733', '20543587339', '20551846408', '20556950327', '20565536657', '20602764380', '20603299893', '20604428328', '20608834843', '20609305631', '21797199', '21801476', '29285108', '40059566', '40283221', '40373389', '40430041', '06379930', '06477570', '06514079', '06514555', '06543622', '06630112', '06673293', '07194374', '07269119', '07341989', '07493750', '07614982', '07770641', '07797555', '07798132', '07799543', '07799555', '07813838', '07857780', '07868357', '07869604', '07881267', '08182571', '08193680', '08198045', '08228932', '08240706', '08246414', '08249218', '08261985', '08267349', '08273746', '40933789', '41012824', '41236559', '41328730', '40508624', '40512437', '40532535', '40597395', '09801119', '09886083', '09935253', '10003874', '000025316', '000222928', '000960830', '005259140', '08746434', '08746756', '09139079', '09177368', '09177801', '09179216', '09335139', '09342698', '09343266', '09344186', '09381598', '09382910', '09383226', '09670340', '41499384', '41580048', '41814922', '42242476', '42652520', '43151755', '43175087', '43313255', '43462560', '43497375', '43875727', '44049621', '44448779', '44710951', '45281859', '45548918', '45988122', '46329774', '46417172', '70089575', '70150032', '73033903', '75056788' ];

        // $exoneradosUbigeo = [];

        $resultProcesados = $sqlServer->select("SELECT * FROM " . TABLE_DATA_ULTRA_PROCESADO . " where nro_documento = ?", [$fila['nro_documento']]);
        $pedidosUltra = $pedidosUltra[0];

        if(count($resultProcesados) === 1 and $pedidosUltra['OFTV_NOMBRE'] === $resultProcesados[0]['desc_oferta'] and
        in_array($pedidosUltra['CLIV_NUMERO_DOCUMENTO'], $exoneradosUbigeo))
        {
            $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$pedidosUltra['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
            continue;
        }

        $coincidenciaPorDistrito = false;
        $cantidadCoincidenciaPorDistrito = 0;
        $idDataCoincidencia = 0;

        foreach($resultProcesados as $itemProcesado)
        {
            if($itemProcesado['desc_distrito'] == $pedidosUltra['desc_distrito'] and $itemProcesado['desc_provincia'] == $pedidosUltra['desc_provincia'])
            {
                $coincidenciaPorDistrito = true;
                $cantidadCoincidenciaPorDistrito++;
                $idDataCoincidencia = $itemProcesado['id_data'];
            }
        }

        if($coincidenciaPorDistrito and $cantidadCoincidenciaPorDistrito === 1 and $idDataCoincidencia === $fila['id_data'])
        {
            $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$pedidosUltra['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
            continue;
        }

        $cantidadNoEncontrados++;
        continue;

        print_r_f(['no encontrado 1', $fila, $pedidosUltra, $resultProcesados]);
    }

    if(!$pedidoEntontrado)
    {
        if($index > 1)
        {
            print_r_f([$fila, $pedidosUltra, $resultadosIgualdad]);
        }

        continue;
    }

    print_r_f(['encontrado', $pedidosUltra, $fila]);
}

print_r_f('Cantidad de no encontrados: ' . $cantidadNoEncontrados . ' de ' . $total);

print_r_f('OK :)');
