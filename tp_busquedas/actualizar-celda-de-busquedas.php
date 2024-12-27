<?php


// Para SQL Server
$sqlServer = new SQLServerConnection('10.1.4.22', 'master', 'PE_OPTICAL_ERP', 'Optical123+');
$sqlServer->connect();

$mysql = new MySQLConnection('10.1.4.17:33064', 'wincoreh_db_PROD_20241206_1230', 'desarrollo', 'D3v3$2023');
$mysql->connect();

// Ejemplo de SELECT usando executeSP
$resultados = $sqlServer->executeSP("get_busquedas");

// Verificar si hay resultados antes de continuar
if (empty($resultados)) {
    echo 'No hay resultados para procesar';
    die;
}

foreach ($resultados as $resultado) {
    // echo json_encode([$resultado['bus_lat'], $resultado['bus_lng']]);

    $celda = $mysql->select("SELECT T.nombre,  C.sector
    FROM
    co_celdas C
    LEFT JOIN co_celda_tipo T on C.id_celda_tipo=T.id
    LEFT JOIN co_celda_estado E on E.id_celda_estado=C.id_celda_estado
    WHERE
    C.in_estado=0 AND
    C.id_celda_tipo > 0 AND
    E.in_disponible=1 AND
    ST_CONTAINS(C.poligono,ST_GEOMFROMTEXT('POINT(" . $resultado['bus_lat'] . " " . $resultado['bus_lng'] . ")')) 
    order by C.id_celda_tipo desc
    limit 1");
    
    if(count($celda) == 0) {
        $actualizado = $sqlServer->update(
            "UPDATE tp_busquedas SET valid_celda = 1, is_process = 0 WHERE ide_bus = ?",
            [$resultado['ide_bus']]
        );
        continue;
    }

    $celda = $celda[0];

    // echo json_encode($celda); die;

    $actualizado = $sqlServer->update(
        "UPDATE tp_busquedas SET nombre_celda = ?, sector_celda = ?, valid_celda = 1, is_process = 0 WHERE ide_bus = ?",
        [$celda['nombre'], $celda['sector'], $resultado['ide_bus']]
    );

}

echo 'ok';