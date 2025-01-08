<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/get_razon_social.php';
require_once __DIR__ . '/helper.php';

const DB_MYSQL_WINCRM_ULTRA = 'db_wincrm_prod';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado_prod';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

// $resultados = $mysql->select("SELECT DISTINCT P.PEDI_COD_PEDIDO, C.CLIV_NUMERO_DOCUMENTO, C.CLIV_NOMBRES, C.CLIV_APELLIDO_PATERNO, C.CLIV_APELLIDO_MATERNO
// FROM CRM_PEDIDO P
// INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
// where flg_validado = 0");

$resultados = $sqlServer->select("SELECT d.cod_circuito, d.cod_pedido_ultra, d.cod_pedido_pf_ultra,
    d.id_data, d.flg_nombre_validado, d.flg_check_nom_v2, CC.CLIV_NRO_RUC ecom_nro_documento, CC.CLIV_RAZON_SOCIAL ecom_razon_social,
    d.nro_documento proce_nro_documento,
    CASE WHEN d.nombres <> '' then d.nombres else d.razon_social end proce_nombres,
    CASE WHEN d.ape_paterno <> '' then d.ape_paterno else '.' end proce_ape_paterno,
    CASE WHEN d.ape_materno <> '' then d.ape_materno else '.' end proce_ape_materno,
    e.cli_nro_doc emision_nro_documento, e.desc_cliente emision_razon_social, desc_observacion_activacion
    FROM " . TABLE_DATA_ULTRA_PROCESADO . " d
    INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
    LEFT JOIN data_ultra_emision_prod e ON d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
    inner join PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
    INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
    INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE CC ON EP.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
    WHERE d.flg_nombre_validado = 0 order by d.id_data");

$cantidadAcierto = 0;
$cantidadError = 0;
$query = '';
$cantidadQuery = 0;

$except = [
    // '00320768', // Validar cambio de nombres y CE en prod
    // '05288399', // Exluido por el nombre de Hernan -> Herman
    // '06383156', // Observador, razon social es de empresa y es un CE
    // '06589920', // Se considera correcto
    // '07186905', // Se considera correcto
    // '07614982', // Se considera correcto
    // '001804896', // Se considera correcto
];

$_GLOBAL['errores'] = [];

$querySQLMySQL = '';

$querySQLMySQLCRMExp = '';

$querySQLMySQLWinforce = '';

// print_r_f($resultados[0]);
// print_r_f(count($resultados));

foreach($resultados as $index => $fila)
{
    if(in_array($fila['proce_nro_documento'], $except)) continue;

    // $datosFinales = [
    //     'nro_documento' => $fila['proce_nro_documento'],
    //     'nombres' => $fila['proce_nombres'],
    //     'ape_paterno' => $fila['proce_ape_paterno'],
    //     'ape_materno' => $fila['proce_ape_materno']
    // ];

    $fila['proce_nombres'] = str_replace('  ', ' ', $fila['proce_nombres']);
    $fila['proce_nombres'] = str_replace('  ', ' ', $fila['proce_nombres']);

    $coincideInfoEcomProcesado = false;
    $coincideInfoProcesadoEmision = false;

    $fila['proce_intento'] = $fila['proce_nombres'];
    $fila['proce_intento'] .= $fila['proce_ape_paterno'] != '.' ? ' ' . $fila['proce_ape_paterno'] : '';
    $fila['proce_intento'] .= $fila['proce_ape_materno'] != '.' ? ' ' . $fila['proce_ape_materno'] : '';

    $fila['proce_intento_completo'] = $fila['proce_nombres'] . ' ' . $fila['proce_ape_paterno'] . ' ' . $fila['proce_ape_materno'];

    $fila['ecom_razon_social'] = trim($fila['ecom_razon_social']);

    $fila['ecom_razon_social_original'] = $fila['ecom_razon_social'];

    $fila['ecom_razon_social'] = str_replace(',', ' ', $fila['ecom_razon_social']);
    $fila['ecom_razon_social'] = str_replace('  ', ' ', $fila['ecom_razon_social']);
    $fila['ecom_razon_social'] = str_replace('  ', ' ', $fila['ecom_razon_social']);

    $ordenandoNombresPorNombres = true;

    if($fila['ecom_razon_social'] == $fila['proce_intento'])
    {
        $coincideInfoEcomProcesado = true;
    }
    else
    {
        $fila['proce_intento'] = $fila['proce_ape_paterno'] != '.' ? $fila['proce_ape_paterno'] : '';
        $fila['proce_intento'] .= $fila['proce_ape_materno'] != '.' ? ' ' . $fila['proce_ape_materno'] : '';
        $fila['proce_intento'] .= ' ' . $fila['proce_nombres'];

        $fila['proce_intento_completo'] = $fila['proce_ape_paterno'] . ' ' . $fila['proce_ape_materno'] . ' ' . $fila['proce_nombres'];

        if($fila['ecom_razon_social'] == $fila['proce_intento'])
        {
            $coincideInfoEcomProcesado = true;
        }

        $ordenandoNombresPorNombres = false;
    }

    if(!$coincideInfoEcomProcesado)
    {
        $contieneNombres = strpos($fila['ecom_razon_social'], $fila['proce_nombres']) !== false;
        $contieneApePaterno = strpos($fila['ecom_razon_social'], $fila['proce_ape_paterno']) !== false;
        $contieneApeMaterno = strpos($fila['ecom_razon_social'], $fila['proce_ape_materno']) !== false;

        $scriptSQL = '';

        if($contieneNombres and $contieneApeMaterno and $contieneApePaterno)
        {
            $auxRzOri = strpos($fila['ecom_razon_social_original'], ',') !== false;

            if($auxRzOri)
            {
                $auxRzOri = explode(',', $fila['ecom_razon_social_original']);
                $auxRzOri[0] = trim($auxRzOri[0]);
                $auxRzOri[1] = trim($auxRzOri[1]);

                $apellidos = null;
                
                if($auxRzOri[0] == $fila['proce_nombres'])
                {
                    $apellidos = $auxRzOri[1];
                }
                else if($auxRzOri[1] == $fila['proce_nombres'])
                {
                    $apellidos = $auxRzOri[0];
                }

                if($apellidos != null)
                {
                    $auxPosiApellidos = strpos($apellidos, $fila['proce_ape_paterno']);
                    $apellidos = trim(str_replace($fila['proce_ape_paterno'], '', $apellidos));
                    $auxPosiApellidosMaterno = strpos($apellidos, $fila['proce_ape_materno']);

                    if($auxPosiApellidos === 0 and $auxPosiApellidosMaterno === 0 and $apellidos != $fila['proce_ape_materno'])
                    {
                        $contieneApeMaterno = false;
                    }
                }
            }
        }

        if(!$contieneNombres)
        {
            $auxApellido = $fila['ecom_razon_social'];

            if($fila['proce_ape_paterno'] != '.' or $fila['proce_ape_materno'] != '.')
            {
                $auxApellido = str_replace($fila['proce_ape_paterno'], '', $auxApellido);
                $auxApellido = str_replace($fila['proce_ape_materno'], '', $auxApellido);
                $auxApellido = trim($auxApellido);
            }

            $scriptSQL .= "UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET nombres = '" . $auxApellido . "' WHERE id_data = '" . $fila['id_data'] . "'; <br>";
        }
        else if(!$contieneApePaterno)
        {
            $auxApellido = $fila['ecom_razon_social'];
            $auxApellido = str_replace($fila['proce_nombres'], '', $auxApellido);
            $auxApellido = str_replace($fila['proce_ape_materno'], '', $auxApellido);
            $auxApellido = trim($auxApellido);

            $scriptSQL .= "UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET ape_paterno = '" . $auxApellido . "' WHERE id_data = '" . $fila['id_data'] . "'; <br>";
        }
        else if(!$contieneApeMaterno)
        {
            $auxApellido = $fila['ecom_razon_social'];
            $auxApellido = str_replace($fila['proce_nombres'], '', $auxApellido);
            $auxApellido = str_replace($fila['proce_ape_paterno'], '', $auxApellido);
            $auxApellido = trim($auxApellido);

            $scriptSQL .= "UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET ape_materno = '" . $auxApellido . "' WHERE id_data = '" . $fila['id_data'] . "'; <br>";
        }

        print_r_f([
            'linea' => 'line 78 - ' . $index,
            'contieneNombres' => $contieneNombres,
            'contieneApePaterno' => $contieneApePaterno,
            'contieneApeMaterno' => $contieneApeMaterno,
            'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
            'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
            'fila' => $fila,
            'scriptSQL' => $scriptSQL
        ]);
    }

    $fila['emision_razon_social'] = trim($fila['emision_razon_social']); 
    $fila['emision_intento'] = $fila['emision_razon_social'];
    $fila['emision_intento'] = explode(') - ', $fila['emision_intento']);

    if(count($fila['emision_intento']) == 2)
    {
        $fila['emision_intento'] = trim($fila['emision_intento'][1]);
    } else {

        if($fila['desc_observacion_activacion'] == 'No tiene comprobante en el 12/2024')
        {
            $fila['emision_intento'] = $fila['ecom_razon_social'];
            $fila['emision_nro_documento'] = $fila['ecom_nro_documento'];
        }
        else {
            print_r_f([
                'linea' => 'line 92 - ' . $index,
                'fila' => $fila
            ]);
        }
    }

    $fila['emision_intento'] = normalizarTextoCharcater($fila['emision_intento']);
    $fila['emision_intento'] = str_replace('  ', ' ', $fila['emision_intento']);

    $auxEmisionIntento = $fila['emision_intento'];
    $auxEmisionIntento = explode(',', $auxEmisionIntento);

    if(count($auxEmisionIntento) == 2)
    {
        $auxEmisionIntento[0] = trim($auxEmisionIntento[0]);
        $auxEmisionIntento[1] = trim($auxEmisionIntento[1]);
        $fila['emision_intento'] = $auxEmisionIntento[0] . ' ' . $auxEmisionIntento[1];

        if($fila['emision_intento'] == $fila['proce_intento'])
        {
            $coincideInfoProcesadoEmision = true;
        }
        else
        {
            $fila['emision_intento'] = $auxEmisionIntento[1] . ' ' . $auxEmisionIntento[0];

            if($fila['emision_intento'] == $fila['proce_intento'])
            {
                $coincideInfoProcesadoEmision = true;
            }
        }
    }
    else if($fila['emision_intento'] == $fila['proce_intento'])
    {
        $coincideInfoProcesadoEmision = true;
    }

    if(!$coincideInfoProcesadoEmision)
    { 
        var_dump($fila['emision_intento']);
        var_dump($fila['proce_intento']);
        print_r_f([
            'linea' => 'line 125 - ' . $index,
            'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
            'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
            'fila' => $fila
        ]);
    }

    $pedido = $mysql->select("SELECT DISTINCT C.CLIV_NUMERO_DOCUMENTO, C.CLIV_NOMBRES, C.CLIV_APELLIDO_PATERNO, C.CLIV_APELLIDO_MATERNO
    FROM CRM_PEDIDO P
    INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
    WHERE P.PEDI_COD_PEDIDO = ?", [$fila['cod_pedido_pf_ultra']]);

    if(count($pedido) != 1)
    {
        print_r_f([
            'linea' => 'line 125 - ' . $index,
            'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
            'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
            'fila' => $fila,
            'pedido' => $pedido
        ]);
    }

    $pedido = $pedido[0];

    $coincideInfoPedido = false;

    if($ordenandoNombresPorNombres) {
        $fila['pedido_intento'] = $pedido['CLIV_NOMBRES'] . ' ' . $pedido['CLIV_APELLIDO_PATERNO'] . ' ' . $pedido['CLIV_APELLIDO_MATERNO'];
    } else {
        $fila['pedido_intento'] = $pedido['CLIV_APELLIDO_PATERNO'] . ' ' . $pedido['CLIV_APELLIDO_MATERNO'] . ' ' . $pedido['CLIV_NOMBRES'];
    }

    if($fila['pedido_intento'] == $fila['proce_intento_completo'] and $pedido['CLIV_NOMBRES'] == $fila['proce_nombres'] and 
    $pedido['CLIV_APELLIDO_PATERNO'] == $fila['proce_ape_paterno'] and $pedido['CLIV_APELLIDO_MATERNO'] == $fila['proce_ape_materno'])
    {
        $coincideInfoPedido = true;
    }

    if(!$coincideInfoPedido and $fila['flg_check_nom_v2'] == 0)
    {
        $querySQL = 'UPDATE ' . TABLE_DATA_ULTRA_PROCESADO . ' SET flg_check_nom_v2 = 1 WHERE id_data = ' . $fila['id_data'] . '; <br>';

        print_r_f([
            'linea' => 'line 169 - ' . $index,
            'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
            'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
            'coincideInfoPedido' => $coincideInfoPedido,
            'fila' => $fila,
            'pedido' => $pedido,
            'querySQL' => $querySQL
        ]);
    }
    else if(!$coincideInfoPedido and $fila['flg_check_nom_v2'] == 1)
    {
        $auxRazonSocial = $fila['proce_nombres'] . ' ' . $fila['proce_ape_paterno'] . ' ' . $fila['proce_ape_materno'];
        $auxRazonSocial = substr($auxRazonSocial, 0, 100);
        $auxApellidos = $fila['proce_ape_paterno'] . ' ' . $fila['proce_ape_materno'];

        if($fila['proce_nombres'] !== $pedido['CLIV_NOMBRES'])
        {
            $querySQLMySQL .= "UPDATE CRM_CLIENTE SET CLIV_NOMBRES = '" . $fila['proce_nombres'] . "' WHERE CLIV_NUMERO_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQL .= "UPDATE CRM_PEDIDO SET PEDC_NOMBRES_APELLIDOS = '" . $fila['proce_nombres'] . "' WHERE PEDV_NUM_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLWinforce .= "UPDATE tp_clientes SET cli_nom = '" . $fila['proce_nombres'] . "' WHERE cli_num_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";
        }

        if($fila['proce_ape_paterno'] !== $pedido['CLIV_APELLIDO_PATERNO'])
        {
            $querySQLMySQL .= "UPDATE CRM_CLIENTE SET CLIV_APELLIDO_PATERNO = '" . $fila['proce_ape_paterno'] . "' WHERE CLIV_NUMERO_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQL .= "UPDATE CRM_PEDIDO SET PEDV_APE_PATERNO = '" . $fila['proce_ape_paterno'] . "' WHERE PEDV_NUM_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLWinforce .= "UPDATE tp_clientes SET cli_ape_pat = '" . $fila['proce_ape_paterno'] . "' WHERE cli_num_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";
        }
        if($fila['proce_ape_materno'] !== $pedido['CLIV_APELLIDO_MATERNO'])
        {
            $querySQLMySQL .= "UPDATE CRM_CLIENTE SET CLIV_APELLIDO_MATERNO = '" . $fila['proce_ape_materno'] . "' WHERE CLIV_NUMERO_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQL .= "UPDATE CRM_PEDIDO SET PEDV_APE_MATERNO = '" . $fila['proce_ape_materno'] . "' WHERE PEDV_NUM_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLWinforce .= "UPDATE tp_clientes SET cli_ape_mat = '" . $fila['proce_ape_materno'] . "' WHERE cli_num_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";
        }

        $querySQLMySQLCRMExp .= "UPDATE ventas_general SET nombre = '" . $auxRazonSocial . "', razon_social = '" . $auxRazonSocial . "' WHERE nro_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

        $querySQLMySQLCRMExp .= "UPDATE pedidos SET cli_nom = '" . $fila['proce_nombres'] . "', cli_ape = '" . $auxApellidos . "', cli_razonSocial = '" . $auxRazonSocial . "' WHERE cli_nroDoc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

        $querySQLMySQLCRMExp .= "UPDATE winpe_registro_llamada SET nombre = '" . $fila['proce_nombres']. "', apellido = '" . $auxApellidos . "', razon_social = '" . $auxRazonSocial . "' WHERE nro_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

        $except[] = $fila['proce_nro_documento'];

        if($fila['id_data'] == -1)
        {
            print_r_f([$querySQLMySQL, $querySQLMySQLWinforce, $querySQLMySQLCRMExp]);
        }

        continue;
    }

    if($pedido['CLIV_NUMERO_DOCUMENTO'] !== $fila['proce_nro_documento'] or 
    $pedido['CLIV_NUMERO_DOCUMENTO'] !== $fila['ecom_nro_documento'] or 
    $pedido['CLIV_NUMERO_DOCUMENTO'] !== $fila['emision_nro_documento'])
    {
        $docsAnalizados = ['00320768', 'AG774859', '20190065', '01655107', '945609', 'A9155857', 'AUN B49521', '00000733531', '00000574646', '00353411',
        '20211001', '00001262357'];

        if($fila['ecom_nro_documento'] == $fila['emision_nro_documento'] and $fila['ecom_nro_documento'] != $fila['proce_nro_documento']) {
            $querySQL = "UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET nro_documento = '" . $fila['ecom_nro_documento'] . "' WHERE id_data = '" . $fila['id_data'] . "'; <br>";
        }
        else if($fila['ecom_nro_documento'] == $fila['proce_nro_documento'] and $fila['ecom_nro_documento'] == $fila['emision_nro_documento'] and in_array($fila['ecom_nro_documento'], $docsAnalizados))
        {
            $querySQLMySQL .= "UPDATE CRM_CLIENTE SET CLIV_NUMERO_DOCUMENTO = '" . $fila['proce_nro_documento'] . "' WHERE CLIV_NUMERO_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQL .= "UPDATE CRM_PEDIDO SET PEDV_NUM_DOCUMENTO = '" . $fila['proce_nro_documento'] . "' WHERE PEDV_NUM_DOCUMENTO = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLWinforce .= "UPDATE tp_clientes SET cli_num_doc = '" . $fila['proce_nro_documento'] . "' WHERE cli_num_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLCRMExp .= "UPDATE ventas_general SET nro_doc = '" . $fila['proce_nro_documento'] . "' WHERE nro_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLCRMExp .= "UPDATE pedidos SET cli_nroDoc = '" . $fila['proce_nro_documento'] . "' WHERE cli_nroDoc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLCRMExp .= "UPDATE winpe_registro_llamada SET nro_doc = '" . $fila['proce_nro_documento']. "' WHERE nro_doc = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $querySQLMySQLCRMExp .= "UPDATE bo_valida_reg SET dni_cliente = '" . $fila['proce_nro_documento']. "' WHERE dni_cliente = '" . $pedido['CLIV_NUMERO_DOCUMENTO'] . "';\n";

            $except[] = $fila['proce_nro_documento'];

            continue;
        }

        print_r_f([
            'linea' => 'line 168 - ' . $index,
            'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
            'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
            'coincideInfoPedido' => $coincideInfoPedido,
            'fila' => $fila,
            'pedido' => $pedido,
            'querySQL' => $querySQL ?? '',
            'sql' => $querySQLMySQL . $querySQLMySQLWinforce . $querySQLMySQLCRMExp
        ]);
    }

    $conincidenciaTotal = false;

    if($fila['pedido_intento'] == $fila['proce_intento_completo'] and
    $fila['proce_intento'] == $fila['emision_intento'] and
    $fila['proce_intento'] == $fila['ecom_razon_social'] and
    $fila['proce_nombres'] == $pedido['CLIV_NOMBRES'] and
    $fila['proce_ape_paterno'] == $pedido['CLIV_APELLIDO_PATERNO'] and
    $fila['proce_ape_materno'] == $pedido['CLIV_APELLIDO_MATERNO'])
    {
        $conincidenciaTotal = true;
    }

    if($conincidenciaTotal) {
        $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET flg_nombre_validado = 1 WHERE id_data = ?", [$fila['id_data']]);
        continue;
    }

    print_r_f([
        'linea' => 'line 86 - ' . $index,
        'coincideInfoEcomProcesado' => $coincideInfoEcomProcesado,
        'coincideInfoProcesadoEmision' => $coincideInfoProcesadoEmision,
        'coincideInfoPedido' => $coincideInfoPedido,
        'conincidenciaTotal' => $conincidenciaTotal,
        'fila' => $fila,
        'pedido' => $pedido
    ]);

    print_r_f([$datosFinales, $fila]);
}


/*
if($query != '')
{
    print_r_f($query);
}

$nombreArchivo = "queries/wincrm_db_" . date("Y-m-d_H-i-s") . ".txt";

$archivo = fopen($nombreArchivo, "w");

if ($archivo)
{
    fwrite($archivo, $querySQLMySQL);
    fclose($archivo);
}

$nombreArchivo = "queries/winforce_db_" . date("Y-m-d_H-i-s") . ".txt";

$archivo = fopen($nombreArchivo, "w");

if ($archivo)
{
    fwrite($archivo, $querySQLMySQLWinforce);
    fclose($archivo);
}

$nombreArchivo = "queries/crm_exp_db_" . date("Y-m-d_H-i-s") . ".txt";

$archivo = fopen($nombreArchivo, "w");

if ($archivo)
{
    fwrite($archivo, $querySQLMySQLCRMExp);
    fclose($archivo);
} */

print_r_f('ok :)');
