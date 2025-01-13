return;

USE PE_OPTICAL_ADM;

SET LANGUAGE Spanish

IF OBJECT_ID('data_ultra_emision_prod_new', 'U') IS NOT NULL
    DROP TABLE data_ultra_emision_prod_new;


CREATE TABLE data_ultra_emision_new (
    CAT VARCHAR(50), -- Categoría, tipo de dato más flexible
    SITU VARCHAR(50), -- Situación
    TDOC VARCHAR(10), -- Tipo de documento
    RUC VARCHAR(15), -- RUC, longitud fija para Perú
    NUM_DOC VARCHAR(50), -- Número de documento
    CLIENTE VARCHAR(255), -- Nombre del cliente
    FEC_EMIS VARCHAR(50), -- Fecha de emisión
    FEC_VENC VARCHAR(50), -- Fecha de vencimiento
    FEC_CANC VARCHAR(50), -- Fecha de cancelación
    MON VARCHAR(50), -- Moneda (ISO 4217, p.ej., PEN, USD)
    SUB_TOTAL VARCHAR(50), -- Subtotal
    IGV VARCHAR(50), -- Impuesto general a las ventas
    TOTAL VARCHAR(50), -- Total
    SUMA_RECURRENTE VARCHAR(50), -- Suma recurrente
    SUMA_NO_RECURRENTE VARCHAR(50), -- Suma no recurrente
    SALDO VARCHAR(50), -- Saldo
    SECTOR VARCHAR(100), -- Sector
    TERRITORIO VARCHAR(100), -- Territorio
    TFAC VARCHAR(50), -- Tipo de factura
    ASESOR_VENTA VARCHAR(255), -- Asesor de venta
    ASESOR_POST_VENTA VARCHAR(255), -- Asesor de post venta
    DIRECCION_FISCAL VARCHAR(255), -- Dirección fiscal
    DISTRITO_FISCAL VARCHAR(100), -- Distrito fiscal
    PROVINCIA_FISCAL VARCHAR(100), -- Provincia fiscal
    DEPARTAMENTO_FISCAL VARCHAR(100), -- Departamento fiscal
    DIRECCION_ENTREGA VARCHAR(255), -- Dirección de entrega
    DISTRITO_ENTREGA VARCHAR(100), -- Distrito de entrega
    PROVINCIA_ENTREGA VARCHAR(100), -- Provincia de entrega
    DEPARTAMENTO_ENTREGA VARCHAR(100), -- Departamento de entrega
    TIPO_OPERACION VARCHAR(50), -- Tipo de operación
    TIPO_ATRIBUTO VARCHAR(50), -- Tipo de atributo
    ID_PEDIDO VARCHAR(50), -- ID del pedido
    ID_SERVICIO VARCHAR(50), -- ID del servicio
    RED VARCHAR(50), -- Red
    MONTO_FACT_SOLARIZ VARCHAR(50) -- Monto facturado solarizado
);

select * from data_ultra_emision_prod_new

exec xp_cmdshell 'dir \\10.24.100.13\DEV_Uploads\PROY-0002-2024FC-WIN-Facturacion-para-clientesULTRA-FASE-3\'
exec xp_cmdshell 'ping 10.1.4.81'
EXEC xp_cmdshell 'curl -o C:\Users\Public\Downloads\Fact_202412.csv http://10.1.4.81:280/Fact_202412.csv';
EXEC xp_cmdshell 'dir C:\Users\Public\Downloads';

exec xp_cmdshell 'net use \\10.24.100.13\DEV_Uploads\PROY-0002-2024FC-WIN-Facturacion-para-clientesULTRA-FASE-3\ /user:fherrerab@win.pe Win.123$ /persistent:yes'

USE PE_OPTICAL_ADM;

SET LANGUAGE Spanish

BULK INSERT data_ultra_emision_new
        FROM 'C:\Users\Public\Downloads\Fact_202412.csv'
            WITH
    (
                FIELDTERMINATOR = ';',
                ROWTERMINATOR = '\n',
				FIRSTROW = 2
    )
GO 

ALTER TABLE data_ultra_procesado
ADD 
	desc_activacion_habil VARCHAR NOT NULL DEFAULT '',
	desc_observacion_activacion varchar not null default '';


