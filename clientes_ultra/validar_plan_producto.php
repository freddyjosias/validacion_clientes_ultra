<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';
require_once __DIR__ . '/helper.php';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$postgres = new PostgreSQLConnection('10.1.4.25', '5432', 'opticalip_de_PORTAL', 'postgres', '');
$postgres->connect();

$resultados = $sqlServer->select("SELECT id_data, nro_documento, id_cliente_intranet, cod_pedido_ultra, cod_circuito, desc_oferta,
desc_producto FROM data_ultra_procesado");

$totalResultados = count($resultados);
$totalRecorridos = 0;
$maxPorcentaje = 0;
$maxDecena = -1;
$totalRecorridosPorcentaje = 0;

foreach($resultados as $index => $fila)
{
    // print_r_f($fila);

    if($fila['cod_pedido_ultra'] === "0" and $fila['cod_circuito'] != 0) {
        validar_plan_producto_mpls($fila, $postgres, $sqlServer);
    } else if($fila['cod_pedido_ultra'] != 0 and $fila['cod_circuito'] == "0") {
        validar_plan_producto_gpon($fila, $sqlServer);
    } else {
        print_r_f('ERROR');
    }

    $totalRecorridos++;
    $totalRecorridosPorcentaje = (int) (($totalRecorridos / $totalResultados) * 100);
    $actualDecena = (int) ($totalRecorridosPorcentaje / 10);

    if($totalRecorridosPorcentaje > $maxPorcentaje and $actualDecena > $maxDecena) {
        echo "Recorridos: " . $totalRecorridos . " - Porcentaje: " . $totalRecorridosPorcentaje . "%  <br>";
        $maxPorcentaje = $totalRecorridosPorcentaje;
        $maxDecena = $actualDecena;
    }
}

print_r_f('OK :)');


function validar_plan_producto_gpon(array $dataProcesada, $sqlServer)
{
    $data = $sqlServer->select("SELECT * FROM data_ultra_raw WHERE IdPedido = ?", [$dataProcesada['cod_pedido_ultra']]);

    if(count($data) != 1) {
        print_r_f($dataProcesada);
        return;
    }

    $data = $data[0];
    
    $resultados = $sqlServer->select("select * from data_raw_ultra_bk2 WHERE cod_pedido_ultra = ?", [$data['IdPedido']]);

    if(count($resultados) != 1) {
        print_r_f($resultados);
        return;
    }

    $resultados = $resultados[0];

    // print_r_f($resultados);

    $data['TipoServicio'] = $data['TipoServicio'] == 'Ultra 600Mbps' ? 'Ultra 600' : $data['TipoServicio'];

    $validaciones = [
        'COD_PEDIDO_ULTRA' => $data['IdPedido'] == $resultados['cod_pedido_ultra'],
        'NRO_DOCUMENTO' => $data['RUC'] == $resultados['num_documento'],
        'ANCHO_BANDA' => $data['AnchoBanda'] == convertAnchoBandaToMbpsGbps($resultados['ancho_banda']),
        'TIPO_SERVICIO' => trim($data['TipoServicio']) == trim($resultados['desc_oferta']),
        'OfertaXVelocidad' => validateOfertaXVelocidad($resultados['desc_oferta'], convertAnchoBandaToMbpsGbps($resultados['ancho_banda'])),
        'ruc_procesado' => $resultados['num_documento'] === $dataProcesada['nro_documento'],
        'cod_circuito' => $dataProcesada['cod_pedido_ultra'] == $data['IdPedido'],
        'tipo_servicio' => $dataProcesada['desc_oferta'] === convertServDescriptionToFinalDescription(trim($resultados['desc_oferta']), false)['desc_servicio'],
        'ancho_banda' => $dataProcesada['desc_producto'] === convertServDescriptionToFinalDescription(trim($resultados['desc_oferta']), false)['desc_producto']
    ];

    // print_r_f([$validaciones, [$resultados['desc_oferta'], convertAnchoBandaToMbpsGbps($resultados['ancho_banda'])], $dataProcesada, $resultados, $data]);

    foreach ($validaciones as $resultado) {
        if (!$resultado) {
            print_r_f([$validaciones, [$resultados['desc_oferta'], convertAnchoBandaToMbpsGbps($resultados['ancho_banda'])], $dataProcesada, $resultados, $data]);
            break;
        }
    }
}

function validar_plan_producto_mpls(array $dataProcesada, $postgres, $sqlServer)
{
    $data = $sqlServer->select("SELECT * FROM data_ultra_raw WHERE CircuitoCod = ?", [$dataProcesada['cod_circuito']]);

    if(count($data) != 1) {
        print_r_f($dataProcesada);
        return;
    }

    $data = $data[0];

    $resultados = $postgres->select("SELECT -- cir.*,
    c.cli_codigo, c.cli_nro_ruc, cir.cir_codigo, cir.cir_ancho_banda,
    tg.tab_descripcion serv_descripcion
    FROM opti_clientes c
    inner join noc_circuitos cir on c.cli_codigo = cir.cli_codigo
    inner join opti_tabla_general tg on cir.cir_tipo_servicio = tg.tab_codigo and tg.tab_tabla = 'SERV'
    where c.cli_codigo = ? and cir.cir_codigo = ?
    ", [$data['ClienteID'], $data['CircuitoCod']]);

    // print_r_f($resultados);

    if (count($resultados) != 1)
    {
        print_r_f($data);

        return;
    }

    $resultados = $resultados[0];

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

    // print_r_f($resultados);

    if($resultados['serv_descripcion'] == 'Ultra 1000' and $resultados['cir_ancho_banda'] == '1000.00' and 
    $data['TipoServicio'] == $resultados['serv_descripcion'] and $data['AnchoBanda'] == '1 Mbps')
    {
        $data['AnchoBanda'] = '1 Gbps';
    }

    if($data['AnchoBanda'] == '1000 Mbps') {
        $data['AnchoBanda'] = '1 Gbps';
    }

    $resultados['serv_descripcion'] = $resultados['serv_descripcion'] == 'ULTRA 600' ? 'Ultra 600' : $resultados['serv_descripcion'];
    $data['TipoServicio'] = $data['TipoServicio'] == 'ULTRA 600' ? 'Ultra 600' : $data['TipoServicio'];


    // print_r_f($resultados);
   
    // print_r_f([$dataProcesada, $resultados]);
   
    // Validaciones de datos
    $validaciones = [
        'Cliente ID' => $data['ClienteID'] == $resultados['cli_codigo'],
        'RUC' => str_pad($data['RUC'], 8, '0', STR_PAD_LEFT) == $resultados['cli_nro_ruc'],
        'Código Circuito' => $data['CircuitoCod'] == $resultados['cir_codigo'],
        'Tipo Servicio' => trim($data['TipoServicio']) == trim($resultados['serv_descripcion']),
        'Ancho Banda' => $data['AnchoBanda'] == convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda']),
        'OfertaXVelocidad' => validateOfertaXVelocidad($resultados['serv_descripcion'], convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda'])),
        'cliente_id' => $resultados['cli_codigo'] == $dataProcesada['id_cliente_intranet'],
        'ruc_procesado' => $resultados['cli_nro_ruc'] === $dataProcesada['nro_documento'],
        'cod_circuito' => $dataProcesada['cod_circuito'] == $resultados['cir_codigo'],
        'tipo_servicio' => $dataProcesada['desc_oferta'] === convertServDescriptionToFinalDescription(trim($resultados['serv_descripcion']), true)['desc_servicio'],
        'ancho_banda' => $dataProcesada['desc_producto'] === convertServDescriptionToFinalDescription(trim($resultados['serv_descripcion']), true)['desc_producto']
    ];

    // print_r_f([$validaciones, $dataProcesada, $resultados]);
    // print_r_f([$data, $resultados]);

    foreach ($validaciones as $campo => $resultado) {
        if (!$resultado) {
            print_r_f([$validaciones, [$resultados['serv_descripcion'], convertAnchoBandaToMbpsGbps($resultados['cir_ancho_banda'])], $dataProcesada, $resultados, $data]);
            break;
        }
    }
}