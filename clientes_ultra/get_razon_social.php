<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

function get_razon_social($nro_documento, $tipo_persona, $tipo_doc)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://10.1.4.11:8000/equifax?nro_documento='.$nro_documento.'&tipo_persona='.$tipo_persona.'&tipo_doc='.$tipo_doc.'&idUsuario=25',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
        'apikey: RpOPMXJdOtyLaN6BhaZH4fcw75lwYfXA'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $resultados = json_decode($response, true);

    $array_modulos = $resultados['soapBody']['ns3GetReporteOnlineResponse']['ns2ReporteCrediticio']['Modulos']['Modulo'] ?? [];
    $directorio = null;
    $directorioEmpresas = null;

    foreach($array_modulos as $modulo) {
        if($modulo['Codigo'] == '602') {
            $directorio = $modulo['Data']['ns3DirectorioPersona'];
        }
        if($modulo['Codigo'] == '878') {
            $directorioEmpresas = $modulo['Data']['ns3DirectorioSUNAT']['Directorio']['RazonSocial'];
        }
    }

    if($directorio == null and $directorioEmpresas == null) {
        return null;
        print_r_f($array_modulos);
        echo 'ERROR 2'; die;
    }
    if($directorioEmpresas != null) {
        $directorio = [
            'RazonSocial' => $directorioEmpresas,
        ];
    }
    else {
        $directorio = [
            'RazonSocial' => $directorio['Nombres'],
            'ApellidoPaterno' => $directorio['ApellidoPaterno'],
            'ApellidoMaterno' => $directorio['ApellidoMaterno'],
            'PrimerNombre' => $directorio['PrimerNombre'],
        ];
    }

    return $directorio;

}

function get_data_equifax($nro_documento)
{
    $tipo_persona = '1';
    $tipo_doc = '1';

    $dos_primeros_digitos = substr($nro_documento, 0, 2);

    if(strlen($nro_documento) == 11 and ($dos_primeros_digitos == '20' or $dos_primeros_digitos == '10')) {
        $tipo_doc = '6';

        if ($dos_primeros_digitos == '20') {
            $tipo_persona = '2';
        }
    } else if (strlen($nro_documento) != 8 or !is_numeric($nro_documento)) {
        $tipo_doc = '3';
    }
    
    return get_razon_social($nro_documento, $tipo_persona, $tipo_doc);
}

function main()
{
    $sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
    $sqlServer->connect();

    $resultados = $sqlServer->select("SELECT nro_documento FROM data_ultra_procesado_uat WHERE status_resultado <> 'ok' order by nro_documento");

    // print_r_f(['resultados', $resultados]);

    $data = [
        'cantidadEmpresas' => 0,
        'cantidadPersonas' => 0,
        'cantidadError' => 0,
    ];

    foreach($resultados as $fila) {
        $directorio = get_data_equifax($fila['nro_documento']);

        if(isset($directorio['RazonSocial']) and !isset($directorio['PrimerNombre']))
        {
            $data['cantidadEmpresas']++;
            // $sqlServer->update("UPDATE data_ultra_procesado SET razon_social = ? WHERE id_data = ?", [$directorio['RazonSocial'], $fila['id_data']]);
        }
        else if(isset($directorio['PrimerNombre']))
        {
            $data['cantidadPersonas']++;
            // $sqlServer->update("UPDATE data_ultra_procesado SET nombres = ?, ape_paterno = ?, ape_materno = ? WHERE id_data = ?", [$directorio['PrimerNombre'], $directorio['ApellidoPaterno'], $directorio['ApellidoMaterno'], $fila['id_data']]);
        } else {
            $data['cantidadError']++;
            // echo 'ERROR 1'; die;
        }
    }

    return $data;
}

$resultados = main();

print_r_f(['resultados', $resultados]);


// $directorio = get_data_equifax('20608536583');

print_r_f('ok :)');