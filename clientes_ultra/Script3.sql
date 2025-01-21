

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

select * from data_ultra_emision

exec xp_cmdshell 'dir \\10.24.100.13\DEV_Uploads\PROY-0002-2024FC-WIN-Facturacion-para-clientesULTRA-FASE-3\'
exec xp_cmdshell 'ping 10.1.4.81'

EXEC xp_cmdshell 'curl -o C:\Users\Public\Downloads\Fact_20250120.csv http://10.1.4.81:280/Emision2001ULTRA-8.csv';
EXEC xp_cmdshell 'dir C:\Users\Public\Downloads';

exec xp_cmdshell 'net use \\10.24.100.13\DEV_Uploads\PROY-0002-2024FC-WIN-Facturacion-para-clientesULTRA-FASE-3\ /user:fherrerab@win.pe Win.123$ /persistent:yes'

USE PE_OPTICAL_ADM;

SET LANGUAGE Spanish

drop table data_ultra_emision_enero20;

CREATE TABLE data_ultra_emision_enero20 (
    ID_PEDIDO VARCHAR(100),
    CLI_ID VARCHAR(100),
    COMPROBANTE VARCHAR(100),
    RAZON_SOCIAL VARCHAR(100),
    CIR_COD VARCHAR(100),
    NRO_RUC VARCHAR(100),
    LATITUD VARCHAR(100),
    LONGITUD VARCHAR(100),
    UBIGEO VARCHAR(100),
    DIRECCION varchar(max),
    DISTRITO VARCHAR(100),
    DEPARTAMENTO VARCHAR(100),
    PROVINCIA VARCHAR(100),
    T_SERVICIO VARCHAR(100),
    ANCHO_BANDA VARCHAR(100),
    RENTA_MENSUAL VARCHAR(100),
    BAJA_OPERATIVA VARCHAR(100),
    ESTADO VARCHAR(100),
    MONEDA VARCHAR(100),
    TECNOLOGIA VARCHAR(100)
);

BULK INSERT data_ultra_emision_enero20
        FROM 'C:\Users\Public\Downloads\Fact_20250120.csv'
            WITH
    (
                FIELDTERMINATOR = ';',
                ROWTERMINATOR = '\n',
				FIRSTROW = 2
    )
GO 

select * from data_ultra_emision_enero20


select * from data_ultra_emision_enero17

select * from data_ultra_procesado where nro_documento = '09536917'

delete from data_ultra_emision_enero17 where BAJA_OPERATIVA = '2025-01-04'
delete from data_ultra_emision_enero17 where CIR_COD = 38507


update data_ultra_emision_enero17 set BAJA_OPERATIVA = null where BAJA_OPERATIVA is not null

select * from data_ultra_emision_enero17 e

select * from data_ultra_raw
where flg_migrado = 0

update data_ultra_raw set AnchoBanda = '800 Mbps' where IdPedido = 5010273

-- actualizar a 600 del plna de 800
update data_ultra_raw set AnchoBanda = '600 Mbps', TipoServicio = 'Ultra 600' where IdPedido = 5010273;

update data_ultra_gpon_raw set ancho_banda = '600',desc_oferta = 'Ultra 600' where cod_pedido_ultra = 5010273;


select * from data_ultra_gpon_raw
where  cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273)

select * from data_ultra_procesado where cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273)

select c.CLIV_NRO_RUC, ec.CTOI_ENVIO_CORREO,ec.CTOV_EMAIL,c.CLID_FECHA_CREACION from ecom.ECOM_CLIENTE c
inner join ecom.ECOM_CLIENTE_CONTACTO ec on c.CLII_ID_CLIENTE = ec.CLII_ID_CLIENTE
where c.CLIV_NRO_RUC = '48946922'

-- actualiza correo
update data_ultra_procesado set desc_correo = 'hannyrodrigob@gmail.com' where nro_documento='20603565984';
update data_ultra_procesado set desc_correo = 'ychoy@breca.com' where nro_documento='20522093743';
update data_ultra_procesado set desc_correo = 'administracion@pominpro.pe' where nro_documento='20511110875';
update data_ultra_procesado set desc_correo = 'AAROM_3@HOTMAIL.COM' where nro_documento='71249550';
update data_ultra_procesado set desc_correo = 'CHRIS_20@OUTLOOK.COM.PE' where nro_documento='48946922';


