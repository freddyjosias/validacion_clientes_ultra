<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

const DB_MYSQL_WINCRM_ULTRA = 'wincrm_ultra_uat';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado_uat';
const TABLE_DATA_ULTRA_PROC_DETALLE = 'data_ultra_proc_detalle';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

$resultado = $sqlServer->select("select distinct desc_concepto
from " . TABLE_DATA_ULTRA_PROC_DETALLE);

$queryConceptos = "";
$producostos = [];

foreach($resultado as $item)
{
    $concepto = $mysql->select("SELECT PROI_COD_PRODUCTO, PROV_NOMBRE_PRODUCTO, PROI_TIPO_PRODUCTO_GRUPO FROM CRM_PRODUCTO 
        WHERE PROV_NOMBRE_PRODUCTO = ?", [$item['desc_concepto']]);

    if(count($concepto) === 0)
    {
        if($item['desc_concepto'] === 'Instalación de Servicio Ultra')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION,
            PROD_FECHA_CREACION, PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION,  PROV_TIPO_RENTA, PROI_VELOCIDAD,
            PROI_VISIBLE_OT, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA,  PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_TIPO_PRODUCTO_GRUPO)
            VALUES ('Instalación de Servicio Ultra', 5, 0.0,
                    1, 'migracion', NOW(),
                    'migracion', NOW(), '02',
                    0, 0, 0, '07', 2,
                    0, '11', 2);

                    ";
        }
        else if($item['desc_concepto'] === 'Servicio de Internet Ultra')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION,
            PROD_FECHA_CREACION, PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION, PROC_COD_TIPO_LINEA_NEGOCIO, PROC_COD_TIPO_ESTADO,
            PROD_FECHA_INICIO, PROD_FECHA_FIN, PROI_COD_ECOM, PROV_TIPO_RENTA, PROI_VELOCIDAD, PROV_COD_PROD_ASOC, PROV_NOMBRE_ASOC,
            PROI_VISIBLE_OT, PROI_OBLIG, PROB_NO_MOSTRAR_EN_PEDIDO, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA, DESCRIPCION,
            TIPO_SVA, LINKIMAG, PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_FLAG_CROSS_SELLING, PROI_FLAG_ACTIVACION_NOC, PROI_FLAG_DELIVERY,
            PROI_FLAG_EQUIPOS_SVA, PRO_FAM_EQUIPOS, desc_prod_num_maximo, desc_prod_periodo, flg_proi_facturable, PROI_TIPO_CONCEPTO, PROI_TIPO_COMPROBANTE,
            PROV_CUENTA_CONTABLE_MRC, PROV_CUENTA_CONTABLE_NRC, PROI_TIPO_PRODUCTO_GRUPO)
            VALUES ('Servicio de Internet Ultra', 5, 0.0, 1,
                    'migracion', now(), 'migracion', 
                    now(), null, null, null, 
                    null, null, '01', 600, null, 
                    null, 0, 0, 0, 0, 
                    null, 3, null, null, null, 1, 
                    '01', 1, 1, 0, 
                    null, null, null, null, 1, 
                    null, null, null, null, 3);

            ";
        }
        else if($item['desc_concepto'] === 'Decremento de renta')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION, PROD_FECHA_CREACION,
                        PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION, PROC_COD_TIPO_LINEA_NEGOCIO, PROC_COD_TIPO_ESTADO, PROD_FECHA_INICIO,
                        PROD_FECHA_FIN, PROI_COD_ECOM, PROV_TIPO_RENTA, PROI_VELOCIDAD, PROV_COD_PROD_ASOC, PROV_NOMBRE_ASOC, PROI_VISIBLE_OT,
                        PROI_OBLIG, PROB_NO_MOSTRAR_EN_PEDIDO, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA, DESCRIPCION,
                        TIPO_SVA, LINKIMAG, PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_FLAG_CROSS_SELLING, PROI_FLAG_ACTIVACION_NOC,
                        PROI_FLAG_DELIVERY, PROI_FLAG_EQUIPOS_SVA, PRO_FAM_EQUIPOS, desc_prod_num_maximo, desc_prod_periodo, flg_proi_facturable,
                        PROI_TIPO_CONCEPTO, PROI_TIPO_COMPROBANTE, PROV_CUENTA_CONTABLE_MRC, PROV_CUENTA_CONTABLE_NRC, PROI_TIPO_PRODUCTO_GRUPO)
            VALUES ( 'Decremento de renta', null, 0.0, 1,
                    'migracion', NOW(), 'migracion',
                    NOW(), null, null, null,
                    null, NULL, '00', 0, null,
                    null, 0, null, null, 0,
                    '07', null, null, null, null, 0,
                    '11', null, null, null,
                    null, null, null, null, null,
                    null, null, null, null, 2);

                    ";
        }
        else if($item['desc_concepto'] === 'Incremento de Renta')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION, PROD_FECHA_CREACION,
                        PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION, PROC_COD_TIPO_LINEA_NEGOCIO, PROC_COD_TIPO_ESTADO, PROD_FECHA_INICIO,
                        PROD_FECHA_FIN, PROI_COD_ECOM, PROV_TIPO_RENTA, PROI_VELOCIDAD, PROV_COD_PROD_ASOC, PROV_NOMBRE_ASOC, PROI_VISIBLE_OT,
                        PROI_OBLIG, PROB_NO_MOSTRAR_EN_PEDIDO, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA, DESCRIPCION,
                        TIPO_SVA, LINKIMAG, PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_FLAG_CROSS_SELLING, PROI_FLAG_ACTIVACION_NOC,
                        PROI_FLAG_DELIVERY, PROI_FLAG_EQUIPOS_SVA, PRO_FAM_EQUIPOS, desc_prod_num_maximo, desc_prod_periodo, flg_proi_facturable,
                        PROI_TIPO_CONCEPTO, PROI_TIPO_COMPROBANTE, PROV_CUENTA_CONTABLE_MRC, PROV_CUENTA_CONTABLE_NRC, PROI_TIPO_PRODUCTO_GRUPO)
            VALUES ( 'Incremento de Renta', null, 0.0, 1,
                    'migracion', NOW(), 'migracion',
                    NOW(), null, null, null,
                    null, NULL, '00', 0, null,
                    null, 0, null, null, 0,
                    '07', null, null, null, null, 0,
                    '11', null, null, null,
                    null, null, null, null, null,
                    null, null, null, null, 2);

                    ";
        }
        else if($item['desc_concepto'] === 'Migracion a Ultra 600')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION, PROD_FECHA_CREACION, PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION, PROC_COD_TIPO_LINEA_NEGOCIO, PROC_COD_TIPO_ESTADO, PROD_FECHA_INICIO, PROD_FECHA_FIN, PROI_COD_ECOM, PROV_TIPO_RENTA, PROI_VELOCIDAD, PROV_COD_PROD_ASOC, PROV_NOMBRE_ASOC, PROI_VISIBLE_OT, PROI_OBLIG, PROB_NO_MOSTRAR_EN_PEDIDO, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA, DESCRIPCION, TIPO_SVA, LINKIMAG, PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_FLAG_CROSS_SELLING, PROI_FLAG_ACTIVACION_NOC, PROI_FLAG_DELIVERY, PROI_FLAG_EQUIPOS_SVA, PRO_FAM_EQUIPOS, desc_prod_num_maximo, desc_prod_periodo, flg_proi_facturable, PROI_TIPO_CONCEPTO, PROI_TIPO_COMPROBANTE, PROV_CUENTA_CONTABLE_MRC, PROV_CUENTA_CONTABLE_NRC, PROI_TIPO_PRODUCTO_GRUPO) VALUES ('Migracion a Ultra 600', 5, 0.000000, 1, 'pabucci', '2024-12-18 16:28:30', 'pabucci', '2024-12-18 16:28:30', null, null, null, null, 5826, '01', 600, null, null, 0, 0, 0, 0, null, 3, null, null, null, 1, '01', 1, 1, 0, null, null, null, null, 1, null, null, null, null, 3);

                    ";


        }
        else if($item['desc_concepto'] === 'Migracion a Ultra 800')
        {
            $queryConceptos .= "INSERT INTO CRM_PRODUCTO (PROV_NOMBRE_PRODUCTO, PROI_COD_PRODUCTO_PADRE, PAQI_PRECIO, PROI_EST_REGISTRO, PROV_USUARIO_CREACION, PROD_FECHA_CREACION, PROV_USUARIO_ACTUALIZACION, PROD_FECHA_ACTUALIZACION, PROC_COD_TIPO_LINEA_NEGOCIO, PROC_COD_TIPO_ESTADO, PROD_FECHA_INICIO, PROD_FECHA_FIN, PROI_COD_ECOM, PROV_TIPO_RENTA, PROI_VELOCIDAD, PROV_COD_PROD_ASOC, PROV_NOMBRE_ASOC, PROI_VISIBLE_OT, PROI_OBLIG, PROB_NO_MOSTRAR_EN_PEDIDO, PROI_VALIDACION_AUT, PROV_TIPO_PRODUCTO, PROI_OBLIGCATEGORIA, DESCRIPCION, TIPO_SVA, LINKIMAG, PROB_ENVIAR_CORREO, PROV_TIPO_CONFIG_PRODUCTO, PROI_FLAG_CROSS_SELLING, PROI_FLAG_ACTIVACION_NOC, PROI_FLAG_DELIVERY, PROI_FLAG_EQUIPOS_SVA, PRO_FAM_EQUIPOS, desc_prod_num_maximo, desc_prod_periodo, flg_proi_facturable, PROI_TIPO_CONCEPTO, PROI_TIPO_COMPROBANTE, PROV_CUENTA_CONTABLE_MRC, PROV_CUENTA_CONTABLE_NRC, PROI_TIPO_PRODUCTO_GRUPO) VALUES ('Migracion a Ultra 800', 5, 0.000000, 1, 'pabucci', '2024-12-18 16:28:30', 'pabucci', '2024-12-18 16:28:30', null, null, null, null, 5826, '01', 600, null, null, 0, 0, 0, 0, null, 3, null, null, null, 1, '01', 1, 1, 0, null, null, null, null, 1, null, null, null, null, 3);

                    ";


        }
        else
        {
            print_r_f(['error', $item]);
        }

        continue;
    }
    else if (count($concepto) > 1)
    {
        print_r_f(['error', $item]);
    }

    $producostos[] = $concepto[0];

    // print_r_f([$item, $concepto]);
}

if($queryConceptos !== "")
{
    print_r_f($queryConceptos);
}

$queryConceptos = "";

$resultado = $sqlServer->select("SELECT p.*
FROM " . TABLE_DATA_ULTRA_PROCESADO . " p
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
AND concat(p.cod_circuito, '-', p.cod_pedido_ultra) in 
(select concat(a.cod_circuito, '-', a.cod_pedido_ultra) from " . TABLE_DATA_ULTRA_PROC_DETALLE . " a)");

foreach($resultado as $item)
{
    $detalles = $sqlServer->select("SELECT p.cod_pedido_pf_ultra, d.*
    FROM " . TABLE_DATA_ULTRA_PROCESADO . " p
    INNER JOIN " . TABLE_DATA_ULTRA_PROC_DETALLE . " d ON p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
    where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
    AND p.cod_pedido_ultra = ? and p.cod_circuito = ?", [$item['cod_pedido_ultra'], $item['cod_circuito']]);

    if(count($detalles) === 0)
    {
        print_r_f(['no encontrado', $item]);
    }

    foreach($detalles as $indiceDetalle => $detalle)
    {
        $productoEncontrado = false;

        foreach($producostos as $producto)
        {
            if($producto['PROV_NOMBRE_PRODUCTO'] === $detalle['desc_concepto'])
            {
                $detalles[$indiceDetalle]['cod_producto'] = $producto['PROI_COD_PRODUCTO'];
                $productoEncontrado = true;
                break;
            }
        }

        if(!$productoEncontrado)
        {
            print_r_f(['no encontrado', $detalle]);
        }

        $detalles[$indiceDetalle]['ya_existe'] = false;
    }

    $detalleVentaActual = $mysql->select("SELECT D.DOCI_COD_PEDIDO_REF, VD.VTDI_COD_VENTA_DETALLE, VD.VTDI_COD_VENTA,
    VD.VTDI_COD_TARIFARIO, VD.VTDI_CANTIDAD, VD.VTDC_COD_TIPO_MONEDA, VD.VTDN_PRECIO,
    VD.VTDC_COD_TIPO_MODALIDAD, VD.VTDC_COD_TIPO_NATURALEZA, VD.TARC_COD_TIPO_RENTA, 
    VD.VTDC_COT_TIPO_CUOTAS, VD.VTDI_CANTIDAD_CUOTAS, VD.VTDI_COD_PAQUETE_PRODUCTO,
    VD.VTDI_EST_REGISTRO, VD.VTDV_USUARIO_CREACION, VD.VTDD_FECHA_CREACION, VD.VTDV_USUARIO_ACTUALIZACION,
    VD.VTDD_FECHA_ACTUALIZACION, VD.VTDD_FECHA_INICIO, VD.VTDD_FECHA_FIN, VD.VTDV_TIPO_PRODUCTO,
    P.PROV_NOMBRE_PRODUCTO, false as ya_relacionado
    FROM CRM_DOCUMENTO D
    INNER JOIN CRM_VENTA V ON D.DOCI_COD_DOCUMENTO = V.VTAI_COD_DOCUMENTO
    INNER JOIN CRM_VENTA_DETALLE VD ON V.VTAI_COD_VENTA = VD.VTDI_COD_VENTA
    INNER JOIN CRM_PRODUCTO P ON VD.VTDI_COD_TARIFARIO = P.PROI_COD_PRODUCTO
    WHERE D.DOCI_COD_PEDIDO_REF = ? AND VD.VTDI_EST_REGISTRO = 1;", [$item['cod_pedido_pf_ultra']]);

    foreach($detalles as $indiceDetalle => $itemDetalle)
    {
        $yaExiste = false;
        $cantidadEncontrada = 0;

        foreach($detalleVentaActual as $ventDetalle => $detalleVenta)
        {
            $detalleVentaActual[$ventDetalle]['ya_relacionado'] = $detalleVentaActual[$ventDetalle]['ya_relacionado'] !== true ? false : true;

            if($detalleVenta['VTDI_COD_TARIFARIO'] == $itemDetalle['cod_producto'] and
            $detalleVenta['VTDC_COD_TIPO_MONEDA'] == $itemDetalle['cod_moneda'] and
            $detalleVenta['VTDC_COD_TIPO_MODALIDAD'] == $itemDetalle['tipo_modalidad'] and
            $detalleVenta['VTDC_COD_TIPO_NATURALEZA'] == $itemDetalle['tipo_naturaleza'] and
            $detalleVenta['TARC_COD_TIPO_RENTA'] == $itemDetalle['tipo_emision'] and
            $detalleVenta['VTDC_COT_TIPO_CUOTAS'] == $itemDetalle['tipo_cuotas'] and
            $detalleVenta['VTDI_CANTIDAD_CUOTAS'] == $itemDetalle['cantidad_cuotas'] and
            $detalleVenta['VTDD_FECHA_INICIO'] == $itemDetalle['fecha_inicio'] and
            $detalleVenta['VTDD_FECHA_FIN'] == $itemDetalle['fecha_fin']
            )
            {
                if($detalles[$indiceDetalle]['ya_existe']) {
                    print_r_f(['ya existe', $itemDetalle]);
                }

                if($detalleVentaActual[$ventDetalle]['ya_relacionado'])
                {
                    print_r_f(['ya relacionado', $itemDetalle]);
                }

                $detalles[$indiceDetalle]['ya_existe'] = true;
                $detalleVentaActual[$ventDetalle]['ya_relacionado'] = true;
                $yaExiste = true;
                $cantidadEncontrada++;
            }
        }

        if($cantidadEncontrada > 1)
        {
            print_r_f(['encontrado', $itemDetalle]);
        }
    }

    foreach($detalleVentaActual as $detalleVenta)
    {
        if($detalleVenta['ya_relacionado'] === false)
        {
            $queryConceptos .= "UPDATE CRM_VENTA_DETALLE SET VTDI_EST_REGISTRO = 0 WHERE VTDI_COD_VENTA_DETALLE = " . $detalleVenta['VTDI_COD_VENTA_DETALLE'] . ";

            ";
        }
    }

    $venta = $mysql->select("SELECT V.VTAI_COD_VENTA
    FROM CRM_DOCUMENTO D
    INNER JOIN CRM_VENTA V ON D.DOCI_COD_DOCUMENTO = V.VTAI_COD_DOCUMENTO
    WHERE D.DOCI_COD_PEDIDO_REF = ?;", [$item['cod_pedido_pf_ultra']]);

    if(count($venta) != 1)
    {
        print_r_f(['error', $item]);
    }

    $venta = $venta[0];

    foreach($detalles as $itemDetalle)
    {
        if(!$itemDetalle['ya_existe'])
        {
            $queryConceptos .= "INSERT INTO CRM_VENTA_DETALLE (VTDI_COD_VENTA, VTDI_COD_TARIFARIO, VTDI_CANTIDAD, VTDC_COD_TIPO_MONEDA, VTDN_PRECIO, VTDC_COD_TIPO_MODALIDAD, VTDC_COD_TIPO_NATURALEZA, TARC_COD_TIPO_RENTA, VTDC_COT_TIPO_CUOTAS, VTDI_CANTIDAD_CUOTAS, VTDI_COD_PAQUETE_PRODUCTO, VTDI_EST_REGISTRO, VTDV_USUARIO_CREACION, VTDD_FECHA_CREACION, VTDV_USUARIO_ACTUALIZACION, VTDD_FECHA_ACTUALIZACION, VTDD_FECHA_INICIO, VTDD_FECHA_FIN, VTDV_TIPO_PRODUCTO)
            VALUES (" . $venta['VTAI_COD_VENTA'] . ", " . $itemDetalle['cod_producto'] . ", " . $itemDetalle['cantidad'] . ", '" . $itemDetalle['cod_moneda'] . "', " . $itemDetalle['monto'] . ", '" . $itemDetalle['tipo_modalidad'] . "', '" . $itemDetalle['tipo_naturaleza'] . "', '" . $itemDetalle['tipo_emision'] . "', '" . $itemDetalle['tipo_cuotas'] . "', " . ($itemDetalle['cantidad_cuotas'] ?? 'NULL') . ", " . $itemDetalle['cod_producto'] . ", 1, 'migracion', NOW(), 'migracion', NOW(), " . ($itemDetalle['fecha_inicio'] == null ? 'NULL' : "'" . $itemDetalle['fecha_inicio'] . "'") . ", " . 
            ($itemDetalle['fecha_fin'] == null ? 'NULL' : "'" . $itemDetalle['fecha_fin'] . "'") . ", '" . $itemDetalle['tipo_producto'] . "');
            
            ";
        }

    }

    // print_r_f([$queryConceptos, $detalles, $detalleVentaActual]);
    // print_r_f($item);
}

print_r_f($queryConceptos);

print_r_f($resultado);
print_r_f($producostos);
print_r_f($producostos);