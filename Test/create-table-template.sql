-- Active: 1723500742071@@10.1.4.81@13306@winforce_ultra
CREATE TABLE test_venta(
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
    created DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Creación',
    modified DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Última Modificación',
    latitud VARCHAR(255),
    longitud VARCHAR(255),
    dni VARCHAR(255),
    celular VARCHAR(255),
    correo VARCHAR(255),
    fecha VARCHAR(255),
    observacion VARCHAR(255),
    estado int DEFAULT 0
) COMMENT '';