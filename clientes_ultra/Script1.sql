USE PE_OPTICAL_ADM;

SET LANGUAGE Spanish
/*
IF OBJECT_ID('data_ultra_raw', 'U') IS NOT NULL
    DROP TABLE data_ultra_raw;


CREATE TABLE data_ultra_raw (
    IdWinforce VARCHAR(50),
    IdPedido VARCHAR(50),
    Item VARCHAR(50),
    ClienteID VARCHAR(50),
    Categoria VARCHAR(50),
    RazonSocial VARCHAR(200),
    Rubro VARCHAR(200),
    CircuitoCod VARCHAR(50),
    RUC VARCHAR(20),
    Circuito VARCHAR(100),
    Tipo VARCHAR(50),
    Latitud VARCHAR(50),
    Longitud VARCHAR(50),
    Ubigeo VARCHAR(50),
    Direccion VARCHAR(500),
    Distrito VARCHAR(100),
    Departamento VARCHAR(100),
    Provincia VARCHAR(100),
    Nodo VARCHAR(100),
    TipoServicio VARCHAR(100),
    MedioFisico VARCHAR(100),
    Origen VARCHAR(100),
    AnchoBanda VARCHAR(50),
    PrecioInstalacion DECIMAL(12,2),
    RentaMensual VARCHAR(50),
    DuracionInicial INT,
    FechaRegistro VARCHAR(50),
    FechaInstalacion VARCHAR(50),
    AltaOperativa VARCHAR(50),
    EstadoAlta VARCHAR(50),
    VencimientoInicial VARCHAR(50),
    VencimientoActual VARCHAR(50),
    BajaOperativa VARCHAR(50),
    MotivoBaja VARCHAR(200),
    Estado VARCHAR(50),
    ModeloRouter VARCHAR(100),
    PrecioRouter DECIMAL(12,2),
    Modalidad VARCHAR(100),
    Moneda VARCHAR(50),
    RentaSoles VARCHAR(50),
    RentaSinIGV VARCHAR(50),
    Tecnologia VARCHAR(100),
    Accion VARCHAR(100),
    EstadoPedido VARCHAR(100),
    Edificio VARCHAR(100),
    NombreEdificio VARCHAR(200)
);

BULK INSERT data_ultra_raw
        FROM '\\10.1.2.112\ti_software_factory\1. PasosQA\PROY-0002-2024FC-WIN-Facturacion-para-clientesULTRA\BaseDatosTraspasoMplsGponvrs12Nov24.csv'
            WITH
    (
                FIELDTERMINATOR = ';',
                ROWTERMINATOR = '\n',
				FIRSTROW = 2
    )
GO */


-- SELECT * INTO data_ultra_raw_bk FROM data_ultra_raw

/* ALTER TABLE data_ultra_raw
ADD flg_migrado TINYINT NOT NULL default 0

ALTER TABLE data_ultra_raw
ADD desc_observacion varchar(5000) NOT NULL default '' */

IF OBJECT_ID('data_ultra_procesado', 'U') IS NOT NULL
    DROP TABLE data_ultra_procesado;

CREATE TABLE data_ultra_procesado (
	id_data INT IDENTITY(1,1) PRIMARY KEY, 
	nro_documento VARCHAR(12) NOT NULL,
	razon_social VARCHAR(100) NOT NULL,
	nombres VARCHAR(100) NOT NULL,
	ape_paterno VARCHAR(100) NOT NULL,
	ape_materno VARCHAR(100) NOT NULL,
	id_cliente_intranet INT NOT NULL,
	id_cliente_ultra INT NOT NULL,
	id_cliente_ecom INT NOT NULL,
	cod_pedido_ultra INT NOT NULL,
	cod_circuito INT NOT NULL,
	desc_circuito VARCHAR(100) NOT NULL,
	razon_social_intranet VARCHAR(100) NOT NULL,
	fec_vence_contrato DATE NULL,
	fec_baja date null, 
	ancho_banda VARCHAR(20) not null,
	estado_pedido VARCHAR(30) not null,
	desc_moneda VARCHAR(20) not null,
	desc_direccion varchar(200) not null,
	desc_latitud decimal(18, 10) not null,
	desc_longitud decimal(18, 10) not null,
	desc_distrito VARCHAR(100) NOT NULL,
	desc_provincia VARCHAR(100) NOT NULL,
	desc_region VARCHAR(100) NOT NULL,
	desc_oferta VARCHAR(100) NOT NULL,
)

