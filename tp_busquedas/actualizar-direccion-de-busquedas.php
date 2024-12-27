<?php



// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.22', 'master', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.17:33063', 'winforce_db202412050950', 'root', 'r00t-P4SS');
$mysql->connect();

// Ejemplo de SELECT usando executeSP
$resultados = $sqlServer->select("
    SELECT top 10000 *
    FROM tp_busquedas
    WHERE valid_celda = 1
    and created_at >= '2024-01-01'
    and nombre_celda = ''
    AND cod_pedido = 0
    and posible_pedido = 0
    and tiene_pedido = 0
    and pedido_instalado = 0
    and direccion = ''");

// Verificar si hay resultados antes de continuar
if (empty($resultados)) {
    echo 'No hay resultados para procesar';
    die;
}

foreach ($resultados as $resultado) {
    // echo json_encode([$resultado['bus_lat'], $resultado['bus_lng']]);

    $celda = $mysql->select("SELECT bus_res
    FROM tp_busquedas
    WHERE ide_bus = ?", [$resultado['ide_bus']]);

    $celda = $celda[0];

    // echo json_encode($celda); die;

    $actualizado = $sqlServer->update(
        "UPDATE tp_busquedas SET direccion = ? WHERE ide_bus = ?",
        [$celda['bus_res'], $resultado['ide_bus']]
    );

}

echo 'ok';