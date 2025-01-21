<?php
// data_ultra_gpon_raw=b2
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/helper.php';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$postgres = new PostgreSQLConnection('10.1.4.25', '5432', 'opticalip_de', 'postgres', '');
$postgres->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', 'db_wincrm_250115', 'root', 'R007w1N0r3');
$mysql->connect();

$resultados = $sqlServer->select("SELECT top 23 d.id_data, d.cod_circuito, d.nro_documento, d.flg_config_address, d.cod_pedido_ultra, d.desc_direccion,
r.Direccion, s.SERV_DIRECCION, r.Latitud, r.Longitud, desc_latitud, desc_longitud, desc_distrito, desc_provincia, desc_region, desc_ubigeo,
cod_pedido_pf_ultra
FROM data_ultra_procesado d
INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
INNER JOIN PE_OPTICAL_ADM.ECOM.ECOM_SERVICIO s ON s.SERI_ID_SERVICIO = d.ecom_id_servicio
WHERE flg_config_address = 0 and d.cod_pedido_pf_ultra <> 0
order by cod_pedido_pf_ultra desc");


$querySQL = '';

foreach ($resultados as $fila)
{
    if($fila['desc_direccion'] == '')
    {
        if($fila['cod_circuito'] <> 0 and $fila['cod_pedido_ultra'] == 0)
        {
            $fila['Direccion'] = normalizarTextoCharcater($fila['Direccion']);
            $fila['Direccion'] = mb_strtoupper($fila['Direccion']);
            $fila['Direccion'] = str_replace('  ', ' ', $fila['Direccion']);

            $fila['Direccion'] = str_replace('CALLE CALLE', 'CALLE', $fila['Direccion']);
            $fila['Direccion'] = str_replace('AVENIDA AVENIDA', 'AVENIDA', $fila['Direccion']);

            $fila['Direccion'] = quitar_tildes($fila['Direccion']);


            $fila['SERV_DIRECCION'] = str_replace('CALLE CALLE', 'CALLE', $fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = str_replace('AVENIDA AVENIDA', 'AVENIDA', $fila['SERV_DIRECCION']);

            $fila['SERV_DIRECCION'] = mb_strtoupper($fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = str_replace('  ', ' ', $fila['SERV_DIRECCION']);

            $fila['SERV_DIRECCION'] = quitar_tildes($fila['SERV_DIRECCION']);

            $address = $postgres->select("SELECT dir.des_direccion, dir.sed_map_x, dir.sed_map_y, dir.dir_ubigeo,
            ub1.descripcion distrito, ub2.descripcion provincia, ub3.descripcion region
            FROM noc_circuitos cir 
            inner join coti_mae_sede dir on dir.id_sede = cir.cir_direccion_id
            INNER JOIN opti_ubigeo ub1 on dir.dir_ubigeo = ub1.ubigeo_id and ub1.nivel = 4
            INNER JOIN opti_ubigeo ub2 on ub1.ubigeo_padre = ub2.ubigeo_id
            INNER JOIN opti_ubigeo ub3 on ub2.ubigeo_padre = ub3.ubigeo_id
            where cir.cir_codigo = ?
            ", [$fila['cod_circuito']]);

            if(count($address) != 1)
            {
                if($fila['cod_circuito'] != 180381 and $fila['cod_circuito'] != 180389)
                {
                    print_r_f(['no se encontro direccion', $fila]);
                }

                $address = [];

                if($fila['cod_circuito'] == 180381)
                {
                    $address[] = [
                        'des_direccion' => 'CALLE TRINIDAD NRO 580 PISO 102 DPTO 102 URB. CHACARILLA DEL ESTANQUE ZONA CALLE TRINIDAD 580, SANTIAGO DE SURCO, LIMA, LIMA',
                        'sed_map_x' => -12.1102239,
                        'sed_map_y' => -76.9920076,
                        'dir_ubigeo' => '00150140',
                        'distrito' => 'SANTIAGO DE SURCO',
                        'provincia' => 'LIMA',
                        'region' => 'LIMA'
                    ];
                }

                if($fila['cod_circuito'] == 180389)
                {
                    $address[] = [
                        'des_direccion' => 'JR. 405 S/N. 0 VIVIENDA URB. CANOPUS ANEXO CANOPUS, SANTIAGO DE SURCO, LIMA, LIMA',
                        'sed_map_x' => -12.1307154,
                        'sed_map_y' => -77.0056855,
                        'dir_ubigeo' => '00150140',
                        'distrito' => 'SANTIAGO DE SURCO',
                        'provincia' => 'LIMA',
                        'region' => 'LIMA'
                    ];
                }
            }

            $address = $address[0];

            $address['des_direccion'] = normalizarTextoCharcater($address['des_direccion']);
            $address['des_direccion'] = mb_strtoupper($address['des_direccion']);
            $address['des_direccion'] = str_replace('  ', ' ', $address['des_direccion']);

            $address['des_direccion'] = str_replace('CALLE CALLE', 'CALLE', $address['des_direccion']);
            $address['des_direccion'] = str_replace('AVENIDA AVENIDA', 'AVENIDA', $address['des_direccion']);

            $address['des_direccion'] = quitar_tildes($address['des_direccion']);
            $address['des_direccion_sin_ñ'] = str_replace('Ñ', 'N', $address['des_direccion']);

            $address['des_direccion'] = str_replace('CALLE EL MONT?CULO', 'CALLE EL MONTICULO', $address['des_direccion']);
            $address['des_direccion'] = str_replace('JR. LA CORU?A', 'JR. LA CORUÑA', $address['des_direccion']);

            if($address['des_direccion'] != $fila['Direccion'] and $address['des_direccion'] != $fila['SERV_DIRECCION']
            and $address['des_direccion_sin_ñ'] != $fila['Direccion'] and $address['des_direccion_sin_ñ'] != $fila['SERV_DIRECCION'])
            {
                print_r_f([$address, $fila]);
            }

            $auxUbigeo = ', ' . $address['distrito'] . ', ' . $address['provincia'] . ', ' . $address['region'];
            $auxUbigeo2 = ',' . $address['distrito'] . ', ' . $address['provincia'];

            $address['des_direccion_final'] = str_replace($auxUbigeo, '', $address['des_direccion']);
            $address['des_direccion_final'] = str_replace($auxUbigeo2, '', $address['des_direccion_final']);
            $address['des_direccion_final'] = str_replace(', SURCO, HUAROCHIRI, LIMA', '', $address['des_direccion_final']);
            // $address['des_direccion_final'] = str_replace(' ' . $address['distrito'] . ' ', '', $address['des_direccion_final']);

            if($address['des_direccion_final'] == 'AV. ALBERTO DEL CAMPO 488, DPTO 1603 - SAN ISIDRO 15076') {
                $address['des_direccion_final'] = 'AV. ALBERTO DEL CAMPO 488, DPTO 1603 - 15076';
            }

            $address['des_direccion_final'] = str_replace('  ', ' ', $address['des_direccion_final']);
            $address['des_direccion_final'] = str_replace('  ', ' ', $address['des_direccion_final']);

            $tieneUbigeo = strpos($address['des_direccion_final'], $address['distrito']) !== false;

            if(!$tieneUbigeo)
            {
                $tieneUbigeo = strpos($address['des_direccion_final'], $address['region']) !== false;
            }

            if($address['des_direccion_final'] == $address['des_direccion'] and $tieneUbigeo)
            {
                print_r_f([$address, $auxUbigeo, $auxUbigeo2, $fila]);
            }

            $ubigeoResult = $mysql->select("SELECT UPPER(U1.UBIV_DESCRIPCION) distrito, UPPER(U2.UBIV_DESCRIPCION) provincia, UPPER(U3.UBIV_DESCRIPCION) region
            FROM CRM_UBIGEO U1
            INNER JOIN CRM_UBIGEO U2 ON U2.UBIC_UBIGEO = CONCAT(SUBSTR(U1.UBIC_UBIGEO, 1, 6), '00')
            INNER JOIN CRM_UBIGEO U3 ON U3.UBIC_UBIGEO = CONCAT(SUBSTR(U2.UBIC_UBIGEO, 1, 4), '0000')
            WHERE U1.UBIC_UBIGEO = '".$address['dir_ubigeo']."'");

            if(count($ubigeoResult) != 1)
            {
                print_r_f([$address, $fila]);
            }

            $ubigeoResult = $ubigeoResult[0];
            $ubigeoResult['distrito'] = quitar_tildes($ubigeoResult['distrito']);
            $ubigeoResult['provincia'] = quitar_tildes($ubigeoResult['provincia']);
            $ubigeoResult['region'] = quitar_tildes($ubigeoResult['region']);

            $ubigeoResult['distrito_sin_ñ'] = str_replace('Ñ', 'N', $ubigeoResult['distrito']);

            if(($ubigeoResult['distrito'] != $address['distrito'] and $ubigeoResult['distrito_sin_ñ'] != $address['distrito']) or $ubigeoResult['provincia'] != $address['provincia'] or $ubigeoResult['region'] != $address['region'])
            {
                print_r_f([$address, $ubigeoResult, $fila]);
            }

            $address['des_direccion_final'] = trim($address['des_direccion_final']);
            $address['des_direccion_final'] .= ' [' . $ubigeoResult['distrito'] . ' - ' . $ubigeoResult['provincia'] . ' - ' . $ubigeoResult['region'] . ']';
            
            if($address['des_direccion_final'] == 'AV. CIRCUNVALACION NRO 1033 PISO 6 RESIDENCIA 1 MZ. 1 LT. 1 RES. AV. CIRCUNVALACION DEL GOLF LOS INCAS 1033 SURCO ZONA AV. CIRCUNVALACION DEL GOLF LOS INCAS 1033 SURCO [SANTIAGO DE SURCO - LIMA - LIMA]')
            {
                $address['des_direccion_final'] = 'AV. CIRCUNVALACION DEL GOLF LOS INCAS NRO 1033 PISO 6 RESIDENCIA 1 MZ. 1 LT. 1 [SANTIAGO DE SURCO - LIMA - LIMA]';
            }

            $resultUpdate = $sqlServer->update("UPDATE data_ultra_procesado SET desc_direccion = ?, desc_latitud = ?, desc_longitud = ?, 
            desc_distrito = ?, desc_provincia = ?, desc_region = ?, desc_ubigeo = ? WHERE id_data = ?", [$address['des_direccion_final'], $address['sed_map_x'], $address['sed_map_y'], $ubigeoResult['distrito'], $ubigeoResult['provincia'], $ubigeoResult['region'], $address['dir_ubigeo'], $fila['id_data']]);

            continue;
        }
        else if($fila['cod_circuito'] == 0 and $fila['cod_pedido_ultra'] <> 0)
        {
            $fila['Direccion'] = normalizarTextoCharcater($fila['Direccion']);
            $fila['Direccion'] = quitar_tildes($fila['Direccion']);

            $fila['SERV_DIRECCION'] = quitar_tildes($fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = str_replace('  ', ' ', $fila['SERV_DIRECCION']);

            $fila['Direccion'] = str_replace('CALLE LA REP+ÜBLICA', 'CALLE LA REPUBLICA', $fila['Direccion']);
            $fila['Direccion'] = str_replace('CALLE MONT+ìCULO', 'CALLE MONTÍCULO', $fila['Direccion']);
            $fila['Direccion'] = str_replace('Jirun ', 'Jiron ', $fila['Direccion']);
            $fila['Direccion'] = str_replace('Av. Roca y Boloua', 'Av. Roca y Boloña', $fila['Direccion']);
            $fila['SERV_DIRECCION'] = strtoupper($fila['SERV_DIRECCION']);
            if($fila['Direccion'] != $fila['SERV_DIRECCION'])
            {
                print_r_f(["Error 162s",$fila]);
            }

            $fila['SERV_DIRECCION'] = mb_strtoupper($fila['SERV_DIRECCION']);

            $getUbigeo = $sqlServer->select("select desc_latitud, desc_longitud, ubigeo, desc_distrito, desc_provincia, desc_region
            from data_ultra_gpon_raw
            where cod_pedido_ultra = ?", [$fila['cod_pedido_ultra']]);

            if(count($getUbigeo) != 1)
            {
                print_r_f(["Error 208s",$fila]);
            }

            $getUbigeo = $getUbigeo[0];

            $auxTieneUbigeo = strpos($fila['SERV_DIRECCION'], $getUbigeo['desc_distrito']) !== false;
            $auxTieneUbigeo2 = strpos($fila['SERV_DIRECCION'], $getUbigeo['desc_region']) !== false;

            if($auxTieneUbigeo or $auxTieneUbigeo2)
            {
                $fila['SERV_DIRECCION'] = str_replace($getUbigeo['desc_distrito'], '', $fila['SERV_DIRECCION']);
                $fila['SERV_DIRECCION'] = str_replace($getUbigeo['desc_region'], '', $fila['SERV_DIRECCION']);
                // print_r_f([$getUbigeo, $fila]);
            }

            $fila['SERV_DIRECCION'] = str_replace(', PERU', '', $fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = trim($fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = trim($fila['SERV_DIRECCION'], ',');
            $fila['SERV_DIRECCION'] = str_replace('  ', ' ', $fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = str_replace('  ', ' ', $fila['SERV_DIRECCION']);

            $fila['SERV_DIRECCION'] .= ' [' . $getUbigeo['desc_distrito'] . ' - ' . $getUbigeo['desc_provincia'] . ' - ' . $getUbigeo['desc_region'] . ']';

            $fila['SERV_DIRECCION'] = str_replace('CALLE CALLE', 'CALLE', $fila['SERV_DIRECCION']);
            $fila['SERV_DIRECCION'] = str_replace('AVENIDA AVENIDA', 'AVENIDA', $fila['SERV_DIRECCION']);


            $fila['SERV_DIRECCION'] = quitar_tildes($fila['SERV_DIRECCION']);
            
            $resultUpdate = $sqlServer->update("UPDATE data_ultra_procesado SET desc_direccion = ?, desc_latitud = ?, desc_longitud = ?, 
            desc_distrito = ?, desc_provincia = ?, desc_region = ?, desc_ubigeo = ? WHERE id_data = ?", [$fila['SERV_DIRECCION'], $getUbigeo['desc_latitud'], $getUbigeo['desc_longitud'], $getUbigeo['desc_distrito'], $getUbigeo['desc_provincia'], $getUbigeo['desc_region'], $getUbigeo['ubigeo'], $fila['id_data']]);

            continue;
        }
        else
        {
            print_r_f($fila);
        }
    }
    else
    {
        $codDireccion = $mysql->select("SELECT P.PEDI_COD_DIRECCION FROM CRM_PEDIDO P WHERE P.PEDI_COD_PEDIDO = ?;", [$fila['cod_pedido_pf_ultra']]);

        if(count($codDireccion) != 1)
        {
            print_r_f([$codDireccion, $fila]);
        }

        $codDireccion = $codDireccion[0]['PEDI_COD_DIRECCION'];

        $dirData = $mysql->select("SELECT D.DIRN_LATITUD, D.DIRN_LONGITUD, D.DIRC_UBIGEO, D.DIRV_NOMBRE, D.DIRV_DIRECCION, D.DIRV_DIRECCION_INSTALACION
        FROM CRM_DIRECCION D
        WHERE D.DIRI_COD_DIRECCION = ?;", [$codDireccion]);

        if(count($dirData) != 1)
        {
            print_r_f([$dirData, $fila]);
        }

        $dirData = $dirData[0];

        if($dirData['DIRV_DIRECCION'] != $fila['desc_direccion'] or number_format((float) $dirData['DIRN_LATITUD'], 4) != number_format((float) $fila['desc_latitud'], 4) or number_format((float) $dirData['DIRN_LONGITUD'], 4) != number_format((float) $fila['desc_longitud'], 4) or $dirData['DIRC_UBIGEO'] != $fila['desc_ubigeo'])
        {
            $querySQL .= "UPDATE CRM_DIRECCION SET DIRN_LATITUD = '" . $fila['desc_latitud'] . "', DIRN_LONGITUD = '" . $fila['desc_longitud'] . "', DIRC_UBIGEO = '" . $fila['desc_ubigeo'] . "', DIRV_NOMBRE = '" . $fila['desc_direccion'] . "', DIRV_DIRECCION = '" . $fila['desc_direccion'] . "', DIRV_DIRECCION_INSTALACION = '" . $fila['desc_direccion'] . "' WHERE DIRI_COD_DIRECCION = " . $codDireccion . "; \n";
        }
        else
        {
            $resultUpdate = $sqlServer->update("UPDATE data_ultra_procesado SET flg_config_address = 1 WHERE id_data = ?", [$fila['id_data']]);
        }

        continue;
    }
}

if($querySQL != '')
{
    print_r_f($querySQL);
}

print_r_f('ok :)');


