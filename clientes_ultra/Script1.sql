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
ADD desc_observacion varchar(5000) NOT NULL default ''

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
) */
RETURN;
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


SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado order by nro_documento 
SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado where cod_pedido_pf_ultra <> 0 order by nro_documento 


SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado where nro_documento = '00000574646'

UPDATE data_ultra_procesado SET status_ingreso_venta = 1, status_resultado = '' where nro_documento = '000523863' and id_data = 348;


-- LAST
select * from data_ultra_procesado_last
SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_last order by nro_documento 
SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_last WHERE nro_documento = '000129962' order by nro_documento 

select * into data_ultra_proc_detalle_last from data_ultra_proc_detalle



-- truncate table data_ultra_proc_detalle_last
select * from data_ultra_proc_detalle_last

SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_last WHERE status_ingreso_venta = 10 AND status_resultado = 'ok'



-- UPDATE data_ultra_procesado_last SET status_ingreso_venta = 1, status_resultado = '' where id_data IN ( '105', '31', '275', '189', '398', '124', '203', '489', '659', '101', '144', '168', '518', '638', '546', '44', '349', '115', '176', '273', '58', '87', '202', '246', '245', '190', '123');
-- LAST



-- UAT

select IdPedido, CircuitoCod, RUC, RazonSocial, TipoServicio, Tecnologia, desc_observacion
from data_ultra_raw
-- where  desc_observacion in ('El último periodo de emision con estado COBRADO es 202403')
where IdPedido in (-1
) or CircuitoCod in (87938,
229280)

select IdPedido, CircuitoCod, RUC, RazonSocial, TipoServicio, Tecnologia, desc_observacion
from data_ultra_raw
where RUC in ('70089575', 
'42435648', 
'17842150')

select CAST(cli_nro_doc	AS BIGINT)
from data_ultra_emision_202412

/*
17842150
42435648
70089575
*/

select RUC, count(*)
from data_ultra_raw
where RUC in ('75056788') -- 002027937
group by RUC
having count(*) > 1

SELECT *
FROM data_ultra_emision_202412
where cli_nro_doc in ('75056788'
)

SELECT desc_situacion, cod_circuito
FROM data_ultra_emision_202412
where cli_nro_doc = '10058402' and flg_status_habil = 1;

select desc_observacion, * 
from data_ultra_raw
where  desc_observacion in ('Pedido Excel: 5000084 - Pedido WE: 5000084 - Nro Documento Excel: 20603146663 - Nro Documento WE: 10702482572')
ORDER BY 1

SELECT * FROM data_ultra_raw where RUC = '20516778203'
SELECT * FROM data_ultra_raw where CircuitoCod in (48064, 59826, 77049, 211310)
select * from data_ultra_emision_202412 where cli_nro_doc in ('20516778203')
select * from data_ultra_emision_202412 where cod_circuito in (48064, 59826, 77049, 211310)
select * from data_ultra_emision_202412 where ID_PEDIDO in (5000084)

select * from data_ultra_procesado where cod_pedido_ultra = '5000110'

select desc_observacion, COUNT(*) cantidad
from data_ultra_raw where flg_migrado = 1 and desc_observacion <> 'OK'
GROUP BY desc_observacion

select * from data_ultra_emision where cod_circuito = 37906

select distinct desc_activacion_habil, desc_observacion_activacion from data_ultra_procesado

select * from data_ultra_procesado where desc_activacion_habil = ''


select cod_pedido_pf_ultra, * from data_ultra_procesado order by 1

-- #temp_comprobantes
SELECT * FROM #temp_comprobantes

