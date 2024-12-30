<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

const DB_MYSQL_WINCRM_ULTRA = 'wincrm_ultra_last';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado_last';
const TABLE_DATA_ULTRA_PROC_DETALLE = 'data_ultra_proc_detalle_last';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$sqlServerActual = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM_PROD_20241224_060004', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServerActual->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

$resultados = $sqlServer->select("SELECT p.id_data, p.nro_documento, p.cod_pedido_ultra, p.cod_circuito, p.desc_oferta,
p.ecom_id_servicio, p.ecom_id_contrato, p.cod_pedido_pf_ultra, p.desc_moneda
FROM " . TABLE_DATA_ULTRA_PROCESADO . " p
LEFT JOIN " . TABLE_DATA_ULTRA_PROC_DETALLE . " d ON p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
AND d.cod_circuito IS NULL");

// print_r_f($resultados);

foreach($resultados as $item)
{
    $pedidosUltra = $mysql->select("SELECT P.PEDI_COD_PEDIDO, C.CLIV_NUMERO_DOCUMENTO
    FROM CRM_PEDIDO P
    INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
    INNER JOIN CRM_OFERTA O ON O.OFTI_COD_OFERTA = P.OFTI_COD_OFERTA
    INNER JOIN CRM_DOCUMENTO D ON D.DOCI_COD_PEDIDO_REF = P.PEDI_COD_PEDIDO
    WHERE C.CLIV_NUMERO_DOCUMENTO = ? AND P.PEDI_COD_PEDIDO = ? AND D.DOCC_COD_TIPO_ESTADO = '03'", [$item['nro_documento'], $item['cod_pedido_pf_ultra']]);

    if(count($pedidosUltra) != 1)
    {
        /// print_r_f(['no encontrado', $item]);
        continue;
    }

    $pedidoUltra = $pedidosUltra[0];
    $esMPLS = false;

    if($item['cod_pedido_ultra'] === '0' and $item['cod_circuito'] != 0)
    {
        $esMPLS = true;
    }
    else if ($item['cod_pedido_ultra'] != '0' and $item['cod_circuito'] === "0")
    {
        $esMPLS = false;
    }
    else {
        print_r_f(['no encontrado - ERROR 47', $item]);
    }

    procesar_venta_detalle($item, $pedidoUltra, $mysql, $sqlServer, $sqlServerActual, $esMPLS);
}

function procesar_venta_detalle($item, $pedidoUltra, $mysql, $sqlServer, $sqlServerActual, $esMPLS)
{
    $ventaDetalleActual = $mysql->select("SELECT D.DOCI_COD_PEDIDO_REF, D.DOCI_COD_DOCUMENTO, D.DOCC_COD_TIPO_MONEDA, V.VTAI_COD_VENTA, 
    V.VTAC_COD_TIPO_MONEDA, VD.VTDI_COD_VENTA_DETALLE, VD.VTDI_COD_TARIFARIO, VD.VTDC_COD_TIPO_MONEDA,
    VD.VTDN_PRECIO
    FROM CRM_DOCUMENTO D 
    INNER JOIN CRM_PROGRAMACION PRO ON D.DOCI_COD_DOCUMENTO = PRO.PRGI_COD_DOCUMENTO
    INNER JOIN CRM_VENTA V ON D.DOCI_COD_DOCUMENTO = V.VTAI_COD_DOCUMENTO
    INNER JOIN CRM_VENTA_DETALLE VD ON V.VTAI_COD_VENTA = VD.VTDI_COD_VENTA
    WHERE D.DOCI_COD_PEDIDO_REF = ?", [$item['cod_pedido_pf_ultra']]);

    if(count($ventaDetalleActual) != 1)
    {
        print_r_f(['no encontrado - ERROR 65', $item]);
        return;
    }

    $ventaDetalleActual = $ventaDetalleActual[0];

    if ($ventaDetalleActual['VTDI_COD_TARIFARIO'] != 260 and $ventaDetalleActual['VTDI_COD_TARIFARIO'] != 261 and
    $ventaDetalleActual['VTDI_COD_TARIFARIO'] != 262 and $ventaDetalleActual['VTDI_COD_TARIFARIO'] != 263 and 
    $ventaDetalleActual['VTDI_COD_TARIFARIO'] != 264) {
        print_r_f(['no encontrado - ERROR 73', $item]);
    }

    $servicioDetalle = $sqlServerActual->select("SELECT S.SERI_ID_SERVICIO, S.SERI_MODALIDAD_EMISION, 
    S.SESI_ID_SERVICIO_ESTADO, S.SERV_SITUACION,
    SD.SDEI_ID_SERVICIO_DETALLE, SD.CATI_ID_CATALOGO,
    SD.SDEI_MONEDA, SD.SDEN_MONTO, SD.SDED_FECHA, SD.SDED_FECHA_FIN,
    SD.SDEI_TIPO_EMISION, SD.SDEI_TIPO_DETALLE, SD.SDEI_CANTIDAD,
    C.CATV_DESCRIPCION_GLOSA, C.CATV_DESCRIPCION_CONCEPTO, CAST(SD.SDED_FECHA_REGISTRO AS DATE) fec_registro
    FROM ECOM.ECOM_SERVICIO S
        INNER JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON S.SERI_ID_SERVICIO = SD.SERI_ID_SERVICIO
        INNER JOIN ECOM.ECOM_CATALOGO C ON SD.CATI_ID_CATALOGO = C.CATI_ID_CATALOGO
        WHERE S.CONI_ID_CONTRATO = ? AND S.SERI_ID_SERVICIO = ?
    AND (SD.SDED_FECHA_FIN IS NULL OR SD.SDED_FECHA_FIN > '2024-06-30') AND SD.SDEN_MONTO <> 0;
    ", [$item['ecom_id_contrato'], $item['ecom_id_servicio']]);

    if(count($servicioDetalle) == 0)
    {
        print_r_f(['no encontrado - ERROR 91', $item]);
        return;
    }

    $controlPago = $sqlServerActual->select("SELECT CP.CPGI_ID_CONTROL_PAGO, CP.SDEI_ID_SERVICIO_DETALLE, 
    CP.CPGI_MONEDA, CPGN_MONTO, CPGI_ESTADO, CPGB_SITUACION, CPGV_PERIODO_CONSUMO,
    CPGD_FECHA_CONSUMO_INI, CPGD_FECHA_CONSUMO_FIN, SD.CATI_ID_CATALOGO
    FROM ECOM.ECOM_CONTROL_PAGO CP
    LEFT JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON CP.SDEI_ID_SERVICIO_DETALLE = SD.SDEI_ID_SERVICIO_DETALLE
    WHERE CP.SERI_ID_SERVICIO = ? AND CPGV_PERIODO_CONSUMO > '202406'
    ORDER BY CP.CPGV_PERIODO_CONSUMO, CP.SDEI_ID_SERVICIO_DETALLE;
    ", [$item['ecom_id_servicio']]);

    if(count($controlPago) == 0)
    {
        print_r_f(['no encontrado - ERROR 105', $item]);
        return;
    }

    $dataComprobante = $sqlServerActual->select("SELECT C.COMC_COD_COMPROBANTE, C.MONI_ID_MONEDA, C.COMV_PERIODO_COMPROBANTE,
    C.COMC_IMPORTE_SOLES compro_soles, C.COMC_IMPORTE_USD compro_usd,
    CD.COMD_COD_DETALLE, CD.SDEI_ID_SERVICIO_DETALLE,
    CD.COMD_IMPORTE_SOL compro_det_soles, CD.COMD_IMPORTE_USD compro_det_usd,
    CD.CPGI_ID_CONTROL_PAGO, CD.COMD_DES_CONCEPTO
    FROM ECOM.COMPROBANTE C
    INNER JOIN ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
    WHERE C.COMV_PERIODO_COMPROBANTE > '202406' AND CD.SERI_ID_SERVICIO = ?
    AND C.ESTI_ID_ESTADO <> 11 AND C.COMC_TIPO_OPERACION IN ('C', 'A')
    ORDER BY C.COMV_PERIODO_COMPROBANTE
    ", [$item['ecom_id_servicio']]);

    if(count($dataComprobante) == 0)
    {
        print_r_f(['no encontrado - ERROR 123', $item]);
        return;
    }

    $nuevaVentaDetalle = [
        'general' => [
            'moneda' => null,
            'moneda_ecom' => null,
            'monto_recurrente' => 0,
            'monto_recurrente_202411' => 0,
            'precio_instalacion' => null
        ],
        'detalle' => [
        ]
    ];

    // VALIDACIÓN MONEDA

    $nuevaVentaDetalle['general']['moneda'] = $item['desc_moneda'];

    if($item['desc_moneda'] === 'Soles')
    {
        $nuevaVentaDetalle['general']['moneda_ecom'] = 1;
    }
    else if($item['desc_moneda'] === 'Dolares')
    {
        $nuevaVentaDetalle['general']['moneda_ecom'] = 2;
    }
    else {
        print_r_f(['no encontrado - ERROR 151   ', $item]);
    }

    // Validar moneda en $servicioDetalle

    foreach($servicioDetalle as $indiceDetalle => $servicio)
    {
        if((int) $servicio['SDEI_MONEDA'] !== (int) $nuevaVentaDetalle['general']['moneda_ecom'])
        {
            print_r_f(['no encontrado - ERROR 160', $item]);
        }
    }

    // Validar moneda en $controlPago

    foreach($controlPago as $control)
    {
        if((int) $control['CPGI_MONEDA'] !== (int) $nuevaVentaDetalle['general']['moneda_ecom'])
        {
            return;
            print_r_f(['no encontrado - ERROR 170', $item]);
        }
    }

    // Validar moneda en $dataComprobante

    foreach($dataComprobante as $comprobante)
    {
        if((int) $comprobante['MONI_ID_MONEDA'] !== (int) $nuevaVentaDetalle['general']['moneda_ecom'])
        {
            print_r_f(['no encontrado - ERROR 180', $item]);
        }
    }

    // Validar modalidad de emision en $servicioDetalle

    // print_r_f([$item, $servicioDetalle]);

    foreach($servicioDetalle as $servicio)
    {
        if((int) $servicio['SERI_MODALIDAD_EMISION'] !== 2 OR 
        ((int) $servicio['SESI_ID_SERVICIO_ESTADO'] !== 2 and (int) $servicio['SESI_ID_SERVICIO_ESTADO'] !== 1) or
        $servicio['SERV_SITUACION'] !== 'A')
        {
            return;
            print_r_f(['no encontrado - ERROR 191', $item, $servicioDetalle]);
        }
    }

    // Validar existencia de los productos en $servicioDetalle

    foreach($servicioDetalle as $indiceDetalle => $servicio)
    {
        if($servicio['CATV_DESCRIPCION_GLOSA'] !== $servicio['CATV_DESCRIPCION_CONCEPTO'])
        {
            print_r_f(['no encontrado - ERROR 201', $item]);
        }

        /*$resultProducto = $mysql->select("SELECT PROI_COD_PRODUCTO, PROV_NOMBRE_PRODUCTO, PROI_TIPO_PRODUCTO_GRUPO FROM CRM_PRODUCTO 
        WHERE PROV_NOMBRE_PRODUCTO = ?", [$servicio['CATV_DESCRIPCION_CONCEPTO']]);

        if(count($resultProducto) !== 1)
        {
            print_r_f(['no encontrado - ERROR producto', $servicio]);
        }*/

        $arrayServicios2 = ['Instalacion de Servicio Ultra', 'Decremento de renta', 'Incremento de Renta',
        'Traslado'];
        $arrayServicios3 = ['Servicio de Internet Ultra'];

        if (in_array($servicio['CATV_DESCRIPCION_CONCEPTO'], $arrayServicios2))
        {
            $servicioDetalle[$indiceDetalle]['PROI_TIPO_PRODUCTO_GRUPO'] = 2;
        }
        else if (in_array($servicio['CATV_DESCRIPCION_CONCEPTO'], $arrayServicios3))
        {
            $servicioDetalle[$indiceDetalle]['PROI_TIPO_PRODUCTO_GRUPO'] = 3;
        }
        else
        {
            print_r_f(['no encontrado - ERROR 209', $servicio]);
        }
    }

    // Validar modalidad de emision en $servicioDetalle

    $tipoEmision2 = [];

    foreach($servicioDetalle as $servicio)
    {
        if((float) $servicio['SDEN_MONTO'] < 10 and (float) $servicio['SDEN_MONTO'] >= -1)
        {
            print_r_f(['no encontrado - ERROR 219 ' . (float) $servicio['SDEN_MONTO'], $item]);
        }

        $tipoEmision2 = ['Instalacion de Servicio Ultra', 'Traslado'];

        if(in_array($servicio['CATV_DESCRIPCION_CONCEPTO'], $tipoEmision2) and $servicio['SDEI_TIPO_EMISION'] == 2)
        {
            $nuevaVentaDetalle['general']['precio_instalacion'] = $servicio['SDEN_MONTO'];
        }
        else if((int) $servicio['SDEI_TIPO_EMISION'] === 1)
        {
            $nuevaVentaDetalle['general']['monto_recurrente'] += $servicio['SDEN_MONTO'];

            if(!is_null($servicio['SDED_FECHA']) and $servicio['SDED_FECHA'] > '2025-01-01') {
                print_r_f(['INICIAR LUEGP']);
            }

            if(!is_null($servicio['SDED_FECHA_FIN']) and $servicio['SDED_FECHA_FIN'] > '2025-01-01') {
                print_r_f(['INICIAR LUEGP1']);
            }
        }
        else {
            print_r_f(['no encontrado - ERROR 231', $servicio, $item]);
        }
    }

    // Validar modalidad de emision en $servicioDetalle

    foreach($servicioDetalle as $servicio)
    {
        $tipoEmision2 = ['Instalacion de Servicio Ultra', 'Traslado'];

        if(!is_null($servicio['SDED_FECHA']) and $servicio['SDED_FECHA'] > '2024-11-31') {
            continue;
        }
        else if(in_array($servicio['CATV_DESCRIPCION_CONCEPTO'], $tipoEmision2) and $servicio['SDEI_TIPO_EMISION'] == 2)
        {
            continue;
        }
        else if((int) $servicio['SDEI_TIPO_EMISION'] === 1)
        {
            $nuevaVentaDetalle['general']['monto_recurrente_202411'] += $servicio['SDEN_MONTO'];
        }
        else {
            print_r_f(['no encontrado - ERROR 2311', $item]);
        }
    }

    // Cuadrar Servicios con Control Pago

    $dataServicios = [];

    foreach($servicioDetalle as $servicio)
    {
        if($servicio['CATV_DESCRIPCION_CONCEPTO'] === 'Instalacion de Servicio Ultra' and $servicio['SDEI_TIPO_EMISION'] == 2)
        {
            $nuevaVentaDetalle['general']['precio_instalacion'] = $servicio['SDEN_MONTO'];
        }
        else
        {
            $dataServicios[] = $servicio;
        }
    }

    $periodosControlPago = [];

    foreach($controlPago as $control)
    {
        if(!in_array($control['CPGV_PERIODO_CONSUMO'], $periodosControlPago))
        {
            $periodosControlPago[] = $control['CPGV_PERIODO_CONSUMO'];
        }
    }

    // print_r_f($dataComprobante);
    $seComprobo202412 = false;

    foreach($periodosControlPago as $periodo)
    {
        $montoControlPago = 0;
        $montoComprobante = 0;
        $cantidadComprobante = 0;
        $montoControlPagoSinSuspension = 0;

        foreach($controlPago as $control)
        {
            if($control['CPGV_PERIODO_CONSUMO'] === $periodo)
            {
                $montoControlPago += round($control['CPGN_MONTO'], 2);

                if($control['CATI_ID_CATALOGO'] != 40)
                {
                    $montoControlPagoSinSuspension += round($control['CPGN_MONTO'], 2);
                }
            }
        }

        // print_r_f([$montoControlPago, $controlPago]);

        $comproMontoTotal = 0;
        $comproMontoTotalSinDesSuspension = 0;

        foreach($dataComprobante as $comprobante)
        {
            if($comprobante['COMV_PERIODO_COMPROBANTE'] === $periodo)
            {
                $montoComprobante += ($nuevaVentaDetalle['general']['moneda'] === 'Soles') ? $comprobante['compro_det_soles'] : $comprobante['compro_det_usd'];
                $cantidadComprobante++;

                $auxMontoTotal = ($nuevaVentaDetalle['general']['moneda'] === 'Soles') ? $comprobante['compro_soles'] : $comprobante['compro_usd'];

                $comproMontoTotal += ($nuevaVentaDetalle['general']['moneda'] === 'Soles') ? $comprobante['compro_det_soles'] : $comprobante['compro_det_usd'];

                if($comprobante['COMD_DES_CONCEPTO'] != 'Suspension por Falta de Pago')
                {
                    $comproMontoTotalSinDesSuspension += ($nuevaVentaDetalle['general']['moneda'] === 'Soles') ? $comprobante['compro_det_soles'] : $comprobante['compro_det_usd'];
                }

                if ((string) $montoControlPago != $auxMontoTotal)
                {
                    return;
                    // var_dump(['no encontrado - ERROR 285', $montoControlPago, $auxMontoTotal]); die;
                    print_r_f(['no encontrado - ERROR 285', $montoControlPago, $auxMontoTotal, $item]);
                }
            }
        }

        if($montoComprobante !== $montoControlPago and $cantidadComprobante > 0)
        {
            print_r_f(['no encontrado - ERROR 283', $item]);
        }

        if($periodo >= '202501' and $montoControlPago !== $nuevaVentaDetalle['general']['monto_recurrente'])
        {
            print_r_f(['no encontrado - ERROR 272', $periodo,  $montoControlPago, $nuevaVentaDetalle['general']['monto_recurrente'], $item, $dataServicios]);
        }

        if($periodo < '202501')
        {
            $year = substr($periodo, 0, 4); // "2024"
            $month = substr($periodo, 4, 2); // "11"

            $montoRecurrente = 0;
            $fechaInicio = "$year-$month-01";
            $fechaFin = date("Y-m-t", strtotime($fechaInicio));

            foreach($dataServicios as $servicio)
            {
                // if(is_null($servicio['SDED_FECHA']) or ($servicio['SDED_FECHA'] >= $fechaInicio and 
                // (is_null($servicio['SDED_FECHA_FIN']) or $servicio['SDED_FECHA_FIN'] >= $fechaFin)))
                $pasa = false;

                if(is_null($servicio['SDED_FECHA']) and is_null($servicio['SDED_FECHA_FIN'])) {
                    $pasa = true;
                }
                else if(!is_null($servicio['SDED_FECHA']) and is_null($servicio['SDED_FECHA_FIN']))
                {
                    if($servicio['SDED_FECHA'] <= $fechaFin) {
                        $pasa = true;
                    }
                }

                if($pasa)
                {
                    $montoRecurrente += round($servicio['SDEN_MONTO'], 2);
                }
            }

            // if(($montoRecurrente != $montoControlPago and $comproMontoTotalSinDesSuspension != $montoRecurrente) or $comproMontoTotalSinDesSuspension < 1)

            if(((string) $montoRecurrente != (string) $montoControlPago and (string) $montoControlPagoSinSuspension != (string) $montoRecurrente) or $montoControlPagoSinSuspension < 1)
            {
                return;
                print_r_f([
                    'message' => 'no encontrado - ERROR 320', 
                    'montoRecurrente' => $montoRecurrente,
                    'comproMontoTotalSinDesSuspension' => $comproMontoTotalSinDesSuspension,
                    'comproMontoTotal' => $comproMontoTotal,
                    'montoControlPago' => $montoControlPago,
                    'periodo' => $periodo,
                    'fechaInicio' => $fechaInicio, 
                    'item' => $item,
                    'dataServicios' => $dataServicios, 
                    'dataComprobante' => $dataComprobante, 'controlPago' => $controlPago]);
            }
        }

        if($periodo === '202412')
        {
            $compro202412 = $sqlServer->select("SELECT * FROM data_ultra_emision_202412 WHERE cod_circuito = ?", [$item['cod_circuito']]);

            if(count($compro202412) != 1)
            {
                print_r_f(['no encontrado - ERROR 329', $item]);
            }

            $auxMoneda = $compro202412[0]['desc_moneda'];

            if($auxMoneda === 'SOLES') {
                $auxMoneda = 'Soles';
            }
            else if($auxMoneda === 'DOLARES') {
                $auxMoneda = 'Dolares';
            }

            if ($auxMoneda !== $nuevaVentaDetalle['general']['moneda']) {
                print_r_f(['no encontrado - ERROR 345', $item]);
            }

            if ((string) $compro202412[0]['SUB_TOTAL'] != (string) $nuevaVentaDetalle['general']['monto_recurrente'])
            {
                print_r_f(['no encontrado - ERROR 353', $compro202412[0],$nuevaVentaDetalle, $item]);
            }

            // print_r_f(['compro202412' => $compro202412]);

            $seComprobo202412 = true;
        }

        // print_r_f(['periodo' => $periodo, 'monto' => $montoControlPago]);
    }

    if(!$seComprobo202412)
    {
        print_r_f(['no encontrado - ERROR 329', $item]);
    }

    // Se debe de eliminar la Venta Detalle Actual

    if(! $esMPLS) {
        print_r_f(['esMPLS' => $esMPLS]);
    }

    foreach($servicioDetalle as $indiceDetalle => $servicio)
    {
        if($servicio['CATV_DESCRIPCION_CONCEPTO'] === 'Instalacion de Servicio Ultra') {
            $servicioDetalle[$indiceDetalle]['CATV_DESCRIPCION_CONCEPTO'] = 'Instalación de Servicio Ultra';
            $servicioDetalle[$indiceDetalle]['tipo_naturaleza'] = '02';
            $servicioDetalle[$indiceDetalle]['cantidad_cuotas'] = 1;
        } else {
            $servicioDetalle[$indiceDetalle]['tipo_naturaleza'] = '01';
            $servicioDetalle[$indiceDetalle]['cantidad_cuotas'] = null;
        }

        $servicioDetalle[$indiceDetalle]['fecha_fin'] = null;

        if($servicio['PROI_TIPO_PRODUCTO_GRUPO'] === 3) {
            $servicioDetalle[$indiceDetalle]['fecha_inicio'] = '2024-12-31';
        } else {
            $servicioDetalle[$indiceDetalle]['fecha_inicio'] = null;

            if($servicio['CATV_DESCRIPCION_CONCEPTO'] === 'Instalacion de Servicio Ultra') {
                $servicioDetalle[$indiceDetalle]['fecha_inicio'] = $servicio['fec_registro'];
                $servicioDetalle[$indiceDetalle]['fecha_fin'] = $servicio['fec_registro'];
            }
        }
    }

    foreach($servicioDetalle as $servicio)
    {
        $nuevaVentaDetalle['detalle'][] = [
            'cod_pedido_ultra' => $item['cod_pedido_ultra'],
            'cod_circuito' => $item['cod_circuito'],
            'desc_concepto' => $servicio['CATV_DESCRIPCION_CONCEPTO'],
            'cod_moneda' => $servicio['SDEI_MONEDA'] == 2 ? '01' : '02',
            'monto' => $servicio['SDEN_MONTO'],
            'cantidad' => $servicio['SDEI_CANTIDAD'],
            'tipo_modalidad' => '02',
            'tipo_naturaleza' => $servicio['tipo_naturaleza'],
            'tipo_emision' => '0' . $servicio['SDEI_TIPO_EMISION'],
            'tipo_cuotas' => '02',
            'cantidad_cuotas' => $servicio['cantidad_cuotas'],
            'fecha_inicio' => $servicio['fecha_inicio'],
            'fecha_fin' => $servicio['fecha_fin'],
            'tipo_producto' => '01'
        ];
    }

    $dataRaw = $sqlServer->select("SELECT RentaMensual FROM data_ultra_raw WHERE CircuitoCod = ? or IdPedido = ?", [$item['cod_circuito'], $item['cod_pedido_ultra']]);

    if(count($dataRaw) != 1)
    {
        print_r_f(['no encontrado - ERROR 329', $dataRaw, $nuevaVentaDetalle['general']['monto_recurrente'], $item]);
    }

    $dataRaw = $dataRaw[0];

    if((string) $dataRaw['RentaMensual'] != (string) $nuevaVentaDetalle['general']['monto_recurrente_202411'])
    {
        print_r_f(['no encontrado - ERROR 339', $dataRaw['RentaMensual'], $nuevaVentaDetalle['general']['monto_recurrente_202411'], $servicioDetalle]);
    }

    // print_r_f(['nuevaVentaDetalle' => $nuevaVentaDetalle]);

    foreach($nuevaVentaDetalle['detalle'] as $indexDetalle => $servicio)
    {
        $nuevaVentaDetalle['detalle'][$indexDetalle]['monto'] = getMontoConIGV($servicio['monto']);
    }

    // print_r_f($nuevaVentaDetalle);

    foreach($nuevaVentaDetalle['detalle'] as $indexDetalle => $servicio)
    {
        $insertQuery = "INSERT INTO " . TABLE_DATA_ULTRA_PROC_DETALLE . " (
            cod_pedido_ultra, cod_circuito, desc_concepto, cod_moneda, monto,
            cantidad, megas_cantidad, tipo_modalidad, tipo_naturaleza, tipo_emision, tipo_cuotas, cantidad_cuotas,
            fecha_inicio, fecha_fin, tipo_producto
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);
        ";

        $insertQueryAux = $insertQuery;

        // print_r_f($insertQuery);

        $params = [
            $servicio['cod_pedido_ultra'],
            $servicio['cod_circuito'],
            $servicio['desc_concepto'],
            $servicio['cod_moneda'],
            $servicio['monto'],
            1,
            $servicio['cantidad'] ?? 0,
            $servicio['tipo_modalidad'],
            $servicio['tipo_naturaleza'],
            $servicio['tipo_emision'],
            $servicio['tipo_cuotas'],
            $servicio['cantidad_cuotas'],
            $servicio['fecha_inicio'],
            $servicio['fecha_fin'],
            $servicio['tipo_producto']
        ];

        // print_r_f($params);

        // Convertir los valores a un formato adecuado para SQL.
        $escapedValues = array_map(function ($value) {
            if (is_null($value)) {
                return 'NULL';
            } elseif (is_string($value)) {
                return "'" . str_replace("'", "''", $value) . "'";
            } elseif (is_numeric($value)) {
                return $value;
            } else {
                throw new Exception("Tipo de dato no soportado: " . gettype($value));
            }
        }, $params);

        // Reemplazar los placeholders (?) con los valores escapados.
        $finalQuery = preg_replace_callback('/\?/', function () use (&$escapedValues) {
            return array_shift($escapedValues);
        }, $insertQueryAux);

        $result = $sqlServer->insert($insertQuery, $params);

        if($result == false and $result !== '')
        {
            echo "Error al insertar datos en la tabla " . TABLE_DATA_ULTRA_PROCESADO;

            print_r_f([$indexDetalle, $finalQuery, $result]);
            print_r_f($result);
        }
    }

    return;

    print_r_f([
        '$nuevaVentaDetalle' => $nuevaVentaDetalle,
        '$item' => $item,
        '$pedidoUltra' => $pedidoUltra,
        '$ventaDetalleActual' => $ventaDetalleActual,
        '$servicioDetalle' => $servicioDetalle,
        '$controlPago' => $controlPago,
        '$dataComprobante' => $dataComprobante
    ]);
}

function getMontoConIGV($monto)
{
    $monto = round($monto * 1.18, 2);
    $montosPermitidos = [175, -40, -50, -25, -94.4, 54.4, -45, -75, -60, 87.51, 87.5];

    if($monto == 175.01)
    {
        $monto = 175;
    }
    else if($monto == -50.01)
    {
        $monto = -50;
    }
    else if ($monto == -24.99)
    {
        $monto = -25;
    }
    else if ($monto == -45.01) {
        $monto = -45;
    }
    else if($monto == -75.01) {
        $monto = -75;
    }
    
    if(in_array($monto, $montosPermitidos))
    {
        return $monto;
    }

    print_r_f(['no encontrado monto', $monto, $montosPermitidos]);

    return $monto;
}

function homologar_producto($producto)
{
    if ($producto === 'Servicio de Internet Ultra') {
        // return 'Servicio de Internet Ultra';
    }

    return $producto;
}

print_r_f('fin :)');