SELECT id_data, nro_documento, desc_celular, desc_celular2, desc_correo
FROM data_ultra_procesado where flg_validate_celular = 0 order by id_data



update data_ultra_gpon_raw set desc_oferta = 'Ultra 800' where desc_oferta = 'ERROR' and num_documento = '20603565984'

update data_ultra_gpon_raw set doc_representante = '06583527' , desc_tipo_documento_repre='DNI',desc_nombres_representante='PEREZ ORIHUELA POLO JUVENAL'
where num_documento = '20511110875'

update data_ultra_gpon_raw set desc_ultimo_periodo = '202412' where cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273)

update r
set r.Latitud = g.desc_latitud, r.Longitud = g.desc_longitud
from data_ultra_raw r
inner join data_ultra_gpon_raw g on g.cod_pedido_ultra = r.IdPedido
where r.flg_migrado = 0

update r
set r.Provincia = g.desc_provincia, r.Departamento = g.desc_region
from data_ultra_raw r
inner join data_ultra_gpon_raw g on g.cod_pedido_ultra = r.IdPedido
where r.flg_migrado = 0


update r
set r.Distrito=g.desc_distrito
from data_ultra_raw r
inner join data_ultra_gpon_raw g on g.cod_pedido_ultra = r.IdPedido
where r.flg_migrado = 0 and r.IdPedido = 5002114

select * from data_ultra_raw r
inner join data_ultra_gpon_raw g on g.cod_pedido_ultra = r.IdPedido
where r.flg_migrado = 0

select * from data_ultra_gpon_raw

update data_ultra_raw set Moneda = 'Soles' where flg_migrado = 0

-- insertar en data_ulltra_raw
insert into data_ultra_raw
select '-' winforce,ISNULL(ID_PEDIDO,'-') idpedido,'-' item,CASE WHEN TECNOLOGIA = 'GPON' THEN '-' ELSE CLI_ID END clienteid,
'-' categoria,RAZON_SOCIAL,'-' rubro,CIR_COD,NRO_RUC,'', '-' tipo,LATITUD,LONGITUD,UBIGEO,DIRECCION,DISTRITO,DEPARTAMENTO,PROVINCIA,'-' nodo,
T_SERVICIO,null mediofisico,null origen,ANCHO_BANDA,null precioInstalacion,RENTA_MENSUAL,0 duracionInicial,GETDATE() fechaRegistro,null FechaInstalacion,null altaOperativa,'' EstadoAlta,
null VencimientoInicial,null VencimientoActual,null bajaOperativa,'-' Motivobaja,'Activo' Estado,null modeloRouter,0 precioRouter,'-' modalidad,
MONEDA,0 rentasoles,0 rentasinIGV,TECNOLOGIA,'Traspaso' accion,'-','-','-',0,''
from data_ultra_emision_enero20

select * from data_ultra_raw 
where flg_migrado = 0

select * from data_ultra_emision

select * from data_ultra_raw
where IdPedido in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273)

select * from data_ultra_raw where CircuitoCod in (select  distinct cir_cod from data_ultra_emision_enero17)
where ClienteID = 671345

select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where nro_documento = '10543759'

select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where cod_circuito in (45473,
45481,
45485,
45489,
53381,
65752,
202382,
215007
)

select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where cod_circuito in (88727)


select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where nro_documento = '20563050765'

select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where cod_pedido_ultra in (5000145)

select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where cod_pedido_ultra in (
-- 5000051,
-- 5000065,
-- 5000031,
5000247,
5006467,
5000072,
5000209,
5000019,
5000618)


select desc_activacion_habil,desc_observacion_activacion,* from data_ultra_procesado where cod_pedido_ultra in (
5000442,
5002606,
5000159,
5000134,
5000077,
5005157,
5002114
)


-- Plan Excel: Ultra 800 - Plan WE: Ultra 800 - Ancho Banda Excel: NULL - Ancho Banda WE: 0.00 - Ancho Banda WE Convertido: 0.00

update data_ultra_raw set flg_migrado = 00, desc_observacion = '' where CircuitoCod = 202382

update data_ultra_raw set AnchoBanda = '800 Mbps' where CircuitoCod = 238478

update data_ultra_raw set flg_migrado = 0, desc_observacion = '' where CircuitoCod = 238478

select * from data_ultra_raw

select status_resultado,status_ingreso_venta, * from data_ultra_procesado where id_data in (
765,764,763,762,761,760,759,758,757,756,755,754,753,752,751,750,749,748,747,746,745,744,743)

set NOCOUNT OFF;

select  status_resultado,status_ingreso_venta, * from data_ultra_procesado where nro_documento
in ('20612587907',
'20522093743',
'20544994779')

update data_ultra_procesado set status_resultado = '', status_ingreso_venta = 1 where nro_documento
in ('20612587907',
'20522093743',
'20544994779')

select e.compro_nro_doc
-- distinct p.cod_circuito,e.ID_PEDIDO, e.cli_nro_doc, p.razon_social, p.nombres,p.ape_paterno,p.ape_materno,p.cod_pedido_pf_ultra,e.compro_nro_doc
from data_ultra_procesado p
inner join data_ultra_emision e on p.cod_pedido_ultra = e.ID_PEDIDO and p.cod_circuito = e.cod_circuito
inner join data_ultra_proc_detalle d on e.cod_circuito = d.cod_circuito and e.ID_PEDIDO = d.cod_pedido_ultra
where estado_programacion = 'Programada'
and desc_situacion = 'COBR'

select * from data_ultra_emision 
where compro_nro_doc in (
'S002-00040291','S002-00040237','S002-00040139','S002-00040059','S002-00039837','S002-00039797','S002-00039729','S002-00039717','S002-00039696','S002-00039690','S002-00039656','S002-00039577','S002-00039569','S002-00040066','S002-00039572','S002-00040295','S002-00040292','S002-00040287','S002-00040274','S002-00040233','S002-00040227','S002-00040197','S002-00040196','S002-00040163','S002-00040149','S002-00040137','S002-00040106','S002-00040089','S002-00040079','S002-00040077','S002-00040044','S002-00039975','S002-00039960','S002-00039950','S002-00039945','S002-00039920','S002-00039912','S002-00039878','S002-00039874','S002-00039849','S002-00039845','S002-00039833','S002-00039831','S002-00039809','S002-00039790','S002-00039776','S002-00039765','S002-00039764','S002-00039738','S002-00039734','S002-00039728','S002-00039707','S002-00039701','S002-00039634','S002-00039619','S002-00039607','S002-00039591','S002-00039585','S005-00001565','S005-00001556','S005-00001554','S005-00001545','S005-00001542','S005-00001536','S005-00001529','S005-00001526','S005-00001515','S005-00001513','S005-00001510','S005-00001507','S005-00001498','S005-00001496','S005-00001482','S002-00040219','S002-00040134','S002-00040061','S002-00040000','S002-00039952','S002-00039904','S002-00039860','S002-00039739','S002-00039730','S002-00039709','S002-00039682','S005-00001564','S005-00001563','S005-00001558','S005-00001557','S005-00001541','S005-00001523','S005-00001518','S005-00001512','S005-00001509','S005-00001508','S005-00001491','S005-00001490','S005-00001486','S002-00040294','S002-00040284','S002-00040250','S002-00040228','S002-00040207','S002-00040201','S002-00040199','S002-00040189','S002-00040188','S002-00040186','S002-00040182','S002-00040177','S002-00040167','S002-00040166','S002-00040161','S002-00040141','S002-00040122','S002-00040046','S002-00040023','S002-00039999','S002-00039991','S002-00039954','S002-00039947','S002-00039937','S002-00039934','S002-00039929','S002-00039897','S002-00039891','S002-00039890','S002-00039872','S002-00039871','S002-00039869','S002-00039859','S002-00039828','S002-00039815','S002-00039813','S002-00039806','S002-00039771','S002-00039750','S002-00039740','S002-00039735','S002-00039733','S002-00039716','S002-00039705','S002-00039694','S002-00039689','S002-00039662','S002-00039658','S002-00039643','S002-00039637','S002-00039583','S002-00039575','S002-00039574','S002-00039573','S002-00039571'
) and compro_nro_doc not in (select e.compro_nro_doc
-- distinct p.cod_circuito,e.ID_PEDIDO, e.cli_nro_doc, p.razon_social, p.nombres,p.ape_paterno,p.ape_materno,p.cod_pedido_pf_ultra,e.compro_nro_doc
from data_ultra_procesado p
inner join data_ultra_emision e on p.cod_pedido_ultra = e.ID_PEDIDO and p.cod_circuito = e.cod_circuito
inner join data_ultra_proc_detalle d on e.cod_circuito = d.cod_circuito and e.ID_PEDIDO = d.cod_pedido_ultra
where estado_programacion = 'Programada'
and desc_situacion = 'COBR')

select * from data_ultra_emision where cod_circuito = 38507

select * from data_ultra_procesado where cod_circuito = 38507

select status_ingreso_venta,status_resultado,cod_pedido_pf_ultra,*  from data_ultra_procesado  where cod_pedido_pf_ultra = 0

select * from ecom.ECOM_CLIENTE where CLIV_NRO_RUC = '001807686'

UPDATE data_ultra_procesado SET flg_check_nom_v2 = 1 WHERE id_data = 244; 

UPDATE data_ultra_procesado SET flg_check_nom_v2 = 1 WHERE id_data = 386; 

UPDATE data_ultra_procesado SET nombres = 'MATIAS EXEQUIEL' WHERE id_data = '524'; 

UPDATE data_ultra_procesado SET nombres = 'GOMASTER SAC' WHERE id_data = '759';

UPDATE data_ultra_procesado SET flg_check_nom_v2 = 1 WHERE id_data = 759; 

SELECT top 23 d.cod_circuito, d.cod_pedido_ultra, d.cod_pedido_pf_ultra,
d.id_data, d.flg_nombre_validado, d.flg_check_nom_v2, CC.CLIV_NRO_RUC ecom_nro_documento, CC.CLIV_RAZON_SOCIAL ecom_razon_social,
d.nro_documento proce_nro_documento,
CASE WHEN d.nombres <> '' then d.nombres else d.razon_social end proce_nombres,
CASE WHEN d.ape_paterno <> '' then d.ape_paterno else '.' end proce_ape_paterno,
CASE WHEN d.ape_materno <> '' then d.ape_materno else '.' end proce_ape_materno,
e.cli_nro_doc emision_nro_documento, e.desc_cliente emision_razon_social, desc_observacion_activacion
FROM data_ultra_procesado d
INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
LEFT JOIN data_ultra_emision e ON d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
inner join PE_OPTICAL_ADM.ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
INNER JOIN PE_OPTICAL_ADM.ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
INNER JOIN PE_OPTICAL_ADM.ECOM.ECOM_CLIENTE CC ON EP.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
WHERE d.flg_nombre_validado = 0 
order by cod_pedido_pf_ultra desc


SELECT top 23 d.id_data, d.cod_circuito, d.nro_documento, d.flg_config_address, d.cod_pedido_ultra, d.desc_direccion,
r.Direccion, s.SERV_DIRECCION, r.Latitud, r.Longitud, desc_latitud, desc_longitud, desc_distrito, desc_provincia, desc_region, desc_ubigeo,
cod_pedido_pf_ultra
FROM data_ultra_procesado d
INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
INNER JOIN PE_OPTICAL_ADM.ECOM.ECOM_SERVICIO s ON s.SERI_ID_SERVICIO = d.ecom_id_servicio
WHERE flg_config_address = 0 and d.cod_pedido_pf_ultra <> 0
order by cod_pedido_pf_ultra desc

select * from data_ultra_procesado

select * from data_ultra_gpon_raw 

update data_ultra_raw set Direccion = 'JUNIN 434 MIRAFLORES DPTO SS01' where Direccion = 'JUN-N 434 MIRAFLORES DPTO SS01'

select * from data_ultra_raw where Direccion = 'JUN-N 434 MIRAFLORES DPTO SS01'

select * from PE_OPTICAL_ADM.ECOM.ECOM_SERVICIO
where SERV_DIRECCION like '%SAN FELIPE 601%'

update ECOM.ECOM_SERVICIO set SERV_DIRECCION = 'AV. SAN FELIPE 601 RESIDENCIAL SAN FELIPE' where SERI_ID_SERVICIO = 2116735

update data_ultra_procesado set desc_direccion=''  where id_data in (744,761,763,760,765,758,759,764,743,749,750,751,752,753,754,755,748,746,747,745,757,756,762)

alter table data_ultra_procesado add flg_config_address int not null default 0;

alter table data_ultra_procesado add desc_ubigeo varchar(20) not null default '';

-- los 23
select top 23 status_ingreso_venta,status_resultado,cod_pedido_pf_ultra as cod_pedido_pf_ultra2,*  from data_ultra_procesado  order by cod_pedido_pf_ultra desc

select * from data_ultra_raw where RUC = '20502194616'

select p.* from data_ultra_emision e
left join data_ultra_procesado p on e.cod_circuito = p.cod_circuito and e.ID_PEDIDO = p.cod_pedido_ultra
where e.id_data in (134,147,361,678,682,686,703,705,706,719,732,737,752,759,760,761)

select p.* from data_ultra_emision e
inner join data_ultra_procesado p on e.cod_circuito = p.cod_circuito and e.ID_PEDIDO = p.cod_pedido_ultra
where e.id_data in (134,147,361)

update data_ultra_procesado set desc_observacion_activacion = 'otros' where id_data = 49

update p
set p.desc_observacion_activacion = 'ok'
from data_ultra_procesado p
inner join data_ultra_emision e on e.cod_circuito = p.cod_circuito and e.ID_PEDIDO = p.cod_pedido_ultra
where e.id_data in (134,147,361)

select * into data_ultra_proc_detallebk1 from data_ultra_proc_detalle

truncate table data_ultra_proc_detalle

select * from data_ultra_proc_detalle

delete from data_ultra_proc_detalle where desc_concepto = 'Servicios Adicionales'

-- MPBLS
select * from data_ultra_emision 
where id_data in (134,147,361)

-- GPON
select * from data_ultra_emision 
where id_data in (678,682,686,703,705,706,719,732,737,752,759,760,761)


select desc_situacion	,cli_nro_doc,	compro_nro_doc,	desc_cliente	,desc_moneda, e.ID_PEDIDO
 from data_ultra_emision e
inner join data_ultra_raw p on e.ID_PEDIDO = p.IdPedido
where e.id_data in (
--134,147,361,
678,682,686,703,705,706,719,732,737,752,759,760,761)
and IdPedido is null

select * from data_ultra_procesado where cod_pedido_ultra in (
5001998,
5000114,
5000061,
5000046,
5000043,
5000037,
5000026)

update data_ultra_procesado set desc_observacion_activacion = 'OK' 
 where cod_pedido_ultra in (
5001998,
5000114,
5000061,
5000046,
5000043,
5000037,
5000026)

select * from data_ultra_raw where IdPedido = 5001998

select * from data_ultra_raw where IdPedido = 5000114

select RentaMensual,* from data_ultra_raw where IdPedido = 5000046


select RentaMensual,* from data_ultra_raw where IdPedido = 5000037

select * from data_ultra_emision where ID_PEDIDO = 5000061

select * from data_ultra_emision where ID_PEDIDO = 5000043

select * from data_ultra_emision where ID_PEDIDO = 5000026

update data_ultra_emision set SUB_TOTAL = 227.97, IGV = 41.03, TOTAL = 269.00,SUMA_RECURRENTE=269.00 where ID_PEDIDO = 5000061

update data_ultra_emision set SUB_TOTAL = 227.97, IGV = 41.03, TOTAL = 269.00,SUMA_RECURRENTE=269.00 where ID_PEDIDO = 5000043



update data_ultra_raw set RentaMensual = 114.41
where IdPedido = 5000026

update data_ultra_raw set RentaMensual = 114.41
where IdPedido = 5000037

update data_ultra_raw set RentaMensual = 114.41
where IdPedido = 5000046

update data_ultra_raw set RentaMensual = 127.12
where IdPedido = 5001998

update data_ultra_raw set RentaMensual = 114.41
where IdPedido = 5000114

select * from data_ultra_proc_detalle  d
inner join data_ultra_procesado  p on p.cod_pedido_ultra = d.cod_pedido_ultra and d.cod_circuito=p.cod_circuito


select * from data_ultra_emision where ID_PEDIDO = 5000026

update data_ultra_emision set SUMA_RECURRENTE = 135.00 where ID_PEDIDO = 5000026

select * from data_ultra_raw where RUC = '46580781'

select * from data_ultra_raw where RUC = '20522093743'
select * from data_ultra_raw where RUC = '20603565984'
select * from data_ultra_raw where RUC = '71249550'
select * from data_ultra_raw where RUC = '48946922'
select * from data_ultra_raw where RUC = '20511110875'

48946922
20522093743
20511110875

select * from data_ultra_proc_detalle

update data_ultra_proc_detalle set desc_concepto = 'Ultra 600' where desc_concepto = 'Ultra 600Mbps'

update data_ultra_proc_detalle set desc_concepto = 'Ultra 800' where cod_circuito = 222175;

update data_ultra_proc_detalle set desc_concepto = 'Ultra 1000' where cod_circuito = 38860;


select * from data_ultra_procesado where cod_circuito in (222175,38860)


CLII_ID_CLIENTE	CLIV_RAZON_SOCIAL	CLIV_NRO_RUC	CTOV_EMAIL	CTOV_TELEFONO_FIJO	CTOV_TELEFONO_CELU	CTOD_FECHA_ALTA
828801	FERNANDEZ LINO VDA DE BUSTAMANTE ROSA VICTORIA	06368524	ana.iza@alexim.com	NULL	NULL	2024-06-13 16:08:51.937

insert into data_ultra_contacto (CLII_ID_CLIENTE,CLIV_RAZON_SOCIAL,CLIV_NRO_RUC,CTOV_EMAIL,CTOV_TELEFONO_FIJO,CTOV_TELEFONO_CELU,CTOD_FECHA_ALTA )
values (42338,'BRECA BANCA S.A.C.','20544994779','ychoy@breca.com',)

select * from ecom.ECOM_CLIENTE where CLIV_NRO_RUC = '20544994779'

select * from ecom.ECOM_CLIENTE_CONTACTO where CLII_ID_CLIENTE = 42338


SET NOCOUNT ON

DECLARE @NRO_RUC VARCHAR(100) = '20544994779'
DECLARE @CORREO VARCHAR(100), @CELULAR1 VARCHAR(100), @CELULAR2 VARCHAR(100), @ID_CLIENTE_ECOM INT

