<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/get_razon_social.php';
require_once __DIR__ . '/procesar_gpon.php';

// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$postgres = new PostgreSQLConnection('10.1.4.25', '5432', 'opticalip_de_PORTAL', 'postgres', '');
$postgres->connect();

//$mysql = new MySQLConnection('10.1.4.17:33064', 'wincoreh_db_PROD_20241206_1230', 'desarrollo', 'D3v3$2023');
//$mysql->connect();

$resultados = $sqlServer->select("SELECT TOP 10 * FROM data_ultra_raw WHERE flg_migrado = 0");

foreach ($resultados as $fila) {

    if (is_numeric($fila['CircuitoCod']) and is_numeric($fila['ClienteID']) ) 
    {
        migrar_mpls($fila, $postgres, $sqlServer);
    } 
    else if (is_numeric($fila['IdWinforce']) and is_numeric($fila['IdPedido']) ) 
    {
        migrar_g_pon($fila, $sqlServer);
    }
}

print_r_f('OK :)');

function migrar_mpls(array $data, $postgres, $sqlServer)
{

//    $resultados = $postgres->select("SELECT  *
//    FROM opti_ubigeo LIMIT 10", []);
//    print_r_f($resultados);
    
    $resultados = $postgres->select("SELECT -- cir.*,
    c.cli_codigo, c.cli_razon_social, c.cli_nro_ruc,
    cir.cir_codigo, cir.cir_descripcion, cir.cir_fecha_vencimiento,
    cir.cir_ancho_banda, cir.cir_fecha_baja_operativa,
    cir.cir_status, tg2.tab_descripcion_breve status_descripcion,
    cir.cir_moneda, c.cli_codigo_ecom, cir.cir_codigo_ecom,
    dir.id_sede, dir.des_direccion, dir.sed_map_x, dir.sed_map_y, dir.dir_ubigeo, dir.dir_nuevocrm_id,
    ub1.descripcion distrito, ub2.descripcion provincia, ub3.descripcion region,
    tg.tab_codigo serv_codigo, tg.tab_descripcion serv_descripcion
    FROM opti_clientes c 
    inner join noc_circuitos cir on c.cli_codigo = cir.cli_codigo
    inner join coti_mae_sede dir on dir.id_sede = cir.cir_direccion_id
    INNER JOIN opti_ubigeo ub1 on dir.dir_ubigeo = ub1.ubigeo_id and ub1.nivel = 4
    INNER JOIN opti_ubigeo ub2 on ub1.ubigeo_padre = ub2.ubigeo_id
    INNER JOIN opti_ubigeo ub3 on ub2.ubigeo_padre = ub3.ubigeo_id
    inner join opti_tabla_general tg on cir.cir_tipo_servicio = tg.tab_codigo and tg.tab_tabla = 'SERV'
    inner join opti_tabla_general tg2 on cir.cir_status = tg2.tab_codigo and tg2.tab_tabla = 'CLIE'
    where c.cli_codigo = ? and cir.cir_codigo = ?
    ", [$data['ClienteID'], $data['CircuitoCod']]);

    
    if (count($resultados) != 1)
    {
        print_r_f($data);
        $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
        ['No se encontró el cliente en la base de datos (' . count($resultados) . ')', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);

        // print_r_f($resultados);
        return;
    }
    $resultados = $resultados[0];

    // Validación de Data
    $resultados['des_direccion'] = eliminarEspaciosMultiples($resultados['des_direccion']);
    $resultados['des_direccion'] = limpiarCaracteresEspeciales2($resultados['des_direccion']);

    $data['RazonSocial'] = normalizarTextoCharcater($data['RazonSocial']);
    $data['Circuito'] = normalizarTextoCharcater($data['Circuito']);
    $data['Direccion'] = normalizarTextoCharcater($data['Direccion']);

    $resultados['status_descripcion'] = convertStatusToDescription($resultados['status_descripcion']);

    $resultados['des_direccion'] = str_replace('JR. LA CORUÑA NRO', 'JR. LA CORUÑA NRO', $resultados['des_direccion']);

    // $data['AnchoBanda'] = $data['AnchoBanda'] == '1 Mbps' ? '1 Gbps' : $data['AnchoBanda'];
    $data['Distrito'] = strtoupper($data['Distrito']) == 'CERCADO DE LIMA' ? 'LIMA' : $data['Distrito'];
    /* $resultados['provincia'] = ($resultados['provincia'] == 'HUAROCHIRI' and $resultados['distrito'] == 'SURCO'
        and $resultados['region'] == 'LIMA') ? 'LIMA' : $resultados['provincia'];
    
    $resultados['cir_ancho_banda'] = $resultados['cir_ancho_banda'] == '1000000.00' ? '1000' : $resultados['cir_ancho_banda'];
    $resultados['cir_ancho_banda'] = $resultados['cir_ancho_banda'] == '800000.00' ? '800' : $resultados['cir_ancho_banda'];
    $resultados['cir_ancho_banda'] = $resultados['cir_ancho_banda'] == '600000.00' ? '600' : $resultados['cir_ancho_banda'];
    
    // Exceptions
    if($data['Item'] == 23921 and $data['ClienteID'] == 7094 and $data['CircuitoCod'] == 37906  and  $data['VencimientoActual'] == '21/05/2023') {
        $data['VencimientoActual'] = '21/05/2026';
    }
    
    if($data['Item'] == 24098 and $data['ClienteID'] == 7168 and $data['CircuitoCod'] == 38748  and  $data['VencimientoActual'] == '23/06/2023') {
        $data['VencimientoActual'] = '23/06/2026';
    }
    
    if($data['Item'] == 25589 and $data['ClienteID'] == 7688 and $data['CircuitoCod'] == 45536  and  $data['VencimientoActual'] == '26/10/2023') {
        $data['VencimientoActual'] = '26/10/2026';
    }

    $data['Distrito'] = $resultados['distrito']; */

    /* if($data['Item'] == 24624 and $data['ClienteID'] == 11333 and $data['CircuitoCod'] == 41193  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'BRENA';
    }

    if($data['Item'] == 24815 and $data['ClienteID'] == 9013 and $data['CircuitoCod'] == 42207  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'SAN ISIDRO';
    }

    if($data['Item'] == 25768 and $data['ClienteID'] == 7742 and $data['CircuitoCod'] == 46383  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'SAN ISIDRO';
    }

    if($data['Item'] == 25770 and $data['ClienteID'] == 10935 and $data['CircuitoCod'] == 46395  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'SAN ISIDRO';
    }

    if($data['Item'] == 25819 and $data['ClienteID'] == 7755 and $data['CircuitoCod'] == 46644  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'SANTIAGO DE SURCO';
    }

    if($data['Item'] == 25714 and $data['ClienteID'] == 7729 and $data['CircuitoCod'] == 46111  and  $data['Distrito'] == 'LIMA') {
        $data['Distrito'] = 'LA MOLINA';
    }
    */

    // VALIDAR CANTIDAD DE ACTIVOS QUE DEBERÍAN ESTAR EN SUSPENDIDOS
    /*if($data['Estado'] == 'Activo' and $resultados['status_descripcion'] == 'Suspendido por Falta Pago') {
        $data['Estado'] = 'Suspendido por Falta Pago';
    }

    if($data['Estado'] == 'Suspendido por Falta Pago' and $resultados['status_descripcion'] == 'Activo') {
        $data['Estado'] = 'Activo';
    }
    
    if($data['Item'] == 24786 and $data['ClienteID'] == 7408 and $data['CircuitoCod'] == 42075  and  $data['Estado'] == 'Activo') {
        $data['Estado'] = 'Baja';
        $data['BajaOperativa'] = '01/12/2024';
    }
    
    if($data['Item'] == 25120 and $data['ClienteID'] == 7510 and $data['CircuitoCod'] == 43420  and  $data['Estado'] == 'Activo') {
        $data['Estado'] = 'Baja';
        $data['BajaOperativa'] = '26/11/2024';
        $resultados['cir_ancho_banda'] = '600';
    }

    if($resultados['cli_codigo'] == 7261 and $resultados['cir_codigo'] == 40159 and $resultados['status_descripcion'] == 'Activo') {
        $resultados['cir_ancho_banda'] = '800';
    }

    if($resultados['cli_codigo'] == 7489 and $resultados['cir_codigo'] == 42986 and $resultados['status_descripcion'] == 'Activo') {
        $resultados['cir_ancho_banda'] = '600';
    } */

    $resultados['serv_descripcion'] = trim($resultados['serv_descripcion']);
    $data['TipoServicio'] = trim($data['TipoServicio']);
    $data['AnchoBanda'] = trim($data['AnchoBanda']);

    if($resultados['serv_descripcion'] == 'ULTRA 600' and ($resultados['cir_ancho_banda'] == '0.00' or is_null($resultados['cir_ancho_banda'])) and 
        $data['TipoServicio'] == $resultados['serv_descripcion'] and $data['AnchoBanda'] == '600 Mbps')
    {
        $resultados['cir_ancho_banda'] = '600';
    }

    if($resultados['serv_descripcion'] == 'Ultra 800' and ($resultados['cir_ancho_banda'] == '0.00' or is_null($resultados['cir_ancho_banda'])) and 
        $data['TipoServicio'] == $resultados['serv_descripcion'] and $data['AnchoBanda'] == '800 Mbps')
    {
        $resultados['cir_ancho_banda'] = '800';
    }

    if($resultados['cir_ancho_banda'] == '1000001.00')
    {
        $resultados['cir_ancho_banda'] = '1000000.00';
    }

    if($resultados['serv_descripcion'] == 'Ultra 1000' and $resultados['cir_ancho_banda'] == '1000.00' and 
    $data['TipoServicio'] == $resultados['serv_descripcion'] and $data['AnchoBanda'] == '1 Mbps')
    {
        $data['AnchoBanda'] = '1 Gbps';
    }

    if($data['AnchoBanda'] == '1000 Mbps') {
        $data['AnchoBanda'] = '1 Gbps';
    }
   
    // print_r_f([$data, $resultados]);
   
    // Validaciones de datos
    $validaciones = [
        'Cliente ID' => $data['ClienteID'] == $resultados['cli_codigo'],
        'Razón Social' => sonNombresSimiliares($data['RazonSocial'], $resultados['cli_razon_social'])['son_similares'],
        'RUC' => str_pad($data['RUC'], 8, '0', STR_PAD_LEFT) == $resultados['cli_nro_ruc'],
        'Código Circuito' => $data['CircuitoCod'] == $resultados['cir_codigo'],
        'Descripción Circuito' => sonNombresSimiliares($data['Circuito'], $resultados['cir_descripcion'])['son_similares'],
        'Dirección' => sonNombresSimiliares($data['Direccion'], $resultados['des_direccion'], 90)['son_similares'],
        'Distrito' => strtoupper(trim($data['Distrito'])) == trim($resultados['distrito']),
        'Provincia' => strtoupper(trim($data['Provincia'])) == trim($resultados['provincia']),
        'Departamento/Región' => strtoupper(trim($data['Departamento'])) == trim($resultados['region']),
        'Tipo Servicio' => trim($data['TipoServicio']) == trim($resultados['serv_descripcion']),
        'Fecha Vencimiento Actual' => convertDateToISOFormat($data['VencimientoActual']) == $resultados['cir_fecha_vencimiento'],
        'Ancho Banda' => $data['AnchoBanda'] == convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']),
        'Fecha Baja Operativa' => convertDateToISOFormat($data['BajaOperativa']) == $resultados['cir_fecha_baja_operativa'],
        'Status Descripción' => $data['Estado'] == $resultados['status_descripcion'],
        'Moneda' => $data['Moneda'] == convertCodModenaToDescription($resultados['cir_moneda']),
        'OfertaXVelocidad' => validateOfertaXVelocidad($resultados['serv_descripcion'], convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda'])),
        // 'Latitud' => $data['Latitud'] == $resultados['sed_map_x'],
        // 'Longitud' => $data['Longitud'] == $resultados['sed_map_y']
    ];

    // print_r_f($validaciones);
    // print_r_f([$data['Direccion'], $resultados['des_direccion']]);

    $coincide = true;

    $cant_val = 0;

    if(!$validaciones['Distrito']) {
        $cant_val++;
    }

    if(!$validaciones['Provincia']) {
        $cant_val++;
    }

    if(!$validaciones['Departamento/Región']) {
        $cant_val++;
    }
    
    if($cant_val === 1 or $cant_val === 2) {
        $validaciones['Distrito'] = true;
        $validaciones['Provincia'] = true;
        $validaciones['Departamento/Región'] = true;

        $resultados['distrito'] = strtoupper(trim($data['Distrito']));
        $resultados['provincia'] = strtoupper(trim($data['Provincia']));
        $resultados['region'] = strtoupper(trim($data['Departamento']));
    }

    // print_r_f([$data, $resultados, $cant_val]);

    foreach ($validaciones as $campo => $resultado) {
        if (!$resultado) {
            $coincide = false;
            break;
        }
    }

    // print_r_f([$data, $resultados]);

    if (!$coincide) {
        // Mostrar resultados de validación
        $htmlToPrint = "<h3>Resultados de validación:</h3>";
        $htmlToPrint .= "<h4>Datos de Ultra:</h4>";
        $htmlToPrint .= "Item: " . $data['Item'] . "<br>";
        $htmlToPrint .= "ClienteID: " . $data['ClienteID'] . "<br>";
        $htmlToPrint .= "CircuitoCod: " . $data['CircuitoCod'] . "<br>";
        $htmlToPrint .= "Razón Social: " . $data['RazonSocial'] . "<br><br>";

        // print_r_f([$data, $resultados]);

        foreach ($validaciones as $campo => $resultado) {
            $htmlToPrint .= str_pad($campo . ': ', 25) . ($resultado ? '✓ OK' : '✗ NO COINCIDE') . "\n <br>";

            if(!$resultado) 
            {
                if($campo != 'Ancho Banda' and $campo != 'OfertaXVelocidad' and $campo != 'Status Descripción' and $campo != 'Dirección') {
                    print_r_f([$campo, $data, $resultados]);
                }

                if ($campo == 'Ancho Banda' || $campo == 'OfertaXVelocidad')
                {
                    $campo = 'Plan Excel: ' . $data['TipoServicio'];
                    $campo .= ' - Plan WE: ' . $resultados['serv_descripcion'];
                    $campo .= ' - Ancho Banda Excel: ' . $data['AnchoBanda'];
                    $campo .= ' - Ancho Banda WE: ' . $resultados['cir_ancho_banda'];
                    $campo .= ' - Ancho Banda WE Convertido: ' . convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']);
                }
                else if($campo == 'Status Descripción') {
                    $campo = 'Estado Excel: ' . $data['Estado'];
                    $campo .= ' - Estado WE: ' . $resultados['status_descripcion'];
                }
                else if($campo == 'Dirección') {

                    if(strpos($resultados['des_direccion'], 'CALLE EL MONT') === 0) {
                        $resultados['des_direccion'] = 'CALLE EL MONT?CULO NRO 146 INT. 288';
                    }

                    $campo = 'Dirección Excel: ' . $data['Direccion'];
                    $campo .= ' - Dirección WE: ' . $resultados['des_direccion'];
                }

                $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
                [$campo, $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
                return;
            }
        }


        // echo $htmlToPrint;

        // print_r_f([sonNombresSimiliares($data['Direccion'], $resultados['des_direccion']), $data['Direccion'], $resultados['des_direccion']]);
        // print_r_f([sonNombresSimiliares($data['Circuito'], $resultados['cir_descripcion']), $data['Circuito'], $resultados['cir_descripcion']]);
        print_r_f([convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']), $data['AnchoBanda'], $resultados['cir_ancho_banda']]);

        // print_r_f([$data, $resultados]);
        return;

        $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
        [$htmlToPrint, $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
    }
    else
    {
        $directorio = get_data_equifax($resultados['cli_nro_ruc']);
        
        if(!isset($directorio['RazonSocial']) and !isset($directorio['PrimerNombre']))
        {
            $directorio = get_directorio_by_ruc($resultados['cli_nro_ruc'], $resultados['cli_razon_social']);

            // print_r_f([$data, $resultados, $directorio]);

            if(!isset($directorio) or !is_array($directorio)) {
                $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
                ['No se encontró la Razón Social en Equifax', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
                return;
            }
        }

        if(!isset($directorio['PrimerNombre'])) {
            $directorio['PrimerNombre'] = '';
            $directorio['ApellidoPaterno'] = '';
            $directorio['ApellidoMaterno'] = '';
        } else {
            $data_aux = [
                'desc_nombres' => '',
                'desc_apellido_paterno' => '',
                'desc_apellido_materno' => ''
            ];

            $aux = explode(' ', $directorio['RazonSocial']);

            if(isset($aux[0])) {
                $data_aux['desc_apellido_paterno'] = $aux[0];
            }

            if(isset($aux[1])) {
                $data_aux['desc_apellido_materno'] = $aux[1];
            }

            if(isset($aux[2])) {
                $data_aux['desc_nombres'] = $aux[2];
            }

            if(isset($aux[3])) {
                $data_aux['desc_nombres'] .= ' ' . $aux[3];
            }

            if(isset($aux[4])) {
                $data_aux['desc_nombres'] .= ' ' . $aux[4];
            }

            if(isset($aux[5])) {
                $data_aux['desc_nombres'] .= ' ' . $aux[5];
            }

            if(isset($aux[6])) {
                $data_aux['desc_nombres'] .= ' ' . $aux[6];
            }

            $isValidoAuxData = false;

            if(strpos($directorio['RazonSocial'], $data_aux['desc_apellido_paterno'] . ' ' . $data_aux['desc_apellido_materno']) === 0) {
                $isValidoAuxData = true;
            }

            if(is_array($directorio['PrimerNombre']) and count($directorio['PrimerNombre']) == 0 and $isValidoAuxData) {
                $directorio['PrimerNombre'] = $data_aux['desc_nombres'];
            }

            if(is_array($directorio['ApellidoPaterno']) and count($directorio['ApellidoPaterno']) == 0 and $isValidoAuxData) {
                $directorio['ApellidoPaterno'] = $data_aux['desc_apellido_paterno'];
            }

            if(is_array($directorio['ApellidoMaterno']) and count($directorio['ApellidoMaterno']) == 0 and $isValidoAuxData) {
                $directorio['ApellidoMaterno'] = $data_aux['desc_apellido_materno'];
            }


            $directorio['RazonSocial'] = '';

            if($directorio['ApellidoMaterno'] == '') {
                $directorio['ApellidoMaterno'] = '.';
            }

            /* if(is_array($directorio['ApellidoMaterno']) and count($directorio['ApellidoMaterno']) == 0) {
                $directorio['ApellidoMaterno'] = '.';
            } */
        }

        $dataRepresentante = [
            'desc_tipo_documento' => '',
            'desc_numero_documento' => '',
            'desc_nombres' => '',
            'desc_apellido_paterno' => '',
            'desc_apellido_materno' => ''
        ];

        if(strlen($resultados['cli_nro_ruc']) == 11 and substr($resultados['cli_nro_ruc'], 0, 2) == '20') {
            $dataRepresentante['desc_tipo_documento'] = 'DNI';
            $dataRepresentante['desc_numero_documento'] = '88888888';
            $dataRepresentante['desc_nombres'] = 'MIGRACION';
            $dataRepresentante['desc_apellido_paterno'] = 'WIN';
            $dataRepresentante['desc_apellido_materno'] = 'ULTRA';
        }

        $resultadosContacto = $sqlServer->select("SET NOCOUNT ON

        DECLARE @NRO_RUC VARCHAR(50) = ?
        DECLARE @CORREO VARCHAR(30), @CELULAR1 VARCHAR(30), @CELULAR2 VARCHAR(30), @ID_CLIENTE_ECOM INT

        SET @CORREO = (SELECT top 1 CTOV_EMAIL FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_EMAIL IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @CELULAR1 = (SELECT top 1 CTOV_TELEFONO_CELU FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_CELU IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @CELULAR2 = (SELECT top 1 CTOV_TELEFONO_FIJO FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_FIJO IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC);

        IF @ID_CLIENTE_ECOM IS NULL
        BEGIN
            SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM ECOM.ECOM_CLIENTE where CLIV_NRO_RUC = @NRO_RUC);
        END
        
        SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom", [$resultados['cli_nro_ruc']]);
        
        if(count($resultadosContacto) != 1) {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['No se encontró el contacto en la base de datos', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        $resultadosContacto = $resultadosContacto[0];

        $resultadosContacto['desc_correo'] = $resultadosContacto['desc_correo'] ?? 'correo.migracion.win.ultra@ultra.com';
        $resultadosContacto['desc_celular'] = $resultadosContacto['desc_celular'] ?? '999999999';
        $resultadosContacto['desc_telefono'] = $resultadosContacto['desc_telefono'] ?? '';

        $resultados['dir_nuevocrm_id'] = $resultados['dir_nuevocrm_id'] ?? 0;

        $resultadosDireccion = $sqlServer->select("select DIRV_NOMBRE_NIV from PE_OPTICAL_CRM.CRM.CRM_DIRECCION WHERE DIRI_COD_DIRECCION = ? ", [$resultados['dir_nuevocrm_id']]);

        if(count($resultadosDireccion) != 1) {
            $resultadosDireccion =[
                'tipo_domicilio' => 'HOGAR',
                'nro_piso' => '',
                'nro_dpto' => '',
                'nombre_condominio' => '',
                'tipo_predio' => 'PROPIETARIO'
            ];
        } else {
            $resultadosDireccion = $resultadosDireccion[0];
            
            if(strlen($resultadosDireccion['DIRV_NOMBRE_NIV']) < 2 and (int) $resultadosDireccion['DIRV_NOMBRE_NIV'] < 3) 
            {
                $resultadosDireccion =[
                    'tipo_domicilio' => 'HOGAR',
                    'nro_piso' => '',
                    'nro_dpto' => '',
                    'nombre_condominio' => '',
                    'tipo_predio' => 'PROPIETARIO'
                ];
            }
            else {
                $resultadosDireccion =[
                    'tipo_domicilio' => 'MULTIFAMILIAR',
                    'nro_piso' => $resultadosDireccion['DIRV_NOMBRE_NIV'],
                    'nro_dpto' => $resultadosDireccion['DIRV_NOMBRE_NIV'],
                    'nombre_condominio' => '',
                    'tipo_predio' => 'INQUILINO'
                ];
            }
        }

        // print_r_f($resultadosContacto);

        $resultados['serv_descripcion'] = $resultados['serv_descripcion'] == 'ULTRA 600' ? 'Ultra 600' : $resultados['serv_descripcion'];

        if(strlen($resultados['cli_nro_ruc']) == 11) {
            $resultados['tipo_documento'] = 'RUC';
        } else if(strlen($resultados['cli_nro_ruc']) != 8) {
            $resultados['tipo_documento'] = 'CE';
        } else {
            $resultados['tipo_documento'] = 'DNI';
        }

        if($resultados['status_descripcion'] != 'Activo') {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['El estado del cliente es ' . $resultados['status_descripcion'], $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        if($resultados['status_descripcion'] != 'Activo') {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['El estado del cliente es ' . $resultados['status_descripcion'], $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        if(!is_null($resultados['cir_fecha_baja_operativa'])) {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['La fecha de baja operativa es ' . $resultados['cir_fecha_baja_operativa'], $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        // print_r_f([$resultadosContacto, $resultados]);

        if ($resultados['cli_codigo_ecom'] != $resultadosContacto['id_cliente_ecom'] OR is_null($resultadosContacto['id_cliente_ecom'])) {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['El id_cliente_ecom es ' . $resultadosContacto['id_cliente_ecom'] . ' y el cli_codigo_ecom es ' . $resultados['cli_codigo_ecom'], $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        $datosEcom = $sqlServer->select("SELECT CO.CONI_ID_CONTRATO, EC.EMCI_ID_EMP_CLI, S.SERI_ID_SERVICIO, EC.EMPI_ID_EMPRESA
        FROM  PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_CLIENTE CLI -- ON C.cli_codigo_ecom = CLI.CLII_ID_CLIENTE
        INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_EMPRESA_CLIENTE EC ON CLI.CLII_ID_CLIENTE = EC.CLII_ID_CLIENTE
        INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_CONTRATO CO ON EC.EMCI_ID_EMP_CLI = CO.EMCI_ID_EMP_CLI
        INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_SERVICIO S ON CO.CONI_ID_CONTRATO = S.CONI_ID_CONTRATO -- AND S.SERI_ID_SERVICIO = CIR.cir_codigo_ecom
        WHERE EC.EMPI_ID_EMPRESA IN (10, 20) AND CLI.CLIV_NRO_RUC = ? AND CLI.CLII_ID_CLIENTE = ? AND S.SERI_ID_SERVICIO = ?",
        [$resultados['cli_nro_ruc'], $resultadosContacto['id_cliente_ecom'], $resultados['cir_codigo_ecom']]);

        if(count($datosEcom) != 1) {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['No se encontró el contrato en ECOM (' . count($datosEcom) . ')', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        $datosEcom = $datosEcom[0];

        $periodoEmision = $sqlServer->select("SELECT TOP 1 C.COMV_PERIODO_COMPROBANTE
        FROM PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.COMPROBANTE C
        INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
        WHERE C.ESTI_ID_ESTADO = 9 AND C.TCOI_ID_TIPOCOMPRO = 6 AND C.COMC_COD_ENTIDAD = ? AND CD.SERI_ID_SERVICIO = ?
        ORDER BY 1 DESC", [$resultadosContacto['id_cliente_ecom'], $resultados['cir_codigo_ecom']]);

        if(count($periodoEmision) != 1) {
            // print_r_f([$periodoEmision, $resultadosContacto['id_cliente_ecom'], $resultados['cir_codigo_ecom']]);
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['No se encontró alguna emision en ECOM', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        $periodoEmision = $periodoEmision[0]['COMV_PERIODO_COMPROBANTE'];

        if($periodoEmision != '202411') {
            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            ['El último periodo de emision con estado COBRADO es ' . $periodoEmision, $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            return;
        }

        print_r_f($periodoEmision);

        // Add INSERT statement
        $insertQuery = "INSERT INTO data_ultra_procesado (
            nro_documento, razon_social, id_cliente_intranet, id_cliente_ultra, 
            id_cliente_ecom, cod_pedido_ultra, cod_circuito, desc_circuito,
            razon_social_intranet, fec_vence_contrato, fec_baja, ancho_banda,
            estado_pedido, desc_moneda, desc_direccion, desc_latitud, desc_longitud,
            desc_distrito, desc_provincia, desc_region, desc_oferta, nombres, ape_paterno, ape_materno,
            desc_correo, desc_celular, desc_celular2, tipo_documento,
            tipo_vivienda, nro_piso, nro_departamento, nombre_condominio, tipo_predio,
            ecom_id_contrato, ecom_id_servicio, periodo_ultima_emision,
            representante_tipo_doc, representante_nro_doc, representante_nombres, representante_ape_paterno, representante_ape_materno
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $resultados['cli_nro_ruc'],
            $directorio['RazonSocial'],
            $resultados['cli_codigo'],
            0,
            $resultadosContacto['id_cliente_ecom'], // id_cliente_ecom (default to 0 since not provided)
            0,
            $resultados['cir_codigo'],
            $resultados['cir_descripcion'],
            $resultados['cli_razon_social'],
            $resultados['cir_fecha_vencimiento'],
            $resultados['cir_fecha_baja_operativa'],
            convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']),
            $resultados['status_descripcion'],
            convertCodModenaToDescription($resultados['cir_moneda']),
            $resultados['des_direccion'],
            floatval($resultados['sed_map_x']),
            floatval($resultados['sed_map_y']),
            $resultados['distrito'],
            $resultados['provincia'],
            $resultados['region'],
            $resultados['serv_descripcion'] . ' MPLS',
            $directorio['PrimerNombre'],
            $directorio['ApellidoPaterno'],
            $directorio['ApellidoMaterno'], 
            $resultadosContacto['desc_correo'],
            $resultadosContacto['desc_celular'],
            $resultadosContacto['desc_telefono'],
            $resultados['tipo_documento'],
            $resultadosDireccion['tipo_domicilio'],
            $resultadosDireccion['nro_piso'],
            $resultadosDireccion['nro_dpto'],
            $resultadosDireccion['nombre_condominio'],
            $resultadosDireccion['tipo_predio'],
            $datosEcom['CONI_ID_CONTRATO'],
            $datosEcom['SERI_ID_SERVICIO'],
            $periodoEmision,
            $dataRepresentante['desc_tipo_documento'],
            $dataRepresentante['desc_numero_documento'],
            $dataRepresentante['desc_nombres'],
            $dataRepresentante['desc_apellido_paterno'],
            $dataRepresentante['desc_apellido_materno']
        ];

        // print_r_f($params);

        $result = $sqlServer->insert($insertQuery, $params);

        if($result == false)
        {
            echo "Error al insertar datos en la tabla data_ultra_procesado";
            print_r_f($result);
            return;
        }

        $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = 'OK' WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            [$data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
    }

    // $resultados['cir_ancho_banda2'] = convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']);
    // print_r_f($resultados);

    return ;
}

function convertDateToISOFormat($dateString): ?string
{
    $dateObject = DateTime::createFromFormat('d/m/Y', $dateString);
    
    if (!($dateObject instanceof DateTime)) {
        return null;
    }
    
    return $dateObject->format('Y-m-d');
}

function convertAnchoBandaToMbpsGbps($anchoBanda) {
    if ($anchoBanda == '1000' or $anchoBanda == '1000000.00' or $anchoBanda == '1000.00') {
        return '1 Gbps';
    } else if ($anchoBanda == '10000') {
        return '10 Gbps';
    } else if ($anchoBanda == '100000') {
        return '100 Gbps';
    } elseif ($anchoBanda == '800' or $anchoBanda == '800000.00') {
        return '800 Mbps';
    } else if ($anchoBanda == '600' or $anchoBanda == '600000.00') {
        return '600 Mbps';
    } else if ($anchoBanda == '10') {
        return '10 Mbps';
    } else if ($anchoBanda == '1') {
        return '1 Mbps';
    } else {
        return $anchoBanda;
    }
}

function convertStatusToDescription($status) {
    if ($status == 'Sin Conformidad') {
        $status = 'Activo';
    }

    return $status;
}

function convertCodModenaToDescription($codModena) {
    if ($codModena == '001') {
        return 'Soles';
    } else if ($codModena == '002') {
        return 'Dolares';
    } else {
        return $codModena;
    }
}

function normalizarTextoCharcater($texto): string
{
    $texto = str_replace('+æ', 'Ñ', $texto);
    $texto = str_replace('´+¢', 'Ñ', $texto);
    $texto = str_replace('+ì', 'Í', $texto);
    $texto = str_replace('+¡', 'í', $texto);
    $texto = str_replace('+¦', 'ó', $texto);
    $texto = str_replace('+í', 'á', $texto);
    $texto = str_replace('+ô', 'Ó', $texto);
    $texto = str_replace('+ü', 'Á', $texto);
    $texto = str_replace('+£', 'Ü', $texto);
    $texto = str_replace('+®', 'é', $texto);
    return str_replace('+ë', 'É', $texto);
}

function eliminarTildes($texto): string
{
    $texto = str_replace('Ú', 'U', $texto);
    $texto = str_replace('Ó', 'O', $texto);
    $texto = str_replace('Í', 'I', $texto);
    $texto = str_replace('É', 'E', $texto);
    $texto = str_replace('Á', 'A', $texto);
    return $texto;
}


function normalizarTexto($texto): string
{
    // Convertir a mayúsculas y eliminar espacios extras
    $texto = trim(strtoupper($texto));
    
    // Normalizar caracteres especiales y acentos
    $caracteres = array(
        'Á'=>'A', 'É'=>'E', 'Í'=>'I', 'Ó'=>'O', 'Ú'=>'U', 'Ü'=>'U', 'Ñ'=>'N',
        'á'=>'A', 'é'=>'E', 'í'=>'I', 'ó'=>'O', 'ú'=>'U', 'ü'=>'U', 'ñ'=>'N',
        'Ô'=>'O', 'Ó'=>'O', '+'=>' ', 'Ý'=>'Y', 'ý'=>'Y'
    );
    $texto = strtr($texto, $caracteres);
    
    // Eliminar caracteres especiales y múltiples espacios
    $texto = preg_replace('/[^A-Z0-9\s]/', ' ', $texto);
    $texto = preg_replace('/\s+/', ' ', $texto);
    
    // Ordenar palabras alfabéticamente para manejar diferentes órdenes
    $palabras = explode(' ', $texto);
    sort($palabras);
    return implode(' ', $palabras);
}

function sonNombresSimiliares($nombre1, $nombre2, $porcentajeSimilitud = 95) {
    // Normalizar ambos nombres
    $nombre1_norm = normalizarTexto($nombre1);
    $nombre2_norm = normalizarTexto($nombre2);
    
    // Calcular distancia de Levenshtein
    $distancia = levenshtein($nombre1_norm, $nombre2_norm);
    
    // Calcular longitud máxima
    $longMaxima = max(strlen($nombre1_norm), strlen($nombre2_norm));
    
    // Calcular porcentaje de similitud
    $similitud = (1 - ($distancia / $longMaxima)) * 100;
    
    return [
        'son_similares' => $similitud >= $porcentajeSimilitud,
        'porcentaje' => round($similitud, 2),
        'nombre1_normalizado' => $nombre1_norm,
        'nombre2_normalizado' => $nombre2_norm
    ];
}

function eliminarEspaciosMultiples($texto) {
    return preg_replace('/\s+/', ' ', trim($texto));
}

function validateOfertaXVelocidad($oferta, $velocidad) {

    if($oferta == 'Ultra 1000' and $velocidad == '1 Gbps') {
        return true;
    } else if(($oferta == 'ULTRA 600' or $oferta == 'Ultra 1000') and $velocidad == '600 Mbps') {
        return true;
    } else if($oferta == 'Ultra 600' and $velocidad == '600 Mbps') {
        return true;
    } else if($oferta == 'Ultra 1000' and $velocidad == '800 Mbps') {
        return true;
    } else if($oferta == 'Ultra 800' and ($velocidad == '800 Mbps' or $velocidad == '1 Gbps')) {
        return true;
    } else if($oferta == 'ULTRA 600' and $velocidad == '1 Gbps') {
        return true;
    }

    return false;
}

function limpiarCaracteresEspeciales2($texto) {
    // Permitir letras (incluyendo ñ/Ñ), números, espacios y signos básicos
    return preg_replace('/[^a-zA-ZñÑáéíóúÁÉÍÓÚ0-9\s,\.\'\"°\-_#\/\(\)]+/', '', $texto);
}

function limpiarCaracteresUnicode($texto) {
    // Reemplazar caracteres Unicode comunes
    $reemplazos = [
        // Comillas y apóstrofes
        "\u{2018}" => "'", // Comilla simple izquierda
        "\u{2019}" => "'", // Comilla simple derecha
        "\u{201C}" => '"', // Comilla doble izquierda
        "\u{201D}" => '"', // Comilla doble derecha
        
        // Guiones y espacios
        "\u{2013}" => "-", // Guión medio
        "\u{2014}" => "-", // Guión largo
        "\u{00A0}" => " ", // Espacio duro
        
        // Caracteres especiales comunes
        "\u{00B0}" => "°", // Símbolo de grado
        "\u{00BA}" => "°", // Indicador ordinal masculino
        "\u{00AA}" => "ª", // Indicador ordinal femenino
        "\u{2022}" => "*", // Viñeta
        "\u{2026}" => "...", // Puntos suspensivos
        
        // Símbolos matemáticos
        "\u{00D7}" => "x", // Símbolo de multiplicación
        "\u{00F7}" => "/", // Símbolo de división
        "\u{00B1}" => "+/-", // Más/menos
        
        // Otros caracteres especiales
        "\u{00A9}" => "(c)", // Copyright
        "\u{00AE}" => "(r)", // Marca registrada
        "\u{2122}" => "TM", // Trademark
    ];
    
    // Aplicar reemplazos
    $texto = str_replace(array_keys($reemplazos), array_values($reemplazos), $texto);
    
    // Eliminar cualquier otro carácter Unicode que no esté en la lista
    // $texto = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $texto);
    
    
    // Eliminar caracteres de control
    $texto = preg_replace('/[\x00-\x1F\x7F]/u', '', $texto);

    print_r_f($texto);
    
    return $texto;
}

function get_directorio_by_ruc($ruc, $razon_social) {

    if(substr($ruc, 0, 2) == '20' and strlen($ruc) == 11) {
        return [
            'RazonSocial' => $razon_social,
            'PrimerNombre' => '',
            'ApellidoPaterno' => '',
            'ApellidoMaterno' => ''
        ];
    }

    $directorio = [
        'RazonSocial' => '',
        'PrimerNombre' => '',
        'ApellidoPaterno' => '',
        'ApellidoMaterno' => ''
    ];

    if($ruc == '00000000') {
        $directorio['PrimerNombre'] = $razon_social;
        return $directorio;
    }

    // GOMEZ MORALES JHONNY JAIR
    if($razon_social == 'GOMEZ MORALES JHONNY JAIR') {
        $directorio['PrimerNombre'] = 'JHONNY JAIR';
        $directorio['ApellidoPaterno'] = 'GOMEZ';
        $directorio['ApellidoMaterno'] = 'MORALES';
        return $directorio;
    }

    // DONOSO ARIAS RODRIGO ANDRES
    if($razon_social == 'DONOSO ARIAS RODRIGO ANDRES') {
        $directorio['PrimerNombre'] = 'RODRIGO ANDRES';
        $directorio['ApellidoPaterno'] = 'DONOSO';
        $directorio['ApellidoMaterno'] = 'ARIAS';
        return $directorio;
    }

    // RODRIGUEZ BARRIGA JUAN MARCELO
    if($razon_social == 'RODRIGUEZ BARRIGA JUAN MARCELO') {
        $directorio['PrimerNombre'] = 'JUAN MARCELO';
        $directorio['ApellidoPaterno'] = 'RODRIGUEZ';
        $directorio['ApellidoMaterno'] = 'BARRIGA';
        return $directorio;
    }

    // FLOISTAD MIR ADRIANA
    if($razon_social == 'FLOISTAD MIR ADRIANA') {
        $directorio['PrimerNombre'] = 'ADRIANA';
        $directorio['ApellidoPaterno'] = 'FLOISTAD';
        $directorio['ApellidoMaterno'] = 'MIR';
        return $directorio;
    }

    // DAVID AGUSTIN SALAZAR PONCE
    if($razon_social == 'DAVID AGUSTIN SALAZAR PONCE') {
        $directorio['PrimerNombre'] = 'DAVID AGUSTIN';
        $directorio['ApellidoPaterno'] = 'SALAZAR';
        $directorio['ApellidoMaterno'] = 'PONCE';
        return $directorio;
    }

    // ENRIQUE SANCHEZ HILARA
    if($razon_social == 'ENRIQUE SANCHEZ HILARA') {
        $directorio['PrimerNombre'] = 'ENRIQUE';
        $directorio['ApellidoPaterno'] = 'SANCHEZ';
        $directorio['ApellidoMaterno'] = 'HILARA';
        return $directorio;
    }

    // KING ANDREW JOHN
    if($razon_social == 'KING ANDREW JOHN') {
        $directorio['PrimerNombre'] = 'JOHN';
        $directorio['ApellidoPaterno'] = 'KING';
        $directorio['ApellidoMaterno'] = 'ANDREW';
        return $directorio;
    }

    if($razon_social == 'BARAONA IPINZA FELIPE') {
        $directorio['PrimerNombre'] = 'FELIPE';
        $directorio['ApellidoPaterno'] = 'BARAONA';
        $directorio['ApellidoMaterno'] = 'IPINZA';
        return $directorio;
    }

    if($razon_social == 'EDUARDO FRANCA DE SOUZA') {
        $directorio['PrimerNombre'] = 'EDUARDO';
        $directorio['ApellidoPaterno'] = 'FRANCA';
        $directorio['ApellidoMaterno'] = 'DE SOUZA';
        return $directorio;
    }

    // PELAT RENAUD PATRICE NICOLAS
    if($razon_social == 'PELAT RENAUD PATRICE NICOLAS') {
        $directorio['PrimerNombre'] = 'PATRICE NICOLAS';
        $directorio['ApellidoPaterno'] = 'PELAT';
        $directorio['ApellidoMaterno'] = 'RENAUD';
        return $directorio;
    }

    return null;

    $tieneComa = false;
    $seEncontroNombre = false;

    if(strpos($razon_social, ',') !== false) {
        $tieneComa = true;
    }

    if($tieneComa) {
        $array_aux = explode(',', $razon_social);

        if(count($array_aux) != 2) {
            $tieneComa = false;
        }
    }

    if($tieneComa) {
        $array_aux_apellidos = explode(' ', trim($array_aux[0]));
        $array_aux_nombres = explode(' ', trim($array_aux[1]));

        if(count($array_aux_apellidos) == 2) {
            $directorio['ApellidoPaterno'] = $array_aux_apellidos[0];
            $directorio['ApellidoMaterno'] = $array_aux_apellidos[1];
            $directorio['PrimerNombre'] = $array_aux[1];
            return $directorio;
        } else if(count($array_aux_apellidos) == 1) {
            $directorio['ApellidoPaterno'] = $array_aux_apellidos[0];
            $directorio['ApellidoMaterno'] = '';
            $directorio['PrimerNombre'] = $array_aux[1];
            return $directorio;
        } else if(count($array_aux_nombres) == 2 and count($array_aux_apellidos) == 3) {
            $directorio['ApellidoPaterno'] = $array_aux_nombres[0];
            $directorio['ApellidoMaterno'] = $array_aux_nombres[1];
            $directorio['PrimerNombre'] = $array_aux[0];
            return $directorio;
        }
    }

    
    $array_aux_nombres = explode(' ', $razon_social);

    if(count($array_aux_nombres) == 2) {
        $directorio['PrimerNombre'] = $array_aux_nombres[0];
        $directorio['ApellidoPaterno'] = $array_aux_nombres[1];
        return $directorio;
    }

    if(count($array_aux_nombres) == 3) {
        $directorio['PrimerNombre'] = $array_aux_nombres[0];
        $directorio['ApellidoPaterno'] = $array_aux_nombres[1];
        $directorio['ApellidoMaterno'] = $array_aux_nombres[2];
        return $directorio;
    }

    if(count($array_aux_nombres) == 4) {
        $directorio['PrimerNombre'] = $array_aux_nombres[2] . ' ' . $array_aux_nombres[3];
        $directorio['ApellidoPaterno'] = $array_aux_nombres[0];
        $directorio['ApellidoMaterno'] = $array_aux_nombres[1];
        return $directorio;
    }

    if(count($array_aux_nombres) == 5) {
        $directorio['PrimerNombre'] = $array_aux_nombres[0] . ' ' . $array_aux_nombres[1] . ' ' . $array_aux_nombres[2];
        $directorio['ApellidoPaterno'] = $array_aux_nombres[3];
        $directorio['ApellidoMaterno'] = $array_aux_nombres[4];
        return $directorio;
    }

    return null;
}

