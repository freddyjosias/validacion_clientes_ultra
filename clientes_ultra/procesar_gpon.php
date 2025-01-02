<?php

function migrar_g_pon(array $data, $sqlServer)
{
    $resultados = $sqlServer->select("select * from data_raw_ultra_bk2 WHERE cod_pedido_ultra = ?", [$data['IdPedido']]);

    if(count($resultados) != 1) {
        print_r_f($resultados);
        return;
    }

    $resultados = $resultados[0];

    $data['TipoServicio'] = $data['TipoServicio'] == 'Ultra 600Mbps' ? 'Ultra 600' : $data['TipoServicio'];

    // print_r_f([$data, $resultados]);

    if($resultados['estado'] == 'Suspendido') {
        $resultados['fec_baja'] = null;
    }

    if($data['Estado'] == 'Suspendido por Falta Pago' and $resultados['estado'] == 'Suspendido') {
        $resultados['estado'] = 'Suspendido por Falta Pago';
    }

    if($data['Estado'] == 'Suspendido Solicitud Cliente' and $resultados['estado'] == 'Suspendido') {
        $resultados['estado'] = 'Suspendido Solicitud Cliente';
    }

    $data['Distrito'] = strtoupper($data['Distrito']) == 'CERCADO DE LIMA' ? 'LIMA' : $data['Distrito'];

    if($data['IdPedido'] == 5000084) {
        $data['RUC'] = '10702482572';
    }

    $validaciones = [
        'COD_PEDIDO_ULTRA' => $data['IdPedido'] == $resultados['cod_pedido_ultra'],
        'NRO_DOCUMENTO' => $data['RUC'] == $resultados['num_documento'],
        'ANCHO_BANDA' => $data['AnchoBanda'] == convertAnchoBandaToMbpsGbps($resultados['ancho_banda']),
        'TIPO_SERVICIO' => trim($data['TipoServicio']) == trim($resultados['desc_oferta']),
        'ESTADO_SERVICIO' => $data['Estado'] == $resultados['estado'],
        'MONEDA' => $data['Moneda'] == $resultados['desc_moneda'],
        'LATITUD' => round($data['Latitud'], 6) == round($resultados['desc_latitud'], 6) or (ceil($data['Latitud'] * 1000000) / 1000000) == round($resultados['desc_latitud'], 6),
        'LONGITUD' => round($data['Longitud'], 6) == round($resultados['desc_longitud'], 6) or (ceil($data['Longitud'] * 1000000) / 1000000) == round($resultados['desc_longitud'], 6),
        'DISTRITO' => strtoupper(trim($data['Distrito'])) == trim(eliminarTildes($resultados['desc_distrito'])),
        'PROVINCIA' => strtoupper(trim($data['Provincia'])) == trim($resultados['desc_provincia']),
        'DEPARTAMENTO' => strtoupper(trim($data['Departamento'])) == trim($resultados['desc_region']),
        'FECHA_BAJA_OPERATIVA' => convertDateToISOFormat($data['BajaOperativa']) == $resultados['fec_baja'],
        'OfertaXVelocidad' => validateOfertaXVelocidad($resultados['desc_oferta'], convertAnchoBandaToMbpsGbps($resultados['ancho_banda'])),
    ];

    // print_r_f([$validaciones]);

    /*
    print_r_f([
        'data_exel' => $data,
        'data_db' => $resultados
    ]);
    */

    $coincide = true;

    foreach ($validaciones as $campo => $resultado) {
        if (!$resultado) {
            $coincide = false;
            break;
        }
    }
    
    if (!$coincide) {
        // Mostrar resultados de validación
        $htmlToPrint = "<h3>Resultados de validación:</h3>";
        $htmlToPrint .= "<h4>Datos de Ultra:</h4>";
        $htmlToPrint .= "Item: " . $data['Item'] . "<br>";
        $htmlToPrint .= "ClienteID: " . $data['ClienteID'] . "<br>";
        $htmlToPrint .= "CircuitoCod: " . $data['CircuitoCod'] . "<br>";
        $htmlToPrint .= "Razón Social: " . $data['RazonSocial'] . "<br><br>";

        // print_r_f([2, $data, $resultados]);

        foreach ($validaciones as $campo => $resultado) {
            $htmlToPrint .= str_pad($campo . ': ', 25) . ($resultado ? '✓ OK' : '✗ NO COINCIDE') . "\n <br>";

            if(!$resultado) 
            {
                if($campo != 'NRO_DOCUMENTO') {
                    print_r_f([$campo, $data, $resultados]);
                }

                if ($campo == 'NRO_DOCUMENTO')
                {
                    $campo = 'Pedido Excel: ' . $data['IdPedido'];
                    $campo .= ' - Pedido WE: ' . $resultados['cod_pedido_ultra'];
                    $campo .= ' - Nro Documento Excel: ' . $data['RUC'];
                    $campo .= ' - Nro Documento WE: ' . $resultados['num_documento'];
                }

                // print_r_f([$campo, $data, $resultados]);

                $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE IdPedido = ? and RUC = ?",
                [$campo, $resultados['cod_pedido_ultra'], $data['RUC']]);
                return;
            }
        }


        // echo $htmlToPrint;

        // print_r_f([sonNombresSimiliares($data['Direccion'], $resultados['des_direccion']), $data['Direccion'], $resultados['des_direccion']]);
        // print_r_f([sonNombresSimiliares($data['Circuito'], $resultados['cir_descripcion']), $data['Circuito'], $resultados['cir_descripcion']]);
        print_r_f([convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']), $data['AnchoBanda'], $resultados['cir_ancho_banda']]);

        // print_r_f([$data, $resultados]);
        return;
    }
    else
    {
        if(strlen($resultados['cod_pedido_ultra']) != 7 or strlen($resultados['num_documento']) > 11 or strlen($resultados['num_documento']) < 8
        or is_null($resultados['cod_pedido_ultra']) or is_null($resultados['num_documento']) or $resultados['id_cliente_ultra'] < 1000 or
        is_null($resultados['id_cliente_ultra'])) 
        {
            print_r_f('ERROR 1');
        }

        $dataRepresentante = [
            'desc_tipo_documento' => '',
            'desc_numero_documento' => '',
            'desc_nombres' => '',
            'desc_apellido_paterno' => '',
            'desc_apellido_materno' => ''
        ];

        if(strlen($resultados['num_documento']) == 11 and substr($resultados['num_documento'], 0, 2) === '20')
        {
            if(strlen($resultados['doc_representante']) < 7 or strlen($resultados['doc_representante']) > 9 
                or is_null($resultados['doc_representante'])) {
                print_r_f('ERROR 2');
            }

            $directorioRepresentante = get_data_equifax($resultados['doc_representante']);

            if(!is_array($directorioRepresentante) or !isset($directorioRepresentante['PrimerNombre']) 
            or strlen($directorioRepresentante['PrimerNombre']) < 3) {
                print_r_f('ERROR 3');
            }

            if(strlen($resultados['doc_representante']) == 8) {
                $dataRepresentante['desc_tipo_documento'] = 'DNI';
            }
            else if(strlen($resultados['doc_representante']) == 9) {
                $dataRepresentante['desc_tipo_documento'] = 'CE';
            } else {
                print_r_f('ERROR 4');
            }

            $dataRepresentante['desc_numero_documento'] = $resultados['doc_representante'];
            $dataRepresentante['desc_nombres'] = $directorioRepresentante['PrimerNombre'];
            $dataRepresentante['desc_apellido_paterno'] = $directorioRepresentante['ApellidoPaterno'];
            $dataRepresentante['desc_apellido_materno'] = $directorioRepresentante['ApellidoMaterno'];
        }

        $dataEquifax = get_data_equifax($resultados['num_documento']);

        if(strlen($resultados['num_documento']) == 11 and substr($resultados['num_documento'], 0, 2) === '20')
        {
            if(!isset($dataEquifax['RazonSocial']) or !is_string($dataEquifax['RazonSocial']) or strlen($dataEquifax['RazonSocial']) < 5 or isset($dataEquifax['PrimerNombre']) or isset($dataEquifax['ApellidoPaterno']) or isset($dataEquifax['ApellidoMaterno'])) {
                print_r_f(['200', $dataEquifax['RazonSocial'], $data, $resultados, $dataEquifax]);
            }
            $dataEquifax['PrimerNombre'] = '';
            $dataEquifax['ApellidoPaterno'] = '';
            $dataEquifax['ApellidoMaterno'] = '';
        }
        else if(!isset($dataEquifax['RazonSocial']) or !is_string($dataEquifax['RazonSocial']) or strlen($dataEquifax['RazonSocial']) < 5 or 
        !isset($dataEquifax['PrimerNombre']) or !is_string($dataEquifax['PrimerNombre']) or strlen($dataEquifax['PrimerNombre']) < 5 or 
        !isset($dataEquifax['ApellidoPaterno']) or !is_string($dataEquifax['ApellidoPaterno']) or strlen($dataEquifax['ApellidoPaterno']) < 3 or 
        !isset($dataEquifax['ApellidoMaterno']) or !is_string($dataEquifax['ApellidoMaterno']) or strlen($dataEquifax['ApellidoMaterno']) < 4)
        {
            print_r_f(['300', $dataEquifax['RazonSocial'],  $dataEquifax, $data, $resultados]);
        } else {
            $dataEquifax['RazonSocial'] = '';
        }

        // print_r_f($resultados);

        if($resultados['estado'] != 'Activo') {
            // print_r_f('ERROR 6');

            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE IdPedido = ? and RUC = ?",
            ['El estado del cliente es ' . $resultados['estado'], 
            $resultados['cod_pedido_ultra'], $data['RUC']]);
            return;
        }

        // print_r_f($resultados);

        if(!is_null($resultados['fec_baja'])) {
            print_r_f('ERROR 7');
            return;
        }

        if(!is_null($resultados['fec_suspension'])) {
            print_r_f('ERROR 7');
            return;
        }

        if($resultados['desc_moneda'] != 'Soles') {
            print_r_f('ERROR 8');
            return;
        }

        if($resultados['desc_ultimo_periodo'] != '202412') {
            // print_r_f($resultados);

            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE IdPedido = ? and RUC = ?",
            ['No tiene comprobante en el periodo 12/2024', $resultados['cod_pedido_ultra'], $data['RUC']]);
            return;
        }

        $periodoEmision = $sqlServer->select("SELECT desc_situacion, cod_circuito
        FROM data_ultra_emision_202412
        where cli_nro_doc = ? and ID_PEDIDO = ? and flg_status_habil = 1;", [$resultados['num_documento'], $resultados['cod_pedido_ultra']]);
        
        if(count($periodoEmision) != 1)
        {
            print_r_f(['401', $periodoEmision, $resultados]);
            if(count($periodoEmision) == 0) {
                print_r_f(['401', $periodoEmision, $resultados]);
            }

            $encontrado = false;
            $cantidadEncontrado = 0;
            $auxPeriodoEmision = [];

            foreach($periodoEmision as $item) {
                if($item['cod_circuito'] == $resultados['cir_codigo']) {
                    $encontrado = true;
                    $cantidadEncontrado++;
                    $auxPeriodoEmision[] = $item;
                }
            }

            if($encontrado == false or $cantidadEncontrado > 1 or count($auxPeriodoEmision) != 1) {
                print_r_f(['402', $periodoEmision, $resultados]);
            }

            $periodoEmision = $auxPeriodoEmision;

            // print_r_f(['400', $periodoEmision, $resultados]);
            // $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE Item = ? AND ClienteID = ? AND CircuitoCod = ?",
            // ['No tiene comprobante generado en el periodo 202412', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
            // return;
        }

        $periodoEmision = $periodoEmision[0];

        $desc_activacion_habil = 'NO HABILITADO';

        if($periodoEmision['desc_situacion'] == 'COBR') {
            $desc_activacion_habil = 'HABILITADO';
        }

        // print_r_f([$periodoEmision, $desc_activacion_habil]);

        if($resultados['desc_oferta'] == 'Ultra 600') {
            $resultados['desc_oferta'] = 'Migración Ultra 600';
        }

        $planesValidos = ['Migración Ultra 600'];

        if(!in_array($resultados['desc_oferta'], $planesValidos)) {
            print_r_f(['El plan no es valido', $resultados, $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
        }

        $productos = ['Migración Ultra 600' => 'Migración Ultra 600 Mbps'];

        $productoFinal = $productos[$resultados['desc_oferta']];

        if(!is_string($productoFinal) or strlen($productoFinal) < 5) {
            print_r_f(['El plan no es valido', $data['Item'], $data['ClienteID'], $data['CircuitoCod']]);
        }

        $resultadosContacto = $sqlServer->select("SET NOCOUNT ON

        DECLARE @NRO_RUC VARCHAR(100) = ?
        DECLARE @CORREO VARCHAR(100), @CELULAR1 VARCHAR(100), @CELULAR2 VARCHAR(100), @ID_CLIENTE_ECOM INT

        SET @CORREO = (SELECT top 1 CTOV_EMAIL FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_EMAIL IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @CELULAR1 = (SELECT top 1 CTOV_TELEFONO_CELU FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_CELU IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @CELULAR2 = (SELECT top 1 CTOV_TELEFONO_FIJO FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_FIJO IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
        SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC);

        IF @ID_CLIENTE_ECOM IS NULL
        BEGIN
            SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM ECOM.ECOM_CLIENTE where CLIV_NRO_RUC = @NRO_RUC);
        END
        
        SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom", [$resultados['num_documento']]);
        
        if(count($resultadosContacto) != 1) {
            print_r_f('ERROR 10');
            return;
        }

        $resultadosContacto = $resultadosContacto[0];

        if($resultados['num_documento'] == '10702482572') {
            // print_r_f([$resultadosContacto['id_cliente_ecom'], $resultados['id_cliente_ecom']]);
            $resultadosContacto['id_cliente_ecom'] = 777489;
            $resultados['id_cliente_ecom'] = 777489;
        }

        if($resultadosContacto['id_cliente_ecom'] != $resultados['id_cliente_ecom']) {
            print_r_f('ERROR 11');
            return;
        }

        if(is_null($resultadosContacto['desc_correo']) or strlen($resultadosContacto['desc_correo']) < 3) {
            $resultadosContacto['desc_correo'] = $resultados['desc_correo'];
        }

        if(is_null($resultadosContacto['desc_celular']) or strlen($resultadosContacto['desc_celular']) < 3) {
            $resultadosContacto['desc_celular'] = $resultados['desc_celular'];
        }

        if(is_null($resultadosContacto['desc_telefono']) or strlen($resultadosContacto['desc_telefono']) < 3) {
            $resultadosContacto['desc_telefono'] = $resultados['desc_telefono'];
        }

        if(is_null($resultadosContacto['desc_telefono'])) {
            $resultadosContacto['desc_telefono'] = '';
        }
        
        if(is_null($resultadosContacto['desc_celular']) or is_null($resultadosContacto['desc_correo']) or is_null($resultadosContacto['id_cliente_ecom'])) {
            print_r_f('ERROR 12');
            return;
        }

        $resultadosDireccion =[
            'tipo_domicilio' => 'Hogar',
            'nro_piso' => '',
            'nro_dpto' => '',
            'torre_bloque' => '',
            'nombre_condominio' => '',
            'tipo_predio' => 'PROPIETARIO'
        ];

        if($data['Edificio'] != 'No') {
            $resultadosDireccion['tipo_domicilio'] = 'Condominio/Edificio';
            $resultadosDireccion['nombre_condominio'] = $data['NombreEdificio'];
            $resultadosDireccion['torre_bloque'] = 1;
            $resultadosDireccion['nro_piso'] = 1;
            $resultadosDireccion['nro_dpto'] = 1;
            $resultadosDireccion['tipo_predio'] = 'INQUILINO';

            /*
            print_r_f([
                'data_exel' => $data,
                'data_db' => $resultados, 
                'data_contacto' => $resultadosContacto, 
                'data_cliente' => $dataEquifax, 
                'data_representante' => $dataRepresentante,
                'data_direccion' => $resultadosDireccion
            ]);
            */
        }

        
        /* print_r_f([
            'data_exel' => $data,
            'data_db' => $resultados, 
            'data_contacto' => $resultadosContacto, 
            'data_cliente' => $dataEquifax, 
            'data_representante' => $dataRepresentante,
            'data_direccion' => $resultadosDireccion
        ]); */
        

        $insertQuery = "INSERT INTO data_ultra_procesado_uat (
            nro_documento, razon_social, id_cliente_intranet, id_cliente_ultra, 
            id_cliente_ecom, cod_pedido_ultra, cod_circuito, desc_circuito,
            razon_social_intranet, fec_vence_contrato, fec_baja, ancho_banda,
            estado_pedido, desc_moneda, desc_direccion, desc_latitud, desc_longitud,
            desc_distrito, desc_provincia, desc_region, desc_oferta, nombres, ape_paterno, ape_materno,
            desc_correo, desc_celular, desc_celular2, tipo_documento,
            tipo_vivienda, nro_piso, nro_departamento, nombre_condominio, tipo_predio, torre_bloque,
            ecom_id_contrato, ecom_id_servicio, periodo_ultima_emision,
            representante_tipo_doc, representante_nro_doc, representante_nombres, representante_ape_paterno, representante_ape_materno,
            desc_activacion_habil, desc_producto,flg_check_nombres, desc_observacion_activacion, cod_pedido_pf_ultra,
            status_ingreso_venta
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, 0, 'OK', 0, 1)";

        $params = [
            $resultados['num_documento'],
            $dataEquifax['RazonSocial'],
            0,
            $resultados['id_cliente_ultra'],
            $resultados['id_cliente_ecom'], // id_cliente_ecom (default to 0 since not provided)
            $resultados['cod_pedido_ultra'],
            0,
            '',
            '',
            $resultados['fec_vence'],
            $resultados['fec_baja'],
            convertAnchoBandaToMbpsGbps($resultados['ancho_banda']),
            $resultados['estado'],
            $resultados['desc_moneda'],
            $resultados['desc_direccion'],
            floatval($resultados['desc_latitud']),
            floatval($resultados['desc_longitud']),
            $resultados['desc_distrito'],
            $resultados['desc_provincia'],
            $resultados['desc_region'],
            $resultados['desc_oferta'],
            trim($dataEquifax['PrimerNombre']),
            trim($dataEquifax['ApellidoPaterno']),
            trim($dataEquifax['ApellidoMaterno']), 
            trim($resultadosContacto['desc_correo']),
            trim($resultadosContacto['desc_celular']),
            trim($resultadosContacto['desc_telefono']),
            $resultados['desc_tipo_documento'],
            $resultadosDireccion['tipo_domicilio'],
            $resultadosDireccion['nro_piso'],
            $resultadosDireccion['nro_dpto'],
            $resultadosDireccion['nombre_condominio'],
            $resultadosDireccion['tipo_predio'],
            $resultadosDireccion['torre_bloque'],
            $resultados['id_contrato_ecom'],
            $resultados['id_servicio_ecom'],
            $resultados['desc_ultimo_periodo'],
            trim($dataRepresentante['desc_tipo_documento']),
            trim($dataRepresentante['desc_numero_documento']),
            trim($dataRepresentante['desc_nombres']),
            trim($dataRepresentante['desc_apellido_paterno']),
            trim($dataRepresentante['desc_apellido_materno']),
            trim($desc_activacion_habil),
            trim($productoFinal)
        ];

        // print_r_f($params);

        $result = $sqlServer->insert($insertQuery, $params);

        if($result == false)
        {
            echo "Error al insertar datos en la tabla data_ultra_procesado_uat";
            print_r_f($result);
            return;
        }

        $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = 'OK' WHERE IdPedido = ? and RUC = ?",
            [$resultados['cod_pedido_ultra'], $data['RUC']]);
    }

    return;
}