ALTER TABLE data_ultra_procesado 
ALTER COLUMN  desc_observacion_activacion varchar(600) not null ;



alter table data_ultra_emision_202412 drop column CAT;
EXEC sp_rename 'data_ultra_emision_202412.SITU', 'desc_situacion', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.TDOC', 'desc_tipo_doc', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.RUC', 'cli_nro_doc', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.NUM_DOC', 'compro_nro_doc', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.id_cliente', 'codigo_cliente_pago', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.CLIENTE', 'desc_cliente', 'COLUMN';
EXEC sp_rename 'data_ultra_emision_202412.MON', 'desc_moneda', 'COLUMN';
alter table data_ultra_emision_202412 drop column DISTRITO_ENTREGA;
alter table data_ultra_emision_202412 drop column ASESOR_VENTA, ASESOR_POST_VENTA, DIRECCION_FISCAL, DISTRITO_FISCAL, PROVINCIA_FISCAL, DEPARTAMENTO_FISCAL,
DIRECCION_ENTREGA, PROVINCIA_ENTREGA, DEPARTAMENTO_ENTREGA, TIPO_OPERACION, TIPO_ATRIBUTO;



select desc_oferta, desc_producto, * from data_ultra_procesado where status_ingreso_venta = 10 and status_resultado = 'ok'



select * from ECOM.MONEDA
SELECT * FROM ECOM.ECOM_TABLA_GENERAL

/*
1	PEN	SOLES
2	USD	DOLARES
3	EUR	EURO
*/



select * into data_ultra_procesado_bk9 from data_ultra_procesado

select * from data_ultra_raw

select * from data_raw_ultra_bk2 


alter table data_ultra_procesado ADD desc_producto VARCHAR(100) NOT NULL DEFAULT '';

SELECT * FROM data_ultra_emision_202412 where compro_nro_doc = 'S002-00040128'

select * from data_ultra_procesado_bk5

select distinct desc_oferta, desc_producto from data_ultra_procesado



SELECT * FROM data_ultra_procesado where nro_documento = '10263163';
SELECT * FROM data_ultra_raw where RUC = '10263163';


CREATE TABLE data_ultra_proc_detalle (
    cod_pedido_ultra INT NOT NULL,
    cod_circuito INT NOT NULL,
    desc_concepto NVARCHAR(255) NOT NULL,
    cod_moneda CHAR(2) NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    cantidad INT NOT NULL,
    megas_cantidad INT NOT NULL,
    tipo_modalidad CHAR(2) NOT NULL,
    tipo_naturaleza CHAR(2) NOT NULL,
    tipo_emision CHAR(2) NOT NULL,
    tipo_cuotas CHAR(2) NOT NULL,
    cantidad_cuotas INT NULL,
    fecha_inicio DATE NULL,
    fecha_fin DATE NULL,
    tipo_producto CHAR(2) NOT NULL
);

ALTER TABLE data_ultra_proc_detalle
ADD CONSTRAINT UQ_DatosPedidosUltra2 UNIQUE (cod_pedido_ultra, cod_circuito, desc_concepto);

SELECT RentaMensual FROM data_ultra_raw WHERE CircuitoCod = '' or IdPedido = ''


TRUNCATE TABLE data_ultra_proc_detalle



select *
from data_ultra_proc_detalle

select distinct desc_concepto
from data_ultra_proc_detalle


SELECT p.*
FROM data_ultra_procesado p
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
AND concat(p.cod_circuito, '-', p.cod_pedido_ultra) in 
(select concat(a.cod_circuito, '-', a.cod_pedido_ultra) from data_ultra_proc_detalle a)


SELECT p.cod_pedido_pf_ultra, d.*
FROM data_ultra_procesado p
INNER JOIN data_ultra_proc_detalle d ON p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_
= 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
AND p.cod_pedido_ultra = 0 and p.cod_circuito = 29640






SELECT * FROM data_ultra_emision_202412 WHERE cod_circuito =0 or flg_status_habil =0

UPDATE data_ultra_emision_202412 SET flg_status_habil = 0 WHERE compro_nro_doc = 'S002-00040204' and SUB_TOTAL = 8.48;


select * from data_ultra_procesado;
select * from data_ultra_contacto;