ALTER TABLE data_ultra_procesado
ADD 
    nombres VARCHAR(100) NOT NULL DEFAULT '',
    ape_paterno VARCHAR(100) NOT NULL DEFAULT '',
    ape_materno VARCHAR(100) NOT NULL DEFAULT '';

ALTER TABLE data_ultra_procesado
ADD desc_celular VARCHAR(20) NOT NULL DEFAULT '',
desc_correo varchar(50) not null default '',
tipo_documento varchar(10) NOT NULL DEFAULT '',
tipo_vivienda varchar(30) not null default '',
representante_tipo_doc varchar(10) not null default '',
representante_nro_doc varchar(12) not null default '',
representante_nombres VARCHAR(100) NOT NULL DEFAULT '',
representante_ape_paterno VARCHAR(100) NOT NULL DEFAULT '',
representante_ape_materno VARCHAR(100) NOT NULL DEFAULT '';

ALTER TABLE data_ultra_procesado
ADD status_ingreso_venta tinyint not null default 0;

ALTER TABLE data_ultra_procesado
ADD desc_celular2 VARCHAR(20) NOT NULL DEFAULT '';


ALTER TABLE data_ultra_procesado
ADD 
    nro_piso VARCHAR(10) NOT NULL DEFAULT '',
    nro_departamento VARCHAR(10) NOT NULL DEFAULT '';

	
ALTER TABLE data_ultra_procesado
ADD 
    nombre_condominio VARCHAR(20) NOT NULL DEFAULT '';

ALTER TABLE data_ultra_procesado
ADD 
    torre_bloque VARCHAR(20) NOT NULL DEFAULT '';
	

ALTER TABLE data_ultra_procesado
ADD 
    periodo_ultima_emision VARCHAR(6) NOT NULL DEFAULT '',
	ecom_id_servicio INT NOT NULL DEFAULT 0,
	ecom_id_servicio_detalle INT NOT NULL DEFAULT 0;


ALTER TABLE data_ultra_procesado
ADD 
    cod_pedido_pf_ultra INT NOT NULL DEFAULT 0;

	ALTER TABLE data_ultra_procesado
DROP CONSTRAINT DF__data_ultr__ecom___687B5397;

ALTER TABLE data_ultra_procesado DROP COLUMN ecom_id_servicio_detalle

ALTER TABLE data_ultra_procesado
ADD 
	ecom_id_contrato INT NOT NULL DEFAULT 0;

ALTER TABLE data_ultra_procesado
ALTER COLUMN nombre_condominio VARCHAR(100) not null;




select * from ECOM.PEDIDOS_ULTRA


UPDATE data_ultra_raw SET flg_migrado = 0, desc_observacion = '' -- where desc_observacion <> 'OK'
TRUNCATE TABLE data_ultra_procesado

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Ancho Banda Excel: 600 Mbps - Ancho Banda WE: 0.00 - Ancho Banda WE Convertido: 0.00'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Ancho Banda Excel: 1 Mbps - Ancho Banda WE: 1000.00 - Ancho Banda WE Convertido: 1 Gbps'


