<?php
require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../functions.php';

$mysql = new MySQLConnection('10.1.4.81:33061', 'wincrm_ultra', 'root', 'R007w1N0r3');
$mysql->connect();

$sqlServer = new SQLServerConnection('10.1.4.20', 'PE_OPTICAL_ADM', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$resultado = $mysql->select("SELECT P.PEDI_COD_PEDIDO
FROM CRM_PEDIDO P
INNER JOIN CRM_DOCUMENTO D ON P.PEDI_COD_PEDIDO = D.DOCI_COD_PEDIDO_REF
INNER JOIN CRM_PROGRAMACION PRO ON D.DOCI_COD_DOCUMENTO = PRO.PRGI_COD_DOCUMENTO
WHERE PRO.PRGC_COD_TIPO_ESTADO = '02' AND D.DOCC_COD_TIPO_ESTADO = '03'");

$scriptFinal = '';

foreach ($resultado as $item)
{
    $checkProcesado = $sqlServer->select("SELECT * FROM data_ultra_procesado 
    where desc_activacion_habil = 'HABILITADO' AND desc_observacion_activacion = 'OK' AND cod_pedido_pf_ultra = ?", [$item['PEDI_COD_PEDIDO']]);

    if(count($checkProcesado) !== 1)
    {
        continue;
    }

    $scriptFinal .= "
    INSERT INTO CRM_CHECKLIST_ACTIVACION (`ACTI_COD_PEDIDO`, `ACTV_SERVICIO`, `ACTT_DATAJSON`, `ACTD_FECHA_INSERT`, `ACTD_FECHA_UPDATE`, 
    `ACTI_INTENTO`, `ACTI_ESTADO`, `ACTV_ACCION`) 
    VALUES (" . $item['PEDI_COD_PEDIDO'] . ", 'wincrm', '{\"codpedido\":" . $item['PEDI_COD_PEDIDO'] . ",\"fechaInstalacion\":\"2024-12-31\",\"horaIni\":\"20:20:20\",\"horaFin\":\"20:20:20\",\"fechaInstalacionReal\":\"2024-12-31\",\"observaciones\":\"\",\"codContrata\":\"1\",\"nomContrata\":\"MIGRACION WIN-ULTRA\",\"macRouter\":\"-\",\"servicio_telefono\":\"\",\"servicio_portabilidad\":0,\"codigo_generado\":\"0\"}', 
    '2024-12-31 20:20:20', '2024-12-31 20:20:20', 0, 0, 'activar');

    INSERT INTO CRM_CHECKLIST_ACTIVACION (`ACTI_COD_PEDIDO`, `ACTV_SERVICIO`, `ACTT_DATAJSON`, `ACTD_FECHA_INSERT`, `ACTD_FECHA_UPDATE`, 
    `ACTI_INTENTO`, `ACTI_ESTADO`, `ACTV_ACCION`)
    VALUES (" . $item['PEDI_COD_PEDIDO'] . ", 'winforce', '{\"codpedido\":" . $item['PEDI_COD_PEDIDO'] . ",\"observaciones\":\"\",\"servicio_telefono\":\"\",\"servicio_portabilidad\":0}',
    '2024-12-31 20:20:20', '2024-12-31 20:20:20', 0, 0, 'activar');
    ";
}

print_r_f($scriptFinal);
