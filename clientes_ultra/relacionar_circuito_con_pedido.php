<?php
set_time_limit(0);

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

const DB_MYSQL_WINCRM_ULTRA = 'db_wincrm_250115';
const DB_MYSQL_WINFORCE_ULTRA = 'winforce_db_250115';
const TABLE_DATA_ULTRA_PROCESADO = 'data_ultra_procesado';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.81:33061', DB_MYSQL_WINCRM_ULTRA, 'root', 'R007w1N0r3');
$mysql->connect();

$mysql2 = new MySQLConnection('10.1.4.81:13306', DB_MYSQL_WINFORCE_ULTRA, 'root', 'r007w1n7o4');
$mysql2->connect();

$resultados = $sqlServer->select("SELECT id_data, nro_documento, cod_pedido_ultra, cod_circuito, desc_latitud, desc_longitud,
desc_distrito, desc_provincia, desc_region, desc_oferta, nro_piso FROM " . TABLE_DATA_ULTRA_PROCESADO . " 
where cod_pedido_pf_ultra = 0 and status_ingreso_venta = 10 and status_resultado = 'ok'");

// print_r_f(count($resultados));
$cantidadNoEncontrados = 0;
$total = count($resultados);

$exoneradosUbigeo = [];
// $exoneradosUbigeo = ['000353411','000960830','007490766','06543622','07194374','07493750','07614982','07881267','08193680','08198045','08261985','08268886','09177801','09335139','09670340','10196529','10220900','10309014','10322472','10490515','10611044','10613407','20063177','20543587339','20549799591','20551846408','20565536657','20603299893','20604428328','29285108','40740355','41580048','43151755','44049621','44448779','44710951','73033903', '08272342','09751553','10058402','20545348412','75056788'];
// $exoneradosUbigeo = ['000129962','000254510','07815496','07867981','07873713','07884806','08219229','08250928','08871375','09341001','10082401','10225906','10263763','10403083858','10727107440','10867356','20521368731','20543551571','20600640560','20608536583','22967744','29672570','40077091','40490587','41420531','41669190','43050379','43342568','43753716','44133281','47760771','71242680','74222198','76191935'];

$exoneradosUbigeoLast = [];
// $exoneradosUbigeoLast = ['000353411','000960830','007490766','06543622','07194374','07493750','07614982','07881267','08193680','08198045','08261985','08268886','09177801','09335139','09670340','10196529','10220900','10309014','10322472','10490515','10611044','10613407','20063177','20543587339','20551846408','20565536657','20603299893','20604428328','29285108','40740355','41580048','43151755','44049621','44448779','44710951','73033903'];

foreach($resultados as $index => $fila)
{
    $resultados[$index]['desc_latitud'] = rtrim($fila['desc_latitud'], '0');
    $resultados[$index]['desc_longitud'] = rtrim($fila['desc_longitud'], '0');
}


foreach($resultados as $index => $fila)
{
    $pedidoEntontrado = false;
    $cantPedidosEncontrados = 0;
    $dataPedido = [];

    $resultadosIgualdad = [];

    $pedidosWinforce = $mysql2->select("SELECT *
    FROM tp_ventas v
    INNER JOIN tp_busquedas b ON v.ide_bus = b.ide_bus
    where v.ide_pedido is not null 
    and v.ide_pedido <> 0 and v.cli_num_doc = ?", [$fila['nro_documento']]);

    foreach($pedidosWinforce as $indice => $pedido)
    {
        $pedidosWinforce[$indice]['ven_lat'] = rtrim($pedido['ven_lat'], '0');
        $pedidosWinforce[$indice]['ven_lng'] = rtrim($pedido['ven_lng'], '0');
        $pedidosWinforce[$indice]['piso'] = $pedido['piso'] == ',' ? '' : $pedido['piso'];

        if($pedidosWinforce[$indice]['ide_ven'] == 2) {
            $pedidosWinforce[$indice]['piso'] = '8';
        }

        if($pedidosWinforce[$indice]['ide_ven'] == 727) {
            // $pedidosWinforce[$indice]['ven_ubigeo'] = 'LIMA LIMA MIRAFLORES';
        }
    }

    foreach($pedidosWinforce as $indice => $pedido)
    {
        $ubigeo = $fila['desc_region'] . ' ' . $fila['desc_provincia'] . ' ' . $fila['desc_distrito'];

        if(in_array($fila['nro_documento'], $exoneradosUbigeo))
        {
            // print_r_f(['exonerado', $fila, $pedido]);
            if($pedido['cli_num_doc'] === $fila['nro_documento'] and $pedido['nom_oferta'] === $fila['desc_oferta'] and 
            $pedido['ven_lat'] === $fila['desc_latitud'] and $pedido['ven_lng'] === $fila['desc_longitud'])
            {
                $pedidoEntontrado = true;
                $dataPedido = $pedido;
                $cantPedidosEncontrados++;
            }
        }
        else
        {
            if($pedido['cli_num_doc'] === $fila['nro_documento'] and $pedido['nom_oferta'] === $fila['desc_oferta'] and 
            $pedido['ven_lat'] === $fila['desc_latitud'] and $pedido['ven_lng'] === $fila['desc_longitud'] and 
            $pedido['ven_ubigeo'] === $ubigeo and $pedido['piso'] === $fila['nro_piso'])
            {
                $pedidoEntontrado = true;
                $dataPedido = $pedido;
                $cantPedidosEncontrados++;
            }
        }

        // print_r_f([$fila, $pedido, $ubigeo]);

        $resultadosIgualdad[] = [
            'nro_documento' => $fila['nro_documento'] === $pedido['cli_num_doc'],
            'desc_oferta' => $fila['desc_oferta'] === $pedido['nom_oferta'],
            'desc_latitud' => $fila['desc_latitud'] === $pedido['ven_lat'],
            'desc_longitud' => $fila['desc_longitud'] === $pedido['ven_lng'],
            'desc_ubigeo' => $pedido['ven_ubigeo'] === $ubigeo,
            'ubigeo_pedido' => $pedido['ven_ubigeo'],
            'ubigeo_fila' => $ubigeo,
            'nro_piso' => $fila['nro_piso'] === $pedido['piso'],
            'piso_pedido' => $pedido['piso'],
            'piso_fila' => $fila['nro_piso'],
        ];
    }

    // print_r_f([$cantPedidosEncontrados, $pedidoEntontrado, $resultadosIgualdad]);

    if($cantPedidosEncontrados === 0 and !$pedidoEntontrado)
    {
        $ubigeo = $fila['desc_region'] . ' ' . $fila['desc_provincia'] . ' ' . $fila['desc_distrito'];
        $auxUbigeo = $fila['desc_region'] . ' ' . $fila['desc_provincia'] . ' ' . $fila['desc_distrito'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://wincoreh.win.pe/php/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "params": {
                "latitud": "' . $fila['desc_latitud'] . '",
                "longitud": "' . $fila['desc_longitud'] . '",
                "ticked": "7f0d6d36c95496760e46ba6a03a9f901.ae131e6e5318f79977536d13629ac9ee.0a26dbc0b480871cb375fd44d3d0fa84"
            },
            "method": "logapi.getUbicacion"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response, true);

        if(!isset($response['data'][0]['nombre_distrito']) or !isset($response['data'][0]['nombre_provincia']) or !isset($response['data'][0]['nombre_region']))
        {
            print_r_f(['no encontrado 1', $response]);
            $cantidadNoEncontrados++;
            continue;
        }

        $ubigeo = mb_strtoupper(trim($response['data'][0]['nombre_region'])) . ' ' . mb_strtoupper(trim($response['data'][0]['nombre_provincia'])) . ' ' . mb_strtoupper(trim($response['data'][0]['nombre_distrito']));

        $ubigeo = quitarTildes($ubigeo);
        $resultadosIgualdad = [];

        foreach($pedidosWinforce as $indice => $pedido)
        {
            if($pedido['cli_num_doc'] === $fila['nro_documento'] and $pedido['nom_oferta'] === $fila['desc_oferta'] and 
            $pedido['ven_lat'] === $fila['desc_latitud'] and $pedido['ven_lng'] === $fila['desc_longitud'] and 
            ($pedido['ven_ubigeo'] === $ubigeo or (count($pedidosWinforce) === 1 and $auxUbigeo === $ubigeo)) 
            and $pedido['piso'] === $fila['nro_piso'])
            {
                $pedidoEntontrado = true;
                $dataPedido = $pedido;
                $cantPedidosEncontrados++;
            }

            $resultadosIgualdad[] = [
                'nro_documento' => $fila['nro_documento'] === $pedido['cli_num_doc'],
                'desc_oferta' => $fila['desc_oferta'] === $pedido['nom_oferta'],
                'desc_latitud' => $fila['desc_latitud'] === $pedido['ven_lat'],
                'desc_longitud' => $fila['desc_longitud'] === $pedido['ven_lng'],
                'desc_ubigeo' => $pedido['ven_ubigeo'] === $ubigeo,
                'ubigeo_pedido' => $pedido['ven_ubigeo'],
                'ubigeo_fila' => $ubigeo,
                'nro_piso' => $fila['nro_piso'] === $pedido['piso'],
                'piso_pedido' => $pedido['piso'],
                'piso_fila' => $fila['nro_piso'],
            ];
        }
    }

    // print_r_f([ $resultadosIgualdad, $fila, $pedidosWinforce]);

    if($cantPedidosEncontrados === 1 and $pedidoEntontrado)
    {
        $resultProcesados = $sqlServer->select("SELECT * FROM " . TABLE_DATA_ULTRA_PROCESADO . " where nro_documento = ?", [$fila['nro_documento']]);

        foreach($resultados as $index => $items)
        {
            $resultados[$index]['desc_latitud'] = rtrim($items['desc_latitud'], '0');
            $resultados[$index]['desc_longitud'] = rtrim($items['desc_longitud'], '0');
        }


        $tienenElMismoUbigeo = false;

        foreach($resultProcesados as $itemProcesado)
        {
            foreach($resultProcesados as $itemProcesado2)
            {
                if($itemProcesado['desc_latitud'] == $itemProcesado2['desc_latitud'] 
                and $itemProcesado['desc_longitud'] == $itemProcesado2['desc_longitud'] and
                $itemProcesado['id_data'] != $itemProcesado2['id_data'] and $itemProcesado['nro_piso'] == $itemProcesado2['nro_piso'])
                {
                    $tienenElMismoUbigeo = true;
                    break;
                }
            }
        }

        if($tienenElMismoUbigeo or count($resultProcesados) == 0)
        {
            // print_r_f(['no encontrado 3', $resultProcesados]);
            $cantidadNoEncontrados++;
            continue;
        }

        // print_r_f(['no encontrado 4',$fila, $dataPedido]);

        $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$dataPedido['ide_pedido'], $fila['id_data'], $fila['nro_documento']]);
            
        // print_r_f(['no encontrado 4', $fila, $dataPedido]);
        continue;
    }
    else {
        // continue;
        // print_r_f(['no encontrado 5', $cantPedidosEncontrados, $fila, $dataPedido, $pedidosWinforce]);
    }

    // $cantidadNoEncontrados++;
    // continue;

    // print_r_f([$fila, $pedidoEntontrado, $resultadosIgualdad, $pedidosWinforce]);

    // print_r_f(['start', $fila]);

    $pedidosUltra = $mysql->select("SELECT P.PEDI_COD_PEDIDO, C.CLIV_NUMERO_DOCUMENTO, D.DIRN_LATITUD, D.DIRN_LONGITUD, 
    UPPER(U1.UBIV_DESCRIPCION) desc_distrito, UPPER(U2.UBIV_DESCRIPCION) desc_provincia, O.OFTV_NOMBRE
    FROM CRM_PEDIDO P
    INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
    INNER JOIN CRM_DIRECCION D ON D.DIRI_COD_DIRECCION = P.PEDI_COD_DIRECCION
    INNER JOIN CRM_UBIGEO U1 ON U1.UBIC_UBIGEO = D.DIRC_UBIGEO
    INNER JOIN CRM_UBIGEO U2 ON U2.UBIC_UBIGEO = CONCAT(LEFT(U1.UBIC_UBIGEO,6), '00')
    INNER JOIN CRM_OFERTA O ON O.OFTI_COD_OFERTA = P.OFTI_COD_OFERTA
    WHERE C.CLIV_NUMERO_DOCUMENTO = ?", [$fila['nro_documento']]);

    if(count($pedidosUltra) == 0)
    {
        print_r_f(['no encontrado 2', $pedidosUltra, $fila]);
        $cantidadNoEncontrados++;
        continue;
    }
    // print_r_f([$index, $pedidosUltra]);

    $pedidoEntontrado = false;
    $cantPedidosEncontrados = 0;
    $dataPedido = [];

    $resultadosIgualdad = [];

    foreach($pedidosUltra as $indice => $pedido)
    {
        $pedidosUltra[$indice]['DIRN_LATITUD'] = round($pedido['DIRN_LATITUD'], 4);
        $pedidosUltra[$indice]['DIRN_LONGITUD'] = round($pedido['DIRN_LONGITUD'], 4);

        $pedido['DIRN_LATITUD'] = $pedidosUltra[$indice]['DIRN_LATITUD'];
        $pedido['DIRN_LONGITUD'] = $pedidosUltra[$indice]['DIRN_LONGITUD'];

        $fila['desc_latitud'] = round($fila['desc_latitud'], 4);
        $fila['desc_longitud'] = round($fila['desc_longitud'], 4);

        if($pedido['CLIV_NUMERO_DOCUMENTO'] === $fila['nro_documento'] and $pedido['OFTV_NOMBRE'] === $fila['desc_oferta'] and 
        $pedido['DIRN_LATITUD'] === $fila['desc_latitud'] and $pedido['DIRN_LONGITUD'] === $fila['desc_longitud'] and 
        $pedido['desc_distrito'] === $fila['desc_distrito'] and $pedido['desc_provincia'] === $fila['desc_provincia'])
        {
            $pedidoEntontrado = true;
            $dataPedido = $pedido;
            $cantPedidosEncontrados++;
        }

        $resultadosIgualdad[] = [
            'nro_documento' => $fila['nro_documento'] === $pedido['CLIV_NUMERO_DOCUMENTO'],
            'desc_oferta' => $fila['desc_oferta'] === $pedido['OFTV_NOMBRE'],
            'desc_latitud' => $fila['desc_latitud'] === $pedido['DIRN_LATITUD'],
            'desc_longitud' => $fila['desc_longitud'] === $pedido['DIRN_LONGITUD'],
            'desc_distrito' => $fila['desc_distrito'] === $pedido['desc_distrito'],
            'desc_provincia' => $fila['desc_provincia'] === $pedido['desc_provincia'],
        ];
    }

    // print_r_f([$resultadosIgualdad, $fila]);

    if($cantPedidosEncontrados > 1)
    {
        // echo 'Mas de un pedido encontrado para el cliente: ' . $fila['nro_documento'] . "\n"; //die;
        $cantidadNoEncontrados++;
        continue;
    }
    else if($cantPedidosEncontrados === 1 and $pedidoEntontrado)
    {
        $resultProcesados = $sqlServer->select("SELECT * FROM " . TABLE_DATA_ULTRA_PROCESADO . " where nro_documento = ?", [$fila['nro_documento']]);

        $estanEnElMismoDistrito = false;

        foreach($resultProcesados as $itemProcesado)
        {
            foreach($resultProcesados as $itemProcesado2)
            {
                if($itemProcesado['desc_distrito'] == $itemProcesado2['desc_distrito'] and $itemProcesado['desc_provincia'] == $itemProcesado2['desc_provincia'] and
                $itemProcesado['id_data'] != $itemProcesado2['id_data'])
                {
                    $estanEnElMismoDistrito = true;
                    break;
                }
            }
        }

        if($estanEnElMismoDistrito or count($resultProcesados) == 0)
        {
            // print_r_f(['no encontrado 3', $resultProcesados]);
            $cantidadNoEncontrados++;
            continue;
        }

        // print_r_f(['no encontrado 4', $resultProcesados]);

        $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$dataPedido['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
        continue;
    }

    if(count($pedidosUltra) === 1)
    {
        $resultProcesados = $sqlServer->select("SELECT * FROM " . TABLE_DATA_ULTRA_PROCESADO . " where nro_documento = ?", [$fila['nro_documento']]);
        $pedidosUltra = $pedidosUltra[0];

        if(count($resultProcesados) === 1 and $pedidosUltra['OFTV_NOMBRE'] === $resultProcesados[0]['desc_oferta'] and
        in_array($pedidosUltra['CLIV_NUMERO_DOCUMENTO'], $exoneradosUbigeoLast))
        {
            $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$pedidosUltra['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
            continue;
        }

        $coincidenciaPorDistrito = false;
        $cantidadCoincidenciaPorDistrito = 0;
        $idDataCoincidencia = 0;

        foreach($resultProcesados as $itemProcesado)
        {
            if($itemProcesado['desc_distrito'] == $pedidosUltra['desc_distrito'] and $itemProcesado['desc_provincia'] == $pedidosUltra['desc_provincia']
            and $itemProcesado['desc_oferta'] == $pedidosUltra['OFTV_NOMBRE'])
            {
                $coincidenciaPorDistrito = true;
                $cantidadCoincidenciaPorDistrito++;
                $idDataCoincidencia = $itemProcesado['id_data'];
            }
        }

        if($coincidenciaPorDistrito and $cantidadCoincidenciaPorDistrito === 1 and $idDataCoincidencia === $fila['id_data'])
        {
            $sqlServer->update("UPDATE " . TABLE_DATA_ULTRA_PROCESADO . " SET cod_pedido_pf_ultra = ? 
            WHERE id_data = ? and nro_documento = ?", [$pedidosUltra['PEDI_COD_PEDIDO'], $fila['id_data'], $fila['nro_documento']]);
            continue;
        }

        $cantidadNoEncontrados++;
        continue;

        // print_r_f(['no encontrado 1', $fila, $pedidosUltra, $resultProcesados]);
    }

    $cantidadNoEncontrados++;
    continue;

    if(!$pedidoEntontrado)
    {
        if($index > 1)
        {
            print_r_f([$fila, $pedidosUltra, $resultadosIgualdad]);
        }

        continue;
    }

    print_r_f(['encontrado', $pedidosUltra, $fila]);
}

function quitarTildes($cadena)
{
    $cadena = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $cadena);
    $cadena = str_replace(['Á', 'É', 'Í', 'Ó', 'Ú'], ['A', 'E', 'I', 'O', 'U'], $cadena);
    return $cadena;
}

print_r_f('Cantidad de no encontrados: ' . $cantidadNoEncontrados . ' de ' . $total);

print_r_f('OK :)');