select desc_situacion [SITUACION], 
e.cli_nro_doc [NRO DOCUMENTO],
e.compro_nro_doc [COMPROBANTE],
CASE WHEN e.cod_circuito = 0 THEN '' ELSE CAST(e.cod_circuito AS VARCHAR(20)) END [COD CIRCUITO],
CASE WHEN e.ID_PEDIDO = 0 THEN '' ELSE CAST(e.ID_PEDIDO AS VARCHAR(20)) END [ID PEDIDO],
e.desc_cliente [CLIENTE],
CASE
	WHEN desc_situacion <> 'COBR' THEN 'El comprobante del 12/2024 no esta cobrado'
	WHEN e.cod_circuito <> 0 AND e.cod_circuito NOT IN (select CircuitoCod from data_ultra_raw) THEN 'Sin información del circuito/pedido'
	WHEN e.ID_PEDIDO <> 0 AND e.ID_PEDIDO NOT IN (select IdPedido from data_ultra_raw where IdPedido <> '-') THEN 'Sin información del circuito/pedido'
	WHEN e.cod_circuito = 0 AND e.ID_PEDIDO = 0 THEN 'Sin información del circuito/pedido'
	ELSE 'Sin información del estado actual del circuito'
END [OBSERVACION] --,
-- p.desc_activacion_habil, p.desc_observacion_activacion ,
-- e.cod_circuito , e .ID_PEDIDO, p.cod_pedido_pf_ultra , c.*, e.*
from data_ultra_emision e
left join data_ultra_procesado p on e.cod_circuito = p.cod_circuito and e.ID_PEDIDO = p.cod_pedido_ultra
LEFT JOIN #temp_comprobantes c on c.COMC_PEDI_COD_PEDIDO = p.cod_pedido_pf_ultra
where e.es_sva = 0  and c.COMC_COD_COMPROBANTE IS NULL and (p.cod_pedido_pf_ultra not in (select * from #temp_carga_exclusiones) or p.cod_pedido_pf_ultra is null)






SELECT cod_pedido_pf_ultra, COUNT(*) FROM data_ultra_procesado GROUP BY cod_pedido_pf_ultra ORDER BY 2 

select * from data_ultra_procesado

UPDATE d
SET d.cod_pedido_pf_ultra = P.PEDI_COD_PEDIDO
FROM data_ultra_procesado d
inner join #temp_pedido p ON d.nro_documento = p.PEDV_NUM_DOCUMENTO
where d.cod_pedido_pf_ultra = 0


select *
from data_ultra_procesado d
inner join #temp_pedido p ON d.nro_documento = p.PEDV_NUM_DOCUMENTO
where d.cod_pedido_pf_ultra = 0



select *
into #temp_comprobantes
from OPENQUERY(ULTRACRM, 'SELECT * FROM db_wincrm_ultra_010725.CRM_COMPROBANTE_FACT')


update p
set p.desc_activacion_habil = (CASE WHEN e.desc_situacion = 'COBR' THEN 'HABILITADO' ELSE 'NO HABILITADO' END),
 p.desc_observacion_activacion = (CASE WHEN e.desc_situacion = 'COBR' THEN 'ok' ELSE 'El comprobante del 12/2024 no esta cobrado' END)
FROM data_ultra_procesado p
inner join data_ultra_emision e ON e.ID_PEDIDO = p.cod_pedido_ultra
WHERE p.cod_pedido_ultra <> 0 AND e.ID_PEDIDO <> 0

SELECT * 
FROM data_ultra_procesado p
inner join data_ultra_emision e ON e.ID_PEDIDO = p.cod_pedido_ultra
WHERE p.cod_pedido_ultra <> 0 AND e.ID_PEDIDO <> 0

select * from  #temp_carga_exclusiones

insert into #temp_carga_exclusiones
select *
-- into #temp_carga_exclusiones
from OPENQUERY(ULTRACRM, 'SELECT distinct id_pedido FROM db_wincrm_ultra_010725.t_carga_exclusiones')



select desc_observacion, COUNT(*) 
from data_ultra_raw where flg_migrado = 1 and desc_observacion <> 'OK'
AND desc_observacion not in ('El estado del cliente es Baja',
'El estado del cliente es Suspendido por Falta Pago',
'El estado del cliente es Suspendido Solicitud Cliente',
'El último periodo de emision con estado COBRADO es 202403',
'El último periodo de emision con estado COBRADO es 202412',
'Estado Excel: Activo - Estado WE: Baja',
'No se encontró alguna emision en ECOM'
)
GROUP BY desc_observacion

select desc_observacion, COUNT(*) 
from data_ultra_raw where flg_migrado = 1 and desc_observacion <> 'OK'
GROUP BY desc_observacion

SELECT * FROM data_ultra_procesado

alter table data_ultra_procesado add flg_config_address tinyint not null default 0
alter table data_ultra_procesado add desc_ubigeo VARCHAR(20) not null default ''
select * from data_ultra_procesado

select num_documento, count(*)
from data_raw_ultra_bk2
GROUP BY num_documento
HAVING COUNT(*) > 1
order by 2 desc

SELECT *
FROM data_ultra_emision_202412
WHERE ID_PEDIDO <> 0

select b.cod_pedido_ultra, e.ID_PEDIDO, b.desc_ultimo_periodo, '202412' last_periodo
from data_raw_ultra_bk2 b
INNER join data_ultra_emision_202412 e ON b.cod_pedido_ultra = e.ID_PEDIDO

UPDATE b
SET 
    b.desc_ultimo_periodo = '202412'
FROM data_raw_ultra_bk2 b
INNER JOIN data_ultra_emision_202412 e
    ON b.cod_pedido_ultra = e.ID_PEDIDO


select ID_PEDIDO, COUNT(*)
from data_ultra_emision_202412
group by ID_PEDIDO
HAVING COUNT(*) > 1
ORDER BY 2 DESC


select *
from data_ultra_emision_202412

select * from data_ultra_raw where desc_observacion in (
'Plan Excel: ULTRA 600 - Plan WE: ULTRA 600 - Ancho Banda Excel: 600 Mbps - Ancho Banda WE: 600 - Ancho Banda WE Convertido: 600 Mbps',
'Plan Excel: Ultra Wifi Total - Plan WE: Ultra Wifi Total - Ancho Banda Excel: 1 Mbps - Ancho Banda WE: 1000.00 - Ancho Banda WE Convertido: 1 Gbps'
)

select * from data_ultra_raw where flg_migrado = 1 and desc_observacion LIKE 'El último periodo de emision con estado COBRADO es 202403%'

UPDATE data_ultra_raw SET flg_migrado = 0 WHERE CircuitoCod in (117881)

select * from data_ultra_raw where flg_migrado = 0
select desc_observacion, * from data_ultra_raw where CircuitoCod in (39314, 117881)
-- update data_ultra_raw set flg_migrado = 0 where IdPedido = 5000084

select * from PE_OPTICAL_ADM_PROD_20241224_060004.ECOM.ECOM_CLIENTE WHERE CLIV_NRO_RUC = '10702482572'

alter table data_ultra_procesado_uat add flg_validate_plan TINYINT NOT NULL DEFAULT 0
alter table data_ultra_procesado_uat add flg_validate_celular TINYINT NOT NULL DEFAULT 0


select status_ingreso_venta, status_resultado, razon_social_intranet, nombres, ape_paterno, ape_materno,
* from data_ultra_procesado_uat order by nro_documento;

select * from data_ultra_procesado_uat

select status_ingreso_venta, status_resultado, razon_social_intranet, nombres, ape_paterno, ape_materno,
* from data_ultra_procesado_uat where status_ingreso_venta NOT IN (1, 10) order by nro_documento;

select * from data_ultra_procesado_uat where status_ingreso_venta <> 10 order by nro_documento

select * from data_ultra_procesado_uat 

-- update data_ultra_procesado_uat set representante_nro_doc = '76850739', representante_ape_materno = 'CORZO',
-- representante_nombres = 'MANUEL', status_ingreso_venta = 5 where nro_documento = '20565536657'


select id_data, status_ingreso_venta, status_resultado, desc_latitud, desc_longitud, -- nombres, ape_paterno, ape_materno, 
cod_circuito, cod_pedido_ultra,
nro_documento, * from data_ultra_procesado_uat 
where status_resultado like 'Cliente no cumple Requisitos Comerciales%'
and status_ingreso_venta not in (10, 1) -- and tipo_documento IN ('DNI', 'CE')


select razon_social_intranet, ape_paterno, ape_materno, nombres, desc_latitud, desc_longitud, status_ingreso_venta,
status_resultado, cod_circuito, cod_pedido_ultra, * from data_ultra_procesado_uat 
where nro_documento = '20601510198' -- and cod_circuito = 226535


update data_ultra_procesado_uat SET desc_latitud = '-12.103863354603952', desc_longitud ='-77.04815781782085' WHERE cod_pedido_ultra ='5000034' AND cod_circuito = '0';

update data_ultra_procesado_uat set status_ingreso_venta = 1 where nro_documento = '20601510198'



select id_data, status_ingreso_venta, status_resultado, ape_paterno, ape_materno, nombres,  flg_check_nombres,
nro_documento, tipo_documento, * from data_ultra_procesado_uat 
where status_resultado like 'Cliente no cumple Requisitos Comerciales%'
and status_ingreso_venta not in (10, 1) and tipo_documento IN ('DNI', 'CE')

select id_data, status_ingreso_venta, status_resultado, razon_social_intranet, ape_paterno, ape_materno, nombres, tipo_vivienda, 
cod_circuito, cod_pedido_ultra,
* from data_ultra_procesado_uat 
where status_resultado = 'Error en los nombres'
and status_ingreso_venta not in (10, 1) and nro_documento = 'UN B49521'

select razon_social_intranet, ape_paterno, ape_materno, nombres, status_ingreso_venta,
status_resultado, cod_circuito, cod_pedido_ultra, * from data_ultra_procesado_uat 
where nro_documento = '0A9155857' and cod_circuito = 60937



update data_ultra_procesado_uat SET desc_latitud = '-12.122910509312934', desc_longitud ='-76.97200439708801' WHERE cod_pedido_ultra ='0' AND cod_circuito = '60937';


update data_ultra_procesado_uat set status_ingreso_venta = 1 where nro_documento = '20605383646' and cod_circuito = 60937;

update data_ultra_procesado_uat set flg_check_nombres = 1, status_ingreso_venta = 1
-- , nombres = 'MARK JAMES', ape_paterno = 'PEARSON'
-- , ape_materno = ' '
-- , nro_documento = '020211001', tipo_documento = 'CE'
where nro_documento = '0A9155857';

-- Cliente no cumple Requisitos Comerciales  Motivo	-12.1186420000	-76.9712110000	MEAD	ARNOVITZ	 	Multifamiliar	47659418
-- Cliente no cumple Requisitos Comerciales  Motivo	-12.1143780000	-76.9789070000	CECILIA	LARIOS	FLORES	Multifamiliar	40312254
-- Cliente no cumple Requisitos Comerciales  Motivo	-12.1030310000	-77.0473820000	KAREN DAYANA	MONTOYA	SUAREZ	Hogar	10760728

select * from PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE where CLIV_NRO_RUC = 'AG774859'


select id_data, status_ingreso_venta, status_resultado, desc_latitud, desc_longitud, nombres, ape_paterno, ape_materno, tipo_vivienda, 
cod_circuito, cod_pedido_ultra,
* from data_ultra_procesado_uat 
where status_resultado = 'Error en los nombres'
and status_ingreso_venta not in (10, 1) and nro_documento = '08221986'



update data_ultra_procesado_uat SET desc_latitud = '-12.105284174462914', desc_longitud = '-77.05308847909266' WHERE cod_pedido_ultra = '0' AND cod_circuito= '38448';


select razon_social_intranet, nombres, ape_paterno, ape_materno, * from data_ultra_procesado_uat where nro_documento = '20518934091'

update data_ultra_procesado_uat set status_ingreso_venta = 1 where nro_documento = '08221986'

-- update data_ultra_procesado_uat set flg_check_nombres = 1, status_ingreso_venta = 1 where nro_documento = '000534172'


SELECT * FROM PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE WHERE CLIV_NRO_RUC = '007490766'
SELECT TOP 1000 * FROM [10.1.4.22].PE_WINET_CRM.CRM.CRM_CLIENTE WHERE CLIV_NUMERO_DOCUMENTO = '007490766'
SELECT TOP 1000 * FROM PE_OPTICAL_CRM_PORTAL.CRM.CRM_CLIENTE WHERE CLIV_NUMERO_DOCUMENTO = '007490766'

update data_ultra_procesado_uat set flg_check_nombres = 1, status_ingreso_venta = 1,
nombres = 'RENAUD PATRICE NICOLAS', ape_paterno = 'PELAT', ape_materno = ' '
where nro_documento = '007490766';

-- UPDATE data_ultra_procesado_uat SET nro_documento = '001655107', status_ingreso_venta = 1, tipo_documento = 'CE' where nro_documento = '01655107'



select * from data_ultra_procesado_uat where status_resultado = 'Error en los nombres' and status_ingreso_venta = 0
update data_ultra_procesado_uat set status_ingreso_venta = 1 where status_resultado = 'Error en los nombres' and status_ingreso_venta = 0


select flg_validate_celular, * from data_ultra_procesado_uat  where flg_validate_celular = 0 order by id_data

select flg_validate_celular, desc_correo, desc_celular, desc_celular2, * from data_ultra_procesado_uat order by id_data

select flg_validate_celular, desc_correo, desc_celular, desc_celular2, * from data_ultra_procesado_uat where desc_celular = '984114723'

update data_ultra_procesado_uat set flg_validate_celular = 0

update data_ultra_procesado_uat set desc_celular2 = LTRIM(RTRIM(desc_celular2))

update data_ultra_procesado_uat set desc_celular = '983549523' where id_data = 733;

update data_ultra_procesado_uat set flg_validate_plan = 0
select * from data_ultra_procesado_uat where cod_circuito = 57194

select * from data_ultra_raw where CircuitoCod  = 57194

SELECT status_ingreso_venta, status_resultado, desc_producto, * FROM data_ultra_procesado_uat order by nro_documento 


select * from data_ultra_procesado_uat order by 1 desc
select distinct desc_oferta from data_ultra_procesado_uat

UPDATE data_ultra_procesado_uat SET status_ingreso_venta = 1, status_resultado = '' where nro_documento = '000523863' and id_data = 348;
UPDATE data_ultra_procesado_uat SET status_ingreso_venta = 1, status_resultado = '' where status_resultado = 'No puedes continuar con el registro de venta debido a que las CTOs de la Zona se encuentran saturadas o no existen CTOs disponibles' and id_data = 348;

ALTER TABLE data_ultra_procesado_uat ADD flg_check_nombres TINYINT NOT NULL DEFAULT 0;
ALTER TABLE data_ultra_procesado_uat ADD updated_at DATETIME NOT NULL DEFAULT GETDATE();

update data_ultra_procesado_uat set nombres = LTRIM(RTRIM(nombres))
update data_ultra_procesado_uat set ape_paterno = LTRIM(RTRIM(ape_paterno))
update data_ultra_procesado_uat set ape_materno = LTRIM(RTRIM(ape_materno))

-- UAT

DROP TRIGGER  dbo.trg_PreventUpdateExceptFields;

-- 00001262357
-- 00000733531
--   000574646
SELECT * FROM data_ultra_raw where RUC like '%1262357%'

SELECT * FROM ECOM.ECOM_CLIENTE WHERE CLIV_NRO_RUC like '%1262357%'

SELECT status_ingreso_venta, status_resultado, * FROM data_ultra_procesado where razon_social = '' AND tipo_documento = 'RUC'

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