select a.nro_documento, a.desc_celular, b.desc_celular, a.desc_correo, b.desc_correo  
from data_ultra_procesado a
left join data_ultra_procesado_bk1 b on a.cod_circuito =b.cod_circuito and a.cod_pedido_ultra = b.cod_pedido_ultra
;

UPDATE data_ultra_procesado SET desc_activacion_habil = 'HABILITADO', desc_observacion_activacion = 'OK';



SELECT a.cli_nro_doc, COUNT(*)
FROM data_ultra_emision_202412 a
WHERE a.cod_circuito = 0 AND a.RED = 'MPLS'
GROUP BY a.cli_nro_doc
order by 2 desc

select * from data_ultra_procesado where representante_nro_doc like '102%'


SELECT a.id_data, a.cli_nro_doc, a.codigo_cliente_pago, C.CLII_ID_CLIENTE id_cliente
FROM data_ultra_emision_202412 a
INNER JOIN ECOM.ECOM_CLIENTE C ON C.CLIV_CODIGO_CLIENTE = a.codigo_cliente_pago
WHERE a.cod_circuito = 0 AND a.RED = 'MPLS'

SELECT a.id_data, a.cli_nro_doc
FROM data_ultra_emision_202412 a
WHERE a.cli_nro_doc = '' AND a.RED = 'MPLS'

SELECT * FROM data_ultra_emision_202412 where cli_nro_doc = '10702482572'
SELECT * FROM data_ultra_emision_202412_bk where cli_nro_doc = '20522093743'
SELECT cli_nro_doc, count(*) FROM data_ultra_emision_202412 GROUP BY cli_nro_doc ORDER BY 2 DESC


select id_data, desc_situacion, desc_tipo_doc, cli_nro_doc, compro_nro_doc, id_cliente, desc_cliente, FEC_EMIS, FEC_VENC, 
FEC_CANC, desc_moneda, SUB_TOTAL, IGV, TOTAL, SUMA_RECURRENTE, SUMA_NO_RECURRENTE, SALDO, TFAC, 
ID_PEDIDO, RED, MONTO_FACT_SOLARIZ INTO data_ultra_emision_202412_bk2 from data_ultra_emision_202412


insert into data_ultra_emision_202412 (desc_situacion, desc_tipo_doc, cli_nro_doc, compro_nro_doc, id_cliente, desc_cliente, FEC_EMIS, FEC_VENC, 
FEC_CANC, desc_moneda, SUB_TOTAL, IGV, TOTAL, SUMA_RECURRENTE, SUMA_NO_RECURRENTE, SALDO, TFAC, 
ID_PEDIDO, RED, MONTO_FACT_SOLARIZ)
select desc_situacion, desc_tipo_doc, cli_nro_doc, compro_nro_doc, id_cliente, desc_cliente, FEC_EMIS, FEC_VENC, 
FEC_CANC, desc_moneda, 
cast(SUB_TOTAL AS DECIMAL(18, 2)), 
cast(IGV AS DECIMAL(18, 2)), 
cast(TOTAL AS DECIMAL(18, 2)), 
cast(SUMA_RECURRENTE AS DECIMAL(18, 2)), 
cast(SUMA_NO_RECURRENTE AS DECIMAL(18, 2)), 
cast(SALDO AS DECIMAL(18, 2)),
TFAC, 
ID_PEDIDO, RED, REPLACE(MONTO_FACT_SOLARIZ, ',', '')
-- cast(MONTO_FACT_SOLARIZ AS DECIMAL(18, 2)) 
from data_ultra_emision_202412_bk2

DROP TABLE data_ultra_emision_202412



SELECT * FROM ECOM.ECOM_CLIENTE WHERE -- CLII_ID_CLIENTE = 33646 AND 
CLIV_NRO_RUC = '07768014'


ALTER TABLE data_ultra_emision_202412 ADD id_cliente int NOT NULL DEFAULT 0;

update data_ultra_emision_202412 set ID_PEDIDO = 0 WHERE ID_PEDIDO IS NULL;
update data_ultra_emision_202412 set SUMA_NO_RECURRENTE = 0 WHERE SUMA_NO_RECURRENTE IS NULL;
update data_ultra_emision_202412 set TFAC = '' WHERE TFAC IS NULL;

ALTER TABLE data_ultra_emision_202412
ADD id_data INT IDENTITY(1,1) PRIMARY KEY;