SET @CORREO = (SELECT top 1 CTOV_EMAIL FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_EMAIL IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @CELULAR1 = (SELECT top 1 CTOV_TELEFONO_CELU FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_CELU IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @CELULAR2 = (SELECT top 1 CTOV_TELEFONO_FIJO FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC AND CTOV_TELEFONO_FIJO IS NOT NULL ORDER BY CTOD_FECHA_ALTA desc);
SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM data_ultra_contacto where CLIV_NRO_RUC = @NRO_RUC);

IF @ID_CLIENTE_ECOM IS NULL
BEGIN
    SET @ID_CLIENTE_ECOM = (SELECT TOP 1 CLII_ID_CLIENTE FROM ECOM.ECOM_CLIENTE where CLIV_NRO_RUC = @NRO_RUC);
END
        
SELECT @CORREO desc_correo, @CELULAR1 desc_celular, @CELULAR2 desc_telefono, @ID_CLIENTE_ECOM id_cliente_ecom


-- insertar en data_ulltra_raw
insert into data_ultra_raw
select '-' winforce,ISNULL(ID_PEDIDO,'-') idpedido,'-' item,CASE WHEN TECNOLOGIA = 'GPON' THEN '-' ELSE CLI_ID END clienteid,
'-' categoria,RAZON_SOCIAL,'-' rubro,CIR_COD,NRO_RUC,'', '-' tipo,LATITUD,LONGITUD,UBIGEO,DIRECCION,DISTRITO,DEPARTAMENTO,PROVINCIA,'-' nodo,
T_SERVICIO,null mediofisico,null origen,ANCHO_BANDA,null precioInstalacion,RENTA_MENSUAL,0 duracionInicial,GETDATE() fechaRegistro,null FechaInstalacion,null altaOperativa,'' EstadoAlta,
null VencimientoInicial,null VencimientoActual,null bajaOperativa,'-' Motivobaja,'Activo' Estado,null modeloRouter,0 precioRouter,'-' modalidad,
MONEDA,0 rentasoles,0 rentasinIGV,TECNOLOGIA,'Traspaso' accion,'-','-','-',0,''
from data_ultra_emision_enero17


select *  from data_ultra_raw
where flg_migrado = 0 and ruc = '20522093743'

select * from data_ultra_procesado where nro_documento = '20522093743'

update data_ultra_raw set AnchoBanda = '600 Mbps' where AnchoBanda = '600Mbps' and IdPedido in (5006467,5000019)

update data_ultra_raw set TipoServicio = 'Ultra 600' where IdPedido in (5006467,5000019)

select * from data_ultra_gpon_raw
where cod_pedido_ultra in (5006467,5000019)

update data_ultra_gpon_raw set desc_oferta = 'Ultra 600' where cod_pedido_ultra in (5000019,5006467)

select * from data_ultra_gpon_raw where cod_pedido_ultra = 5002114

select * from data_ultra_procesado where cod_pedido_ultra = 5002114

select * from data_ultra_procesado
where cod_pedido_pf_ultra = 0

update d
set d.Latitud = g.desc_latitud, Longitud = g.desc_longitud, Distrito=g.desc_distrito,d.Ubigeo=g.ubigeo,
d.Provincia=g.desc_provincia,d.Departamento=g.desc_region
from data_ultra_raw d
inner join data_ultra_gpon_raw g on d.IdPedido = g.cod_pedido_ultra
where d.flg_migrado = 0

update data_ultra_raw set Latitud = '-12.1098878',Longitud = '-76.9894854',Distrito='SAN BORJA',Departamento='LIMA',Provincia='LIMA'
where flg_migrado = 0 and RUC = '20522093743'


select * from data_ultra_raw d
inner join data_ultra_gpon_raw g on d.IdPedido = g.cod_pedido_ultra
where d.flg_migrado = 0

SELECT TOP 1 * FROM data_ultra_raw WHERE flg_migrado = 0 and CircuitoCod is not null order by CircuitoCod asc

select * from data_ultra_raw where ClienteID = 40994 and CircuitoCod = 80485

select * from data_ultra_procesado

select e.RAZON_SOCIAL,c.CLIV_RAZON_SOCIAL, e.NRO_RUC,c.CLIV_NRO_RUC from data_ultra_emision_enero e
left join ecom.ECOM_CLIENTE c on e.NRO_RUC = c.CLIV_NRO_RUC

select * from ecom.ECOM_UBIGEO

select * from ecom.ECOM_CLIENTE where CLIV_NRO_RUC = '20522093743'

select * from data_ultra_raw


update e
set e.Distrito = u.UBIV_DESCRIPCION,e.Provincia = u2.UBIV_DESCRIPCION,e.Departamento=u3.UBIV_DESCRIPCION
from data_ultra_raw e
inner join ecom.ECOM_UBIGEO u on e.Ubigeo = u.UBII_ID_UBIGEO
inner join ecom.ECOM_UBIGEO u2 on u.UBIV_ID_UBIGEO_PADRE = u2.UBII_ID_UBIGEO
inner join ecom.ECOM_UBIGEO u3 on u2.UBIV_ID_UBIGEO_PADRE = u3.UBII_ID_UBIGEO

select * from data_ultra_procesado

select * from data_ultra_emision where cod_circuito = 0 and ID_PEDIDO = 0 and es_sva = 0

update data_ultra_emision set desc_observacion = '', flg_status_habil = 1 where cod_circuito = 0 and ID_PEDIDO = 0 and es_sva = 0

select * from data_ultra_raw where flg_migrado = 0

select * from data_ultra_raw_gpon

update e
set e.RAZON_SOCIAL = c.CLIV_RAZON_SOCIAL
from data_ultra_emision_enero e
left join ecom.ECOM_CLIENTE c on e.NRO_RUC = c.CLIV_NRO_RUC

select * from data_ultra_emision_enero

select * from ecom.ECOM_CLIENTE where CLII_ID_CLIENTE = 667468

update data_ultra_emision_enero set ANCHO_BANDA = '800 Mbps' where ANCHO_BANDA = 'NULL'


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


UPDATE data_ultra_emision SET flg_status_habil = 0 WHERE compro_nro_doc = 'S002-00039756' and SUB_TOTAL = 8.48;


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
