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

        if(!isset($dataEquifax['RazonSocial']) and !isset($dataEquifax['PrimerNombre']))
        {
            print_r_f('ERROR 5');
        }

        if(!isset($dataEquifax['PrimerNombre'])) {
            $dataEquifax['PrimerNombre'] = '';
            $dataEquifax['ApellidoPaterno'] = '';
            $dataEquifax['ApellidoMaterno'] = '';
        } else {
            $data_aux = [
                'desc_nombres' => '',
                'desc_apellido_paterno' => '',
                'desc_apellido_materno' => ''
            ];

            $aux = explode(' ', $dataEquifax['RazonSocial']);

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

            if(strpos($dataEquifax['RazonSocial'], $data_aux['desc_apellido_paterno'] . ' ' . $data_aux['desc_apellido_materno']) === 0) {
                $isValidoAuxData = true;
            }

            if(is_array($dataEquifax['PrimerNombre']) and count($dataEquifax['PrimerNombre']) == 0 and $isValidoAuxData) {
                $dataEquifax['PrimerNombre'] = $data_aux['desc_nombres'];
            }

            if(is_array($dataEquifax['ApellidoPaterno']) and count($dataEquifax['ApellidoPaterno']) == 0 and $isValidoAuxData) {
                $dataEquifax['ApellidoPaterno'] = $data_aux['desc_apellido_paterno'];
            }

            if(is_array($dataEquifax['ApellidoMaterno']) and count($dataEquifax['ApellidoMaterno']) == 0 and $isValidoAuxData) {
                $dataEquifax['ApellidoMaterno'] = $data_aux['desc_apellido_materno'];
            }


            $dataEquifax['RazonSocial'] = '';

            if($dataEquifax['ApellidoMaterno'] == '') {
                $dataEquifax['ApellidoMaterno'] = '.';
            }

            /* if(is_array($directorio['ApellidoMaterno']) and count($directorio['ApellidoMaterno']) == 0) {
                $directorio['ApellidoMaterno'] = '.';
            } */
        }

        // print_r_f($resultados);

        if($resultados['estado'] != 'Activo') {
            // print_r_f('ERROR 6');

            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE IdPedido = ? and RUC = ?",
            ['El estado del cliente es ' . $resultados['estado'] . ' (' . ($resultados['fec_baja'] ?? $resultados['fec_suspension']) . ')', 
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

        if($resultados['desc_ultimo_periodo'] != '202411') {
            // print_r_f($resultados);

            $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = ? WHERE IdPedido = ? and RUC = ?",
            ['El último periodo de emision con estado COBRADO es ' . $resultados['desc_ultimo_periodo'], $resultados['cod_pedido_ultra'], $data['RUC']]);
            return;
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
        
        SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom", [$resultados['num_documento']]);
        
        if(count($resultadosContacto) != 1) {
            print_r_f('ERROR 10');
            return;
        }

        $resultadosContacto = $resultadosContacto[0];

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
            'tipo_domicilio' => 'HOGAR',
            'nro_piso' => '',
            'nro_dpto' => '',
            'torre_bloque' => '',
            'nombre_condominio' => '',
            'tipo_predio' => 'PROPIETARIO'
        ];

        if($data['Edificio'] != 'No') {
            $resultadosDireccion['tipo_domicilio'] = 'CONDOMINIO';
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

        
        print_r_f([
            'data_exel' => $data,
            'data_db' => $resultados, 
            'data_contacto' => $resultadosContacto, 
            'data_cliente' => $dataEquifax, 
            'data_representante' => $dataRepresentante,
            'data_direccion' => $resultadosDireccion
        ]);
        

        $insertQuery = "INSERT INTO data_ultra_procesado (
            nro_documento, razon_social, id_cliente_intranet, id_cliente_ultra, 
            id_cliente_ecom, cod_pedido_ultra, cod_circuito, desc_circuito,
            razon_social_intranet, fec_vence_contrato, fec_baja, ancho_banda,
            estado_pedido, desc_moneda, desc_direccion, desc_latitud, desc_longitud,
            desc_distrito, desc_provincia, desc_region, desc_oferta, nombres, ape_paterno, ape_materno,
            desc_correo, desc_celular, desc_celular2, tipo_documento,
            tipo_vivienda, nro_piso, nro_departamento, nombre_condominio, tipo_predio, torre_bloque,
            ecom_id_contrato, ecom_id_servicio, periodo_ultima_emision,
            representante_tipo_doc, representante_nro_doc, representante_nombres, representante_ape_paterno, representante_ape_materno
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
            $dataEquifax['PrimerNombre'],
            $dataEquifax['ApellidoPaterno'],
            $dataEquifax['ApellidoMaterno'], 
            $resultadosContacto['desc_correo'],
            $resultadosContacto['desc_celular'],
            $resultadosContacto['desc_telefono'],
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

        $sqlServer->update("UPDATE data_ultra_raw SET flg_migrado = 1, desc_observacion = 'OK' WHERE IdPedido = ? and RUC = ?",
            [$resultados['cod_pedido_ultra'], $data['RUC']]);
    }

    return;
}