update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Plan Excel: Ultra 1000 - Plan WE: Ultra 1000 - Ancho Banda Excel: 600 Mbps - Ancho Banda WE: 600000.00 - Ancho Banda WE Convertido: 600 Mbps'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Plan Excel: Ultra 1000 - Plan WE: Ultra 1000 - Ancho Banda Excel: 800 Mbps - Ancho Banda WE: 800000.00 - Ancho Banda WE Convertido: 800 Mbps'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Ancho Banda Excel: 800 Mbps - Ancho Banda WE: 0.00 - Ancho Banda WE Convertido: 0.00'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Plan Excel: Ultra 800 - Plan WE: Ultra 800 - Ancho Banda Excel: 800 Mbps - Ancho Banda WE: 0.00 - Ancho Banda WE Convertido: 0.00'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where desc_observacion = 'Plan Excel: Ultra 800 - Plan WE: Ultra 800 - Ancho Banda Excel: 800 Mbps - Ancho Banda WE: 800 - Ancho Banda WE Convertido: 800 Mbps'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Ancho Banda Excel: 1000 Mbps - Ancho Banda WE: 1000001.00 - Ancho Banda WE Convertido: 1000001.00'

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Ancho Banda Excel: 600 Mbps - Ancho Banda WE:  - Ancho Banda WE Convertido: '

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Ancho Banda Excel: 800 Mbps - Ancho Banda WE:  - Ancho Banda WE Convertido: '

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Dirección Excel: CALLE EL MONTÑCULO NRO 146 INT. 288 - Dirección WE: '
-- CALLE EL MONTÑCULO NRO 146 INT. 288 JR. LA CORUÑA NRO 283 URB. LA ESTANCIA, LA MOLINA, LIMA, LIMA
update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Dirección Excel: JR. LA CORUÑA NRO 283 URB. LA ESTANCIA, LA MOLINA, LIMA, LIMA - Dirección WE: '

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where 
desc_observacion = 'Plan Excel: Ultra Wifi Total - Plan WE: Ultra Wifi Total - Ancho Banda Excel: 1 Mbps - Ancho Banda WE: 1000.00 - Ancho Banda WE Convertido: 1 Gbps'


SELECT desc_observacion, * FROM data_ultra_raw where desc_observacion not in ('OK', '') AND CircuitoCod IS NOT NULL

SELECT desc_observacion, * FROM data_ultra_raw where desc_observacion in ('') AND CircuitoCod IS NOT NULL

select desc_observacion, count(*)
from data_ultra_raw
where CircuitoCod IS NOT NULL
AND desc_observacion not in ('OK', 'El estado del cliente es Baja', 'El estado del cliente es Suspendido por Falta Pago',
'El estado del cliente es Suspendido Solicitud Cliente', 'El último periodo de emision con estado COBRADO es 202403',
'El último periodo de emision con estado COBRADO es 202408', 'El último periodo de emision con estado COBRADO es 202409',
'El último periodo de emision con estado COBRADO es 202410', 'El último periodo de emision con estado COBRADO es 202412',
'Estado Excel: Suspendido por Falta Pago - Estado WE: Activo', 'No se encontró alguna emision en ECOM',
'Plan Excel: Ultra Wifi Total - Plan WE: Ultra Wifi Total - Ancho Banda Excel: 1 Mbps - Ancho Banda WE: 1000.00 - Ancho Banda WE Convertido: 1 Gbps')
AND desc_observacion not like 'La fecha de baja operativa es %'
group by desc_observacion

select desc_observacion, count(*)
from data_ultra_raw
where CircuitoCod IS NOT NULL
AND desc_observacion not in ('OK')
group by desc_observacion

select desc_observacion, count(*)
from data_ultra_raw
where CircuitoCod IS NULL
AND desc_observacion not in ('OK')
group by desc_observacion

select desc_observacion, count(*)
from data_ultra_raw
where desc_observacion not in ('OK')
group by desc_observacion

select * from data_ultra_raw where desc_observacion not in ('OK')

select * from ECOM.ECOM_CONTRATO WHERE CONI_ID_CONTRATO = 17606523
SELECT TOP 100 * FROM ECOM.ECOM_SERVICIO WHERE CONI_ID_CONTRATO = 17606523

SELECT TOP 1 * FROM data_ultra_raw WHERE flg_migrado = 1

SELECT desc_observacion, * FROM data_ultra_raw where desc_observacion in ('')
select * FROM data_ultra_procesado ORDER BY 1 DESC
select * from data_ultra_raw where IdPedido = '5006022'

select * FROM data_ultra_procesado WHERE nro_documento = '70248257'

select * from data_ultra_raw where RUC LIKE '%70248257%'

SELECT nro_documento, desc_oferta, count(*) FROM data_ultra_procesado GROUP BY nro_documento, desc_oferta order by 3 desc

select desc_oferta, count(*) from data_ultra_procesado group by desc_oferta


SELECT distinct concat(desc_oferta, ' MPLS') FROM data_ultra_procesado WHERE cod_circuito = 0

SELECT distinct concat(desc_oferta, ' MPLS') FROM data_ultra_procesado WHERE cod_circuito <> 0

-- UPDATE data_ultra_procesado SET desc_oferta = concat(desc_oferta, ' MPLS') WHERE cod_circuito <> 0

-- delete from data_ultra_procesado where cod_pedido_ultra = '5006022'
update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where IdPedido = '5006022'

SELECT nro_documento, razon_social, nombres, ape_paterno, ape_materno, razon_social_intranet FROM data_ultra_procesado 
select * FROM data_ultra_procesado WHERE ape_materno = '.'
SELECT * FROM data_ultra_procesado  where desc_oferta = 'ULTRA 600 'ta

UPDATE data_ultra_procesado SET razon_social = '1' where id_data = 1


SELECT * FROM data_ultra_procesado where nro_documento = 'F60717293';
select * from data_ultra_raw where RUC = 100705;



select * INTO PE_OPTICAL_AUDITORIA.[dbo].data_ultra_procesado_bk2 FROM data_ultra_procesado
select * INTO PE_OPTICAL_AUDITORIA.[dbo].data_ultra_raw_bk2 FROM data_ultra_raw

select * INTO data_ultra_procesado_bk1 FROM  [10.1.4.20].[PE_OPTICAL_ADM].dbo.data_ultra_procesado
select * INTO data_ultra_raw_bk1 FROM [10.1.4.20].[PE_OPTICAL_ADM].dbo.data_ultra_raw


SELECT CLIV_CODIGO_CLIENTE FROM ECOM.ECOM_CLIENTE

select * from data_ultra_contacto

select * from ECOM.ECOM_CLIENTE WHERE CLII_ID_CLIENTE = 33817
-- 08251160		7167	0	33817


-- status_ingreso_venta
-- fec_baja IS NULL
-- estado_pedido = 'Activo'

SELECT * FROM ECOM.ECOM_CLIENTE WHERE CLIV_NRO_RUC = '00353411'


SELECT desc_observacion, * FROM data_ultra_raw where RUC = '353411'
delete data_ultra_procesado where nro_documento = '46080472'
UPDATE data_ultra_raw set flg_migrado = 0 where RUC = '46080472'

select a.*
from data_ultra_raw a
left join data_ultra_emision_202412_bk b ON a.RUC = b.cli_nro_doc
where b.cli_nro_doc is null

select * from data_ultra_raw where RUC  in ('10702482572', '20603146663') -- 5000084

select * from data_ultra_raw WHERE IdPedido = 5000084

update data_ultra_raw set RUC = '10702482572' WHERE IdPedido = 5000084

SELECT * FROM data_ultra_emision_202412 WHERE cli_nro_doc in ('10702482572', '20603146663')

-- Pedido Excel: 5000084 - Pedido WE: 5000084 - Nro Documento Excel: 20603146663 - Nro Documento WE: 10702482572

SELECT CO.CONI_ID_CONTRATO, EC.EMCI_ID_EMP_CLI, S.SERI_ID_SERVICIO, EC.EMPI_ID_EMPRESA
FROM  PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_CLIENTE CLI -- ON C.cli_codigo_ecom = CLI.CLII_ID_CLIENTE
INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_EMPRESA_CLIENTE EC ON CLI.CLII_ID_CLIENTE = EC.CLII_ID_CLIENTE
INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_CONTRATO CO ON EC.EMCI_ID_EMP_CLI = CO.EMCI_ID_EMP_CLI
INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.ECOM_SERVICIO S ON CO.CONI_ID_CONTRATO = S.CONI_ID_CONTRATO -- AND S.SERI_ID_SERVICIO = CIR.cir_codigo_ecom
WHERE EC.EMPI_ID_EMPRESA IN (10, 20) AND CLI.CLIV_NRO_RUC = '46080472' AND CLI.CLII_ID_CLIENTE = 1 AND S.SERI_ID_SERVICIO = 10

SELECT TOP 1 C.*
FROM PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.COMPROBANTE C
INNER JOIN PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
WHERE C.ESTI_ID_ESTADO = 9 AND C.COMC_COD_ENTIDAD = 730022 -- AND CD.SERI_ID_SERVICIO = 1
ORDER BY 1 DESC

SELECT DISTINCT MONI_ID_MONEDA
FROM PE_OPTICAL_ADM_PROD_20241222_071629.ECOM.COMPROBANTE C
WHERE C.ESTI_ID_ESTADO = 9 AND C.COMC_TIPO_OPERACION IN ('A', 'C')
AND C.COMC_COD_EMPRESA = 20


exec sp_columns 'COMPROBANTE'


SELECT * FROM ECOM.MONEDA

SELECT TOP 1 C.COMV_PERIODO_COMPROBANTE
    FROM PE_OPTICAL_ADM_PROD_20241220_071311.ECOM.COMPROBANTE C
    INNER JOIN PE_OPTICAL_ADM_PROD_20241220_071311.ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE

SET NOCOUNT ON

DECLARE @NRO_RUC VARCHAR(50) = '20565536657'
DECLARE @CORREO VARCHAR(30), @CELULAR1 VARCHAR(30), @CELULAR2 VARCHAR(30), @ID_CLIENTE_ECOM INT

SET @CORREO = (SELECT top 1 CTOV_EMAIL FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_EMAIL IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @CELULAR1 = (SELECT top 1 CTOV_TELEFONO_CELU FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_CELU IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @CELULAR2 = (SELECT top 1 CTOV_TELEFONO_FIJO FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_FIJO IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC);

SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom


-- Ancho Banda (600 Mbps - 600000.00 - 600000.00)

select C.CLIV_NRO_RUC, CC.CTOV_EMAIL, CC.CTOV_TELEFONO_FIJO, CC.CTOV_TELEFONO_CELU, CC.CTOD_FECHA_ALTA
from ECOM.ECOM_CLIENTE C WITH (NOLOCK)
LEFT JOIN ECOM.ECOM_CLIENTE_CONTACTO CC  WITH (NOLOCK) ON C.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
WHERE C.CLIV_NRO_RUC IN ('20511434263', '08249218') 

DELETE FROM data_ultra_procesado WHERE LEN(nro_documento) <> 8

ALTER TABLE data_ultra_procesado
ADD CONSTRAINT UQ_data_ultra_procesado_pedido_circuito UNIQUE (cod_pedido_ultra, cod_circuito);



SELECT TOP 100 * FROM ECOM.ECOM_CLIENTE_CONTACTO

truncate TABLE data_ultra_contacto

insert into data_ultra_contacto
select C.CLII_ID_CLIENTE, C.CLIV_RAZON_SOCIAL, C.CLIV_NRO_RUC, CC.CTOV_EMAIL, CC.CTOV_TELEFONO_FIJO, CC.CTOV_TELEFONO_CELU, CC.CTOD_FECHA_ALTA
from PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE C WITH (NOLOCK)
LEFT JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE_CONTACTO CC  WITH (NOLOCK) ON C.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
WHERE C.CLIV_NRO_RUC IN (
SELECT RUC
FROM data_ultra_raw
UNION
SELECT CONCAT('000', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 5
UNION
SELECT CONCAT('0000', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 5
UNION
SELECT CONCAT('00', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 6
UNION
SELECT CONCAT('000', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 6
UNION
SELECT CONCAT('0', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 7
UNION
SELECT CONCAT('00', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 7
UNION
SELECT CONCAT('0', RUC)
FROM data_ultra_raw
WHERE LEN(RUC) = 8
);

select * INTO data_raw_ultra_bk from data_raw_ultra_bk2
select * from data_raw_ultra_bk2
select * from data_raw_ultra_bk2 where desc_correo = ''

UPDATE data_raw_ultra_bk2 SET desc_correo = 'correo.migracion.win.ultra@ultra.com' where desc_correo = ''

CREATE TABLE [dbo].[data_raw_ultra_bk2](
	[cod_pedido_ultra] [bigint] NOT NULL,
	[num_documento] [varchar](11) NOT NULL,
	[id_cliente_ultra] [bigint] NULL,
	[ubigeo] [char](8) NOT NULL,
	[doc_representante] [varchar](200) NULL,
	[fec_vence] [date] NULL,
	[ancho_banda] [int] NULL,
	[desc_tipo_documento] [varchar](3) NOT NULL,
	[desc_tipo_documento_repre] [varchar](3) NOT NULL,
	[desc_nombres_representante] [varchar](200) NOT NULL,
	[desc_oferta] [varchar](9) NOT NULL,
	[estado] [varchar](10) NOT NULL,
	[fec_baja] [date] NULL,
	[fec_suspension] [date] NULL,
	[desc_moneda] [varchar](5) NOT NULL,
	[desc_direccion] [varchar](150) NOT NULL,
	[desc_latitud] [decimal](9, 6) NOT NULL,
	[desc_longitud] [decimal](9, 6) NOT NULL,
	[desc_distrito] [varchar](200) NULL,
	[desc_provincia] [varchar](200) NULL,
	[desc_region] [varchar](200) NULL,
	[desc_razon_social] [varchar](152) NOT NULL,
	[desc_nombres] [varchar](50) NOT NULL,
	[desc_ape_paterno] [varchar](50) NOT NULL,
	[desc_ape_materno] [varchar](50) NOT NULL,
	[desc_correo] [varchar](200) NOT NULL,
	[desc_celular] [varchar](20) NOT NULL,
	[desc_telefono] [varchar](20) NULL,
	[id_contrato_ecom] [int] NOT NULL,
	[id_cliente_ecom] [int] NOT NULL,
	[id_servicio_ecom] [int] NOT NULL,
	[desc_ultimo_periodo] [varchar](6) NULL
)


select * from data_ultra_contacto WHERE CTOV_EMAIL LIKE '%Administracion@junglepictures.%'
select * from data_ultra_contacto WHERE CTOV_TELEFONO_CELU = ' 991924899 -'
select * from data_ultra_contacto WHERE CTOV_TELEFONO_CELU = '5215538807928'


select * from data_ultra_contacto WHERE CTOV_TELEFONO_FIJO <> LTRIM(RTRIM(CTOV_TELEFONO_FIJO))

update data_ultra_contacto set CTOV_TELEFONO_FIJO = LTRIM(RTRIM(CTOV_TELEFONO_FIJO)) where CTOV_TELEFONO_FIJO <> LTRIM(RTRIM(CTOV_TELEFONO_FIJO))


update data_ultra_contacto set CTOV_TELEFONO_FIJO = '2990214' WHERE CTOV_TELEFONO_FIJO = '2990214- 4444921'



select * from data_ultra_contacto where CTOV_TELEFONO_CELU <> LTRIM(RTRIM(CTOV_TELEFONO_CELU))

update data_ultra_contacto set CTOV_TELEFONO_CELU = LTRIM(RTRIM(CTOV_TELEFONO_CELU)) where CTOV_TELEFONO_CELU <> LTRIM(RTRIM(CTOV_TELEFONO_CELU))

update data_ultra_contacto set CTOV_TELEFONO_FIJO = NULL WHERE CTOV_TELEFONO_FIJO = '5215538807928'



update data_ultra_contacto set CTOV_TELEFONO_CELU = null, CTOV_TELEFONO_FIJO = '5215538807928' WHERE CTOV_TELEFONO_CELU = '52 15538807928'

update data_ultra_contacto set CTOV_TELEFONO_CELU = '983200077' WHERE CTOV_TELEFONO_CELU = '+51 983 200 077'


update data_ultra_contacto set CTOV_TELEFONO_CELU = '997527019' WHERE CTOV_TELEFONO_CELU = '997527019 -994074023'

update data_ultra_contacto set CTOV_TELEFONO_CELU = '991924899' WHERE CTOV_TELEFONO_CELU = ' 991924899 -'

update data_ultra_contacto set CTOV_TELEFONO_CELU = '937445678' WHERE CTOV_TELEFONO_CELU = '937445678 -937445678'
update data_ultra_contacto set CTOV_TELEFONO_CELU = '986198247' WHERE CTOV_TELEFONO_CELU = '986 198 247'
update data_ultra_contacto set CTOV_TELEFONO_CELU = '933543070', CTOV_TELEFONO_FIJO = '971449059' WHERE CTOV_TELEFONO_CELU = '933543070-971449059'

-- update data_ultra_contacto set CTOV_EMAIL = 'administracion@junglepictures.com' WHERE CTOV_EMAIL = 'Administracion@junglepictures.'

update data_ultra_contacto set CTOV_TELEFONO_CELU = NULL WHERE CTOV_TELEFONO_CELU = ''