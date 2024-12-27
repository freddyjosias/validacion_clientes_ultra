<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultados = $sqlServer->select("SELECT a.id_data, a.cli_nro_doc, a.codigo_cliente_pago, C.CLII_ID_CLIENTE id_cliente,
a.desc_moneda
FROM data_ultra_emision_202412 a
INNER JOIN ECOM.ECOM_CLIENTE C ON C.CLIV_CODIGO_CLIENTE = a.codigo_cliente_pago
WHERE a.flg_status_habil = 1 AND a.cod_circuito = 0 AND a.RED = 'MPLS' and a.cli_nro_doc NOT IN ('-')");

// 07861996
// print_r_f($resultados);

$excluir = [];

foreach ($resultados as $fila) {

    if (in_array($fila['cli_nro_doc'], $excluir)) {
        continue;
    }

    $auxDataCliente = $sqlServer->select("SELECT a.id_data, a.cli_nro_doc, a.cod_circuito, a.desc_situacion, a.desc_moneda, a.SUB_TOTAL
    FROM data_ultra_emision_202412 a
    WHERE a.flg_status_habil = 1 AND a.cli_nro_doc = ? AND a.RED = 'MPLS' order by SUB_TOTAL", [$fila['cli_nro_doc']]);

    if (count($auxDataCliente) > 1)
    {
        $son_todos_iguales = true;
        $estan_conbrados = true;

        foreach ($auxDataCliente as $item) 
        {
            if($item['desc_situacion'] !== 'COBR') {
                $estan_conbrados = false;
            }

            if ($item['SUB_TOTAL'] !== $auxDataCliente[0]['SUB_TOTAL'] or $item['desc_situacion'] !== $auxDataCliente[0]['desc_situacion'] or 
            $item['desc_moneda'] !== $auxDataCliente[0]['desc_moneda'] or $item['cod_circuito'] !== $auxDataCliente[0]['cod_circuito'] or 
            $item['cli_nro_doc'] !== $auxDataCliente[0]['cli_nro_doc']) {
                $son_todos_iguales = false;
                break;
            }
        }

        $dataRaw = null;
        $intCliNroDoc = ctype_digit($fila['cli_nro_doc']) ? (int) $fila['cli_nro_doc'] : $fila['cli_nro_doc'];

        if (!$son_todos_iguales)
        {
            $son_todos_iguales = true;

            foreach ($auxDataCliente as $item) 
            {
                if ($item['desc_situacion'] !== $auxDataCliente[0]['desc_situacion'] or 
                $item['desc_moneda'] !== $auxDataCliente[0]['desc_moneda'] or $item['cod_circuito'] !== $auxDataCliente[0]['cod_circuito'] or 
                $item['cli_nro_doc'] !== $auxDataCliente[0]['cli_nro_doc']) {
                    $son_todos_iguales = false;
                    break;
                }
            }

            if (!$son_todos_iguales) {
                print_r_f(['line 70', $son_todos_iguales, $estan_conbrados, $auxDataCliente]);
            }

            $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod, RentaMensual, Moneda FROM data_ultra_raw WHERE CircuitoCod IS NOT NULL AND( RUC = ? OR RUC = ? ) ORDER BY CAST(RentaMensual AS FLOAT)", [$intCliNroDoc, $fila['cli_nro_doc']]);

            if (count($dataRaw) != count($auxDataCliente)) {
                if($fila['cli_nro_doc'] == '20544994779' or $fila['cli_nro_doc'] == '08248924') {
                    continue;
                }

                print_r_f(['line 80', $son_todos_iguales, $estan_conbrados, $auxDataCliente, $dataRaw]);
            }

            foreach ($auxDataCliente as $index => $item)
            {
                if ($item['SUB_TOTAL'] != $dataRaw[$index]['RentaMensual'] or $item['cli_nro_doc'] != $dataRaw[$index]['RUC'] or
                strtoupper($item['desc_moneda']) != strtoupper($dataRaw[$index]['Moneda'])) {
                    $son_todos_iguales = false;
                    break;
                }
            }

            if (!$son_todos_iguales) {
                print_r_f(['line 93', $son_todos_iguales, $estan_conbrados, $auxDataCliente, $dataRaw]);
            }
        }

        $estan_conbrados = true;
        // print_r_f(['line 98', $son_todos_iguales, $estan_conbrados, $auxDataCliente]);

        if (!$son_todos_iguales or !$estan_conbrados) {
            print_r_f(['line 101', $son_todos_iguales, $estan_conbrados, $auxDataCliente]);
        }

        if ($dataRaw == null) {
            $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod FROM data_ultra_raw WHERE CircuitoCod IS NOT NULL AND( RUC = ? OR RUC = ? )", [$intCliNroDoc, $fila['cli_nro_doc']]);
        }

        if (count($dataRaw) != count($auxDataCliente)) {
            if($fila['cli_nro_doc'] == '20522093743') {
                continue;
            }

            print_r_f(['line 109', $son_todos_iguales, $estan_conbrados, $auxDataCliente, $dataRaw]);
        }

        foreach ($auxDataCliente as $index => $item)
        {
            $sqlServer->update("UPDATE data_ultra_emision_202412 SET cod_circuito = ? WHERE id_data = ? AND cli_nro_doc = ?", [$dataRaw[$index]['CircuitoCod'], $item['id_data'], $fila['cli_nro_doc']]);
        }

        $excluir[] = $fila['cli_nro_doc'];
        continue;
    }
    else if (count($auxDataCliente) == 0)
    {
        print_r_f($fila);
    }

    $auxDataCliente = $auxDataCliente[0];

    $intCliNroDoc = ctype_digit($fila['cli_nro_doc']) ? (int) $fila['cli_nro_doc'] : $fila['cli_nro_doc'];

    $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod FROM data_ultra_raw
    WHERE CircuitoCod IS NOT NULL AND( RUC = ? OR RUC = ? ) AND BajaOperativa IS NULL", [$intCliNroDoc, $fila['cli_nro_doc']]);

    if (count($dataRaw) != 1) {
        if($fila['cli_nro_doc'] == '40636473' or $fila['cli_nro_doc'] == '75544787' or $fila['cli_nro_doc'] == '20502194616') {
            continue;
        }

        print_r_f(['no hay data raw', $dataRaw, $auxDataCliente]);
    }

    $dataRaw = $dataRaw[0];

    $sqlServer->update("UPDATE data_ultra_emision_202412 SET cod_circuito = ? WHERE id_data = ? AND cli_nro_doc = ?", [$dataRaw['CircuitoCod'], $auxDataCliente['id_data'], $fila['cli_nro_doc']]);

    // print_r_f([$fila, $auxDataCliente, $dataRaw]);
}

print_r_f('OK :)');