<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/get_razon_social.php';

const DB_MYSQL_WINCRM_ULTRA = 'wincrm_ultra_uat';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado_uat';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

$resultados = $mysql->select("SELECT DISTINCT P.PEDI_COD_PEDIDO, C.CLIV_NUMERO_DOCUMENTO, C.CLIV_NOMBRES, C.CLIV_APELLIDO_PATERNO, C.CLIV_APELLIDO_MATERNO
FROM CRM_PEDIDO P
INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
where flg_validado = 0");

$cantidadAcierto = 0;
$cantidadError = 0;
$query = '';
$cantidadQuery = 0;

$except = [
    '00320768', // Validar cambio de nombres y CE en prod
    '05288399', // Exluido por el nombre de Hernan -> Herman
    '06383156', // Observador, razon social es de empresa y es un CE
    '06589920', // Se considera correcto
    '07186905', // Se considera correcto
    '07614982', // Se considera correcto
    '001804896', // Se considera correcto
];

$_GLOBAL['errores'] = [];

foreach($resultados as $index => $fila)
{
    if(in_array($fila['CLIV_NUMERO_DOCUMENTO'], $except)) continue;
    if($index >= 30) break;

    $dataUltra = $sqlServer->select("SELECT d.id_data, d.flg_valid_nombres, CC.CLIV_RAZON_SOCIAL
    FROM " . TABLE_DATA_ULTRA_PROCESADO . " d
    inner join PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
    INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
    INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE CC ON EP.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
    WHERE d.cod_pedido_pf_ultra = ?", [$fila['PEDI_COD_PEDIDO']]);
    

    if(count($dataUltra) != 1)
    {
        $cantidadError++;
        continue;
    }

    $razonSocial = $dataUltra[0]['CLIV_RAZON_SOCIAL'];
    // $razonSocialSinTildes = quitar_tildes($razonSocial);
    $razonSocialSinComa = str_replace(',', '', $razonSocial);
    // $razonSocialSinComaSinTildes = quitar_tildes($razonSocialSinComa);

    // print_r_f([$fila, $dataUltra]);

    $intento = $fila['CLIV_APELLIDO_PATERNO'] . ' ' . 
    ($fila['CLIV_APELLIDO_MATERNO'] != '.' ? $fila['CLIV_APELLIDO_MATERNO'] . ' ' : '') . 
    $fila['CLIV_NOMBRES'];

    $intentoAlReves = $fila['CLIV_NOMBRES'] . ' ' . $fila['CLIV_APELLIDO_PATERNO'] . ' ' . 
    ($fila['CLIV_APELLIDO_MATERNO'] != '.' ? $fila['CLIV_APELLIDO_MATERNO'] : '');


    if($razonSocial == $intento || $razonSocial == $intentoAlReves || $razonSocialSinComa == $intento || $razonSocialSinComa == $intentoAlReves)
    {
        $cantidadAcierto++;
        $mysql->update("UPDATE CRM_CLIENTE SET flg_validado = 1 WHERE CLIV_NUMERO_DOCUMENTO = ?", [$fila['CLIV_NUMERO_DOCUMENTO']]);
        continue;
    }

    // print_r_f([$fila, $razonSocialEquifax, 'empresas' => $razonSocial, $intento]);

    $newName = get_razon_data_ultra($fila['CLIV_NUMERO_DOCUMENTO'], $fila, $razonSocial);
    $query .= get_new_name($fila, $newName);
    $cantidadQuery++;

    $cantidadError++;
    continue;
}

function quitar_tildes($cadena)
{
    return str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'], $cadena);
}

function get_razon_data_ultra($nro_documento, $fila, $razonSocial)
{
    if($nro_documento == '001262357')
    {
        return [
            'PrimerNombre' => 'JHONNY JAIR',
            'ApellidoPaterno' => 'GOMEZ',
            'ApellidoMaterno' => 'MORALES',
        ];
    }
    else if($nro_documento == '001705631')
    {
        return [
            'PrimerNombre' => 'GERMAN HORACIO',
            'ApellidoPaterno' => 'ALVAREZ',
            'ApellidoMaterno' => '.',
        ];
    }
    else if($nro_documento == '005259140')
    {
        return [
            'PrimerNombre' => 'NICOLAS ENRIQUE',
            'ApellidoPaterno' => 'MARTIN',
            'ApellidoMaterno' => '.',
        ];
    }
    else if($nro_documento == '000797866')
    {
        return [
            'PrimerNombre' => 'CRISTIAN',
            'ApellidoPaterno' => 'RESTREPO',
            'ApellidoMaterno' => 'HERNÁNDEZ',
        ];
    }
    else if($nro_documento == '001643664')
    {
        return [
            'PrimerNombre' => 'NORBERT',
            'ApellidoPaterno' => 'ONKELBACH',
            'ApellidoMaterno' => '.',
        ];
    }
    else if($nro_documento == '06348917')
    {
        return [
            'PrimerNombre' => 'MARIA ANGELICA',
            'ApellidoPaterno' => 'GOÑI',
            'ApellidoMaterno' => 'MORGAN DE MILLA',
        ];
    }
    else if($nro_documento == '06514079')
    {
        return [
            'PrimerNombre' => 'CESAR VICTOR',
            'ApellidoPaterno' => 'LEÓN',
            'ApellidoMaterno' => 'BACIGALUPO',
        ];
    }
    else if($nro_documento == '06589920')
    {
        return [
            'PrimerNombre' => 'CESAR VICTOR',
            'ApellidoPaterno' => 'LEÓN',
            'ApellidoMaterno' => 'BACIGALUPO',
        ];
    }
    else if($nro_documento == '07003518')
    {
        return [
            'PrimerNombre' => 'ANGEL ALEJANDRO',
            'ApellidoPaterno' => 'AGÜERO',
            'ApellidoMaterno' => 'CORREA',
        ];
    }
    else if($nro_documento == '06673293')
    {
        return [
            'PrimerNombre' => 'FRANCESCA MARIA',
            'ApellidoPaterno' => 'SILVA RODRIGUEZ',
            'ApellidoMaterno' => 'BONAZZI',
        ];
    }
    else if($nro_documento == '07799019')
    {
        return [
            'PrimerNombre' => 'JORGE EDGAR JOSE',
            'ApellidoPaterno' => 'MUÑIZ',
            'ApellidoMaterno' => 'ZICHES',
        ];
    }
    else if($nro_documento == '004689070')
    {
        return [
            'PrimerNombre' => 'MATIAS EXEQUIEL',
            'ApellidoPaterno' => 'BEGUERI',
            'ApellidoMaterno' => '.',
        ];
    }
    else if($nro_documento == '07832092')
    {
        return [
            'PrimerNombre' => 'JOSE CARLOS',
            'ApellidoPaterno' => 'DEXTRE',
            'ApellidoMaterno' => 'CHACÓN',
        ];
    }

    $equifax = get_data_equifax($fila['CLIV_NUMERO_DOCUMENTO']);

    if(isset($equifax['RazonSocial']))
    {
        $razonSocialEquifax = $equifax['RazonSocial'];
    }
    else
    {
        $razonSocialEquifax = '';
    }
    

    print_r_f([$nro_documento, $razonSocialEquifax, $fila, $razonSocial]);
    return null;
}

function get_new_name($fila, $newName)
{
    $diferenciaNombre = false;
    $diferenciaApellidoPaterno = false;
    $diferenciaApellidoMaterno = false;

    if($fila['CLIV_NOMBRES'] != $newName['PrimerNombre'])
    {
        $diferenciaNombre = true;
    }

    if($fila['CLIV_APELLIDO_PATERNO'] != $newName['ApellidoPaterno'])
    {
        $diferenciaApellidoPaterno = true;
    }

    if($fila['CLIV_APELLIDO_MATERNO'] != $newName['ApellidoMaterno'])
    {
        $diferenciaApellidoMaterno = true;
    }

    if($diferenciaNombre and !$diferenciaApellidoPaterno and !$diferenciaApellidoMaterno)
    {
        return "UPDATE CRM_CLIENTE SET CLIV_NOMBRES = '{$newName['PrimerNombre']}' WHERE CLIV_NUMERO_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}'; 
        UPDATE CRM_PEDIDO P SET P.PEDC_NOMBRES_APELLIDOS = '{$newName['PrimerNombre']}' WHERE P.PEDV_NUM_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        <br><br>";
    }

    if($diferenciaApellidoMaterno and !$diferenciaNombre and !$diferenciaApellidoPaterno)
    {   
        return "UPDATE CRM_CLIENTE SET CLIV_APELLIDO_MATERNO = '{$newName['ApellidoMaterno']}' WHERE CLIV_NUMERO_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        UPDATE CRM_PEDIDO P SET P.PEDV_APE_MATERNO = '{$newName['ApellidoMaterno']}' WHERE P.PEDV_NUM_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        <br><br>";
    }

    if($diferenciaApellidoPaterno and !$diferenciaNombre and !$diferenciaApellidoMaterno)
    {
        return "UPDATE CRM_CLIENTE SET CLIV_APELLIDO_PATERNO = '{$newName['ApellidoPaterno']}' WHERE CLIV_NUMERO_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        UPDATE CRM_PEDIDO P SET P.PEDV_APE_PATERNO = '{$newName['ApellidoPaterno']}' WHERE P.PEDV_NUM_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        <br><br>";
    }

    if($diferenciaNombre and !$diferenciaApellidoPaterno and $diferenciaApellidoMaterno)
    {
        return "UPDATE CRM_CLIENTE SET CLIV_APELLIDO_MATERNO = '{$newName['ApellidoMaterno']}', CLIV_NOMBRES = '{$newName['PrimerNombre']}' WHERE CLIV_NUMERO_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        UPDATE CRM_PEDIDO P SET P.PEDC_NOMBRES_APELLIDOS = '{$newName['PrimerNombre']}', P.PEDV_APE_MATERNO = '{$newName['ApellidoMaterno']}' WHERE P.PEDV_NUM_DOCUMENTO = '{$fila['CLIV_NUMERO_DOCUMENTO']}';
        <br><br>";
    }

    print_r_f(['nombre' => $fila,$newName]);
}

if($query != '')
{
    print_r_f($query);
}

echo 'Cantidad de aciertos: ' . $cantidadAcierto . "<br>";
echo 'Cantidad de errores: ' . $cantidadError . "<br>";
echo 'Cantidad de queries: ' . $cantidadQuery . "<br>";

print_r_f(':)');
