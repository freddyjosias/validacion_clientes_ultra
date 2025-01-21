<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultados = $sqlServer->select("SELECT * FROM data_ultra_emision_202412 WHERE id_cliente = 0");

foreach ($resultados as $fila)
{
    $datosActualizar = [
        'id_cliente' => $fila['id_cliente'],
        'cli_nro_doc' => $fila['cli_nro_doc'],
        'FEC_EMIS' => $fila['FEC_EMIS'],
        'FEC_VENC' => $fila['FEC_VENC'],
        'FEC_CANC' => $fila['FEC_CANC'],
    ];

    $auxIdCliente = explode('-', $fila['desc_cliente']);

    if (count($auxIdCliente) < 2) {
        print_r_f($auxIdCliente);
    }

    $auxIdCliente = trim($auxIdCliente[0]);
    $auxIdCliente = trim($auxIdCliente, '(');
    $auxIdCliente = trim($auxIdCliente, ')');

    if (!is_numeric($auxIdCliente)) {
        print_r_f($auxIdCliente);
    }

    $datosActualizar['id_cliente'] = $auxIdCliente;

    $clienteEcom = $sqlServer->select("SELECT * FROM ECOM.ECOM_CLIENTE WHERE CLIV_CODIGO_CLIENTE = ?", [$auxIdCliente]);

    if (count($clienteEcom) != 1) {
        print_r_f($auxIdCliente);
    }

    $auxDataCliente = $clienteEcom[0];

    if ((int) $auxDataCliente['CLIV_NRO_RUC'] !== (int) $fila['cli_nro_doc']) {
        print_r_f($auxDataCliente);
    }

    $datosActualizar['cli_nro_doc'] = $auxDataCliente['CLIV_NRO_RUC'];

    $auxFecha = $fila['FEC_EMIS'];
    $auxFecha = explode('/', $auxFecha);

    if (count($auxFecha) != 3) {
        print_r_f($auxFecha);
    }

    $auxFecha = $auxFecha[2] . '-' . $auxFecha[1] . '-' . $auxFecha[0];

    $datosActualizar['FEC_EMIS'] = $auxFecha;

    $auxFecha = $fila['FEC_VENC'];
    $auxFecha = explode('/', $auxFecha);

    if (count($auxFecha) != 3) {
        print_r_f($auxFecha);
    }

    $auxFecha = $auxFecha[2] . '-' . $auxFecha[1] . '-' . $auxFecha[0];

    $datosActualizar['FEC_VENC'] = $auxFecha;

    $auxFecha = $fila['FEC_CANC'];

    if(is_null($auxFecha)) {
        $datosActualizar['FEC_CANC'] = null;
    } else {
        $auxFecha = explode('/', $auxFecha);

        if (count($auxFecha) != 3) {
            print_r_f($auxFecha);
        }

        $auxFecha = $auxFecha[2] . '-' . $auxFecha[1] . '-' . $auxFecha[0];

        $datosActualizar['FEC_CANC'] = $auxFecha;
    }

    $sqlServer->update('UPDATE data_ultra_emision_202412 SET id_cliente = ?, cli_nro_doc = ?, FEC_EMIS = ?, 
    FEC_VENC = ?, FEC_CANC = ? WHERE id_data = ?',
    [$datosActualizar['id_cliente'], $datosActualizar['cli_nro_doc'], $datosActualizar['FEC_EMIS'], $datosActualizar['FEC_VENC'], 
    $datosActualizar['FEC_CANC'], $fila['id_data']]);
}

print_r_f('OK :)');
