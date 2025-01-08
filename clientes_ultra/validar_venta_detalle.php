<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultados = $sqlServer->select("select distinct cod_pedido_ultra, cod_circuito from data_ultra_proc_detalle_pr where flg_validacion = 0");

foreach($resultados as $item)
{
    $data = $sqlServer->select("select d.cod_pedido_ultra, d.cod_circuito, desc_concepto, cod_moneda, monto, p.ecom_id_servicio
    from data_ultra_proc_detalle_pr d
    inner join data_ultra_procesado_prod p on d.cod_circuito = p.cod_circuito and d.cod_pedido_ultra = p.cod_pedido_ultra
    where d.flg_validacion = 0 and d.cod_circuito = ? and d.cod_pedido_ultra = ?", [$item['cod_circuito'], $item['cod_pedido_ultra']]);

    if(count($data) == 0) {
        print_r_f($item);
    }

    $servicio = $data[0]['ecom_id_servicio'];
    $moneda = $data[0]['cod_moneda'];
    $montoRecurrente = 0;

    foreach($data as $itemDetalle)
    {
        $montoRecurrente += $itemDetalle['monto'];

        if($servicio != $itemDetalle['ecom_id_servicio']) {
            print_r_f($item);
        }
    }

    $controlPago = $sqlServer->select("SELECT CP.CPGI_ID_CONTROL_PAGO, CP.SDEI_ID_SERVICIO_DETALLE,  CP.SERI_ID_SERVICIO, 
    CP.CPGI_MONEDA, CPGN_MONTO, CPGI_ESTADO, CPGB_SITUACION, CPGV_PERIODO_CONSUMO,
    CPGD_FECHA_CONSUMO_INI, CPGD_FECHA_CONSUMO_FIN, SD.CATI_ID_CATALOGO
    FROM PE_OPTICAL_ADM_PROD_20250106_090317.ECOM.ECOM_CONTROL_PAGO CP
    LEFT JOIN PE_OPTICAL_ADM_PROD_20250106_090317.ECOM.ECOM_SERVICIO_DETALLE SD ON CP.SDEI_ID_SERVICIO_DETALLE = SD.SDEI_ID_SERVICIO_DETALLE
    WHERE CP.SERI_ID_SERVICIO = ? AND CPGV_PERIODO_CONSUMO >= '202412' and CPGN_MONTO <> 0 and CP.CPGB_SITUACION = 1
    ORDER BY CP.CPGV_PERIODO_CONSUMO, CP.SDEI_ID_SERVICIO_DETALLE;
    ", [$servicio]);

    if(count($controlPago) == 0) {
        print_r_f($item);
    }

    $agrupadosPorPeriodo = [];
    $monedaControlPago = $controlPago[0]['CPGI_MONEDA'] == '2' ? '01' : '02';

    foreach($controlPago as $itemControlPago)
    {
        if(!isset($agrupadosPorPeriodo[$itemControlPago['CPGV_PERIODO_CONSUMO']])) {
            $agrupadosPorPeriodo[$itemControlPago['CPGV_PERIODO_CONSUMO']] = 0;
        }

        $agrupadosPorPeriodo[$itemControlPago['CPGV_PERIODO_CONSUMO']] += $itemControlPago['CPGN_MONTO'];
    }

    foreach($agrupadosPorPeriodo as $periodo => $monto)
    {
        $monto = round($monto * 1.18, 2);
        $monto = $monto == 175.01 ? 175.00 : $monto;
        $monto = $monto == 100.01 ? 100.00 : $monto;
        $monto = $monto == 119.99 ? 120.00 : $monto;
        $monto = $monto == 269.01 ? 269.00 : $monto;
        $monto = $monto == 175.01 ? 175.00 : $monto;
        $monto = $monto == 174.99 ? 175.00 : $monto;
        $monto = $monto == 99.99 ? 100.00 : $monto;
        $monto = $monto == 114.99 ? 115.00 : $monto;
        $monto = $monto == 125.01 ? 125.00 : $monto;
        $monto = $monto == 74.99 ? 75.00 : $monto;
        $monto = $monto == 268.99 ? 269.00 : $monto;

        if((string) $monto != (string) $montoRecurrente or $monedaControlPago != $moneda) {
            print_r_f(['error', $item, $monto, $montoRecurrente, $monedaControlPago, $moneda]);
        }
    }

    $sumaRecurrente = $sqlServer->select("select SUMA_RECURRENTE from data_ultra_emision_prod where ID_PEDIDO = ? and cod_circuito = ?;", [$item['cod_pedido_ultra'], $item['cod_circuito']]);

    if(count($sumaRecurrente) != 1) {
        print_r_f([$item, $sumaRecurrente]);
    }

    $sumaRecurrente = $sumaRecurrente[0]['SUMA_RECURRENTE'];
    $sumaRecurrente = $sumaRecurrente == 175.01 ? 175.00 : $sumaRecurrente;
    $sumaRecurrente = $sumaRecurrente == 174.99 ? 175.00 : $sumaRecurrente;
    $sumaRecurrente = $sumaRecurrente == 268.98 ? 269.00 : $sumaRecurrente;

    if((string) $sumaRecurrente != (string) $montoRecurrente) {
        print_r_f(['error 2'	, $item, $sumaRecurrente, $montoRecurrente]);
    }

    $sqlServer->update("update data_ultra_proc_detalle_pr set flg_validacion = 1 where cod_pedido_ultra = ? and cod_circuito = ?;", [$item['cod_pedido_ultra'], $item['cod_circuito']]);

    continue;

    $planesAux = [];
    print_r_f([$sumaRecurrente]);

    print_r_f([$data, $item, $montoRecurrente], $data);
}

print_r_f('fin :)');

