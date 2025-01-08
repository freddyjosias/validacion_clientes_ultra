<?php

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultados = $sqlServer->select("SELECT * FROM data_ultra_procesado_prod WHERE desc_activacion_habil = '' order by 1");

foreach ($resultados as $fila)
{
    $data = $sqlServer->select("SELECT * FROM data_ultra_emision_prod WHERE cod_circuito = ? AND ID_PEDIDO = ?", [$fila['cod_circuito'], $fila['cod_pedido_ultra']]);

    if(count($data) == 0) {
        $sqlServer->update("UPDATE data_ultra_procesado_prod SET desc_activacion_habil = 'NO HABILITADO', desc_observacion_activacion = 'No tiene comprobante en el 12/2024' WHERE id_data = ?", [$fila['id_data']]);
        continue;
    } else if(count($data) > 1) {
        print_r_f(['line 16', $fila]);
        continue;
    }

    $data = $data[0];
    
    // print_r_f($data);

    if($data['desc_situacion'] == 'COBR') 
    {
        $arrayPasa = ['ok', 'Tiene OT de decremento de renta', 'En data_raw esta mal la renta mensual'];
        $arrayNoPasa = ['Tiene OT de decremento de renta - OT de Baja'];

        if(in_array($data['desc_observacion'], $arrayPasa))
        {
            $sqlServer->update("UPDATE data_ultra_procesado_prod SET desc_activacion_habil = 'HABILITADO', desc_observacion_activacion = 'ok' WHERE id_data = ?", [$fila['id_data']]);
        }
        else if(in_array($data['desc_observacion'], $arrayNoPasa))
        {
            $sqlServer->update("UPDATE data_ultra_procesado_prod SET desc_activacion_habil = 'NO HABILITADO', desc_observacion_activacion = ? WHERE id_data = ?", [$data['desc_observacion'], $fila['id_data']]);
        }
        else
        {
            print_r_f(['line 32', $data]);
        }
    } 
    else {
        $sqlServer->update("UPDATE data_ultra_procesado_prod SET desc_activacion_habil = 'NO HABILITADO', desc_observacion_activacion = 'El comprobante del 12/2024 no esta cobrado' WHERE id_data = ?", [$fila['id_data']]);
    }
}

print_r_f('ok :)');