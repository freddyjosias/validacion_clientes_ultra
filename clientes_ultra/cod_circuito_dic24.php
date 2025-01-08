<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultados = $sqlServer->select("SELECT a.id_data, a.cli_nro_doc, a.codigo_cliente_pago, C.CLII_ID_CLIENTE id_cliente,
a.desc_moneda, compro_nro_doc
FROM data_ultra_emision_prod a
INNER JOIN ECOM.ECOM_CLIENTE C ON C.CLIV_CODIGO_CLIENTE = a.codigo_cliente_pago
WHERE a.flg_status_habil = 1 AND a.cod_circuito = 0 AND a.RED = 'MPLS' and a.cli_nro_doc NOT IN ('-') 
order by cli_nro_doc");

// 07861996
// print_r_f($resultados);

$excluir = [];

foreach ($resultados as $fila) {

    if (in_array($fila['cli_nro_doc'], $excluir)) {
        continue;
    }

    $auxDataCliente = $sqlServer->select("SELECT a.id_data, a.cli_nro_doc, a.cod_circuito, a.desc_situacion, a.desc_moneda, a.SUB_TOTAL, a.compro_nro_doc,
    a.TOTAL
    FROM data_ultra_emision_prod a
    WHERE a.flg_status_habil = 1 AND a.cli_nro_doc = ? AND a.RED = 'MPLS' order by SUB_TOTAL", [$fila['cli_nro_doc']]);

    if (count($auxDataCliente) > 1)
    {
        $son_todos_iguales = true;
        $estan_conbrados = true;
        $son_situacion_iguales = true;

        foreach ($auxDataCliente as $item) 
        {
            if($item['desc_situacion'] !== 'COBR') {
                $estan_conbrados = false;
            }

            if($item['desc_situacion'] !== $auxDataCliente[0]['desc_situacion']) {
                $son_situacion_iguales = false;
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

        if (!$son_todos_iguales and $son_situacion_iguales)
        {
            // print_r_f(['line 55', $son_situacion_iguales, $dataRaw, $auxDataCliente, $fila]);
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

            $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod, RentaMensual, Moneda FROM data_ultra_raw WHERE CircuitoCod IS NOT NULL AND( RUC = ? OR RUC = ? ) ORDER BY CAST(LTRIM(RTRIM(REPLACE(RentaMensual, 'S/', ''))) AS FLOAT)", [$intCliNroDoc, $fila['cli_nro_doc']]);

            if(count($dataRaw) == 0) {
                $sqlServer->update("UPDATE data_ultra_emision_prod SET desc_observacion = 'No tiene registro en data_ultra_raw', flg_status_habil = 0
                WHERE id_data = ? AND cli_nro_doc = ?", [$fila['id_data'], $fila['cli_nro_doc']]);
                continue;
            }

            if (count($dataRaw) != count($auxDataCliente)) {
                // if($fila['cli_nro_doc'] == '20544994779' or $fila['cli_nro_doc'] == '08248924') {
                //     continue;
                // }

                print_r_f(['line 80', $son_todos_iguales, $estan_conbrados, $auxDataCliente, $dataRaw]);
            }

            foreach ($auxDataCliente as $index => $item)
            {
                $dataRaw[$index]['RentaMensual'] = trim($dataRaw[$index]['RentaMensual'], 'S/');
                $dataRaw[$index]['RentaMensual'] = trim($dataRaw[$index]['RentaMensual']);

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

        // $estan_conbrados = true;
        // print_r_f(['line 98', $son_todos_iguales, $estan_conbrados, $auxDataCliente]);

        if (!$son_todos_iguales or !$son_situacion_iguales) {
        // if (!$son_todos_iguales or !$estan_conbrados or !$son_situacion_iguales) {
            print_r_f(['line 101', $son_todos_iguales, $estan_conbrados, $son_situacion_iguales, $auxDataCliente]);
        }

        if ($dataRaw == null) {
            $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod FROM data_ultra_raw WHERE CircuitoCod IS NOT NULL AND ( RUC = ? OR RUC = ? )", [$intCliNroDoc, $fila['cli_nro_doc']]);

            if(count($dataRaw) == 0) {
                $sqlServer->update("UPDATE data_ultra_emision_prod SET desc_observacion = 'No tiene registro en data_ultra_raw', flg_status_habil = 0
                WHERE id_data = ? AND cli_nro_doc = ?", [$fila['id_data'], $fila['cli_nro_doc']]);
                continue;
            }
        }

        if (count($dataRaw) != count($auxDataCliente)) {
            // if($fila['cli_nro_doc'] == '20522093743') {
            //     continue;
            // }

            print_r_f(['line 109', $son_todos_iguales, $estan_conbrados, $auxDataCliente, $dataRaw]);
        }

        foreach ($auxDataCliente as $index => $item)
        {
            $sqlServer->update("UPDATE data_ultra_emision_prod SET cod_circuito = ?, desc_observacion = 'ok' WHERE id_data = ? AND cli_nro_doc = ?", [$dataRaw[$index]['CircuitoCod'], $item['id_data'], $fila['cli_nro_doc']]);
        }

        $excluir[] = $fila['cli_nro_doc'];
        continue;
    }
    else if (count($auxDataCliente) == 0)
    {
        print_r_f([$dataRaw, $auxDataCliente, $fila]);
    }

    $auxDataCliente = $auxDataCliente[0];

    $intCliNroDoc = ctype_digit($fila['cli_nro_doc']) ? (int) $fila['cli_nro_doc'] : $fila['cli_nro_doc'];

    $dataRaw = $sqlServer->select("SELECT RUC, CircuitoCod, RentaMensual, BajaOperativa FROM data_ultra_raw
    WHERE CircuitoCod IS NOT NULL AND( RUC = ? OR RUC = ? )", [$intCliNroDoc, $fila['cli_nro_doc']]);

    if (count($dataRaw) == 0)
    {
        $sqlServer->update("UPDATE data_ultra_emision_prod SET desc_observacion = 'No tiene registro en data_ultra_raw', flg_status_habil = 0
        WHERE id_data = ? AND cli_nro_doc = ?", [$auxDataCliente['id_data'], $fila['cli_nro_doc']]);

        continue;
    }
    else if (count($dataRaw) > 1)
    {
        $dataSinBaja = [];

        foreach ($dataRaw as $item) {
            if($item['BajaOperativa'] == null) {
                $dataSinBaja[] = $item;
            }
        }

        if(count($dataSinBaja) != 1) {
            print_r_f(['line 135', $dataRaw, $auxDataCliente]);
        }

        $dataRaw = $dataSinBaja;
    }

    $dataRaw = $dataRaw[0];

    if($dataRaw['BajaOperativa'] != null)
    {
        $baja = $dataRaw['BajaOperativa'];
        $baja = explode('/', $baja);

        if(count($baja) != 3) {
            print_r_f(['line 136', $baja]);
        }

        $baja = $baja[2] . '-' . str_pad($baja[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($baja[0], 2, '0', STR_PAD_LEFT);

        if($baja > '2024-06-01') {
            print_r_f(['line 139', $dataRaw, $auxDataCliente, $fila]);
        }
    }

    $dataRaw['RentaMensual'] = trim($dataRaw['RentaMensual'], 'S/.');
    $dataRaw['RentaMensual'] = trim($dataRaw['RentaMensual']);

    $diferencia1 = abs($dataRaw['RentaMensual'] - $auxDataCliente['SUB_TOTAL']);
    $diferencia2 = abs($dataRaw['RentaMensual'] - $auxDataCliente['TOTAL']);

    if($dataRaw['RentaMensual'] != $auxDataCliente['SUB_TOTAL'] and $dataRaw['RentaMensual'] != $auxDataCliente['TOTAL'] and $diferencia1 > 0.01 and $diferencia2 > 0.01) {
        print_r_f(['line 145', $dataRaw, $auxDataCliente, $fila]);
    }

    // print_r_f([$dataRaw, $auxDataCliente, $fila]);

    $sqlServer->update("UPDATE data_ultra_emision_prod SET cod_circuito = ?, desc_observacion = 'ok' WHERE id_data = ? AND cli_nro_doc = ?", [$dataRaw['CircuitoCod'], $auxDataCliente['id_data'], $fila['cli_nro_doc']]);

    // print_r_f([$fila, $auxDataCliente, $dataRaw]);
}

print_r_f('OK :)');