
return;

select * from data_ultra_procesado_uat order by id_data
SELECT * FROM data_ultra_procesado_prod order by id_data
SELECT * FROM data_ultra_procesado_prod WHERE nro_documento = '20602764380'
SELECT * FROM data_ultra_procesado_prod WHERE status_ingreso_venta in (1) order by id_data
SELECT top 1 * FROM data_ultra_procesado_prod WHERE status_ingreso_venta in (1) order by nro_documento


SELECT id_data, nro_documento, status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_prod p order by p.nro_documento

SELECT * FROM data_ultra_procesado_prod where nro_documento = '000025316'
SELECT * FROM data_ultra_procesado_prod where status_ingreso_venta = 10 and status_resultado = 'ok'
-- where status_ingreso_venta = 10 and status_resultado = 'ok'

SELECT id_data, nro_documento, status_ingreso_venta, status_resultado,
* FROM data_ultra_procesado_prod where status_ingreso_venta <> 1

UPDATE data_ultra_procesado_prod SET status_ingreso_venta = 1, status_resultado = '' where nro_documento = '' and id_data = 0;


select nro_documento, count(*) from data_ultra_procesado_uat group by nro_documento order by 2 desc





select distinct nro_documento from data_ultra_procesado_uat

select * from ECOM.ECOM_CLIENTE WHERE CLIV_NRO_RUC = '10702482572'
-- 10702482572
select CLIV_NRO_RUC, count(*)
from [PE_OPTICAL_ADM_PROD_20241224_060004].ECOM.ECOM_CLIENTE
WHERE (CLIV_NRO_RUC IN (
	select distinct nro_documento from data_ultra_procesado_uat
) OR
CLIV_NRO_RUC IN ('00353411',
'AG774859',
'20190065',
'01655107', 
'A9155857',
'00001262357',
'945609',
'AUN B49521',
'00000733531',
'00000574646',
'20211001')) and CLIV_CODIGO_CLIENTE <> '788804'
GROUP BY CLIV_NRO_RUC
order by 2 desc



select CONCAT('UPDATE CRM_CLIENTE SET cod_pago = ''', RIGHT(REPLICATE('0', 7) + CLIV_CODIGO_CLIENTE, 7),
''' WHERE CLIV_NUMERO_DOCUMENTO = ''', CLIV_NRO_RUC, ''';')
from [PE_OPTICAL_ADM_PROD_20241224_060004].ECOM.ECOM_CLIENTE
WHERE (CLIV_NRO_RUC IN (
	select distinct nro_documento from data_ultra_procesado_uat where nro_documento not in (
	'000353411', '0AG774859', '020190065', '001655107', '020211001', '000945609', '0A9155857',
	'UN B49521', '001262357', '000733531', '000574646')
)
OR
CLIV_NRO_RUC IN ('00353411',
'AG774859',
'20190065',
'01655107', 
'A9155857',
'00001262357',
'945609',
'AUN B49521',
'00000733531',
'00000574646',
'20211001'))  and CLIV_CODIGO_CLIENTE <> '788804'


select nro_documento, CLI.CLIV_NRO_RUC
from data_ultra_procesado_uat d
inner join [PE_OPTICAL_ADM_PROD_20241224_060004].ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
INNER JOIN [PE_OPTICAL_ADM_PROD_20241224_060004].ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
INNER JOIN [PE_OPTICAL_ADM_PROD_20241224_060004].ECOM.ECOM_CLIENTE CLI ON EP.CLII_ID_CLIENTE = CLI.CLII_ID_CLIENTE
WHERE CLI.CLIV_NRO_RUC <> nro_documento


select IdPedido, CircuitoCod, RUC, RazonSocial, TipoServicio, Tecnologia, desc_observacion
from data_ultra_raw
where RUC in ('945609', 'A9155857', 'AG774859', 'AUN B49521', '353411', '20190065', '1655107',
'20211001', '1262357', '733531', '574646') or
IdPedido in (-1) or 
CircuitoCod in (87938, 229280)



update data_ultra_procesado_uat SET nro_documento = '945609' WHERE nro_documento = '000945609';
update data_ultra_procesado_uat SET nro_documento = 'A9155857' WHERE nro_documento = '0A9155857';
update data_ultra_procesado_uat SET nro_documento = 'AG774859' WHERE nro_documento = '0AG774859';
update data_ultra_procesado_uat SET nro_documento = 'AUN B49521' WHERE nro_documento = 'UN B49521';


/* '000353411', '020190065', '001655107', '020211001', '001262357', '000733531', '000574646'
nro_documento	CLIV_NRO_RUC
000353411	00353411
020190065	20190065
001655107	01655107
020211001	20211001
001262357	00001262357
000733531	00000733531
000574646	00000574646
*/


select * into data_ultra_procesado_uat_bk11 from data_ultra_procesado_uat order by id_data

select * from data_ultra_procesado_uat order by id_data
select * from data_ultra_raw

select IdPedido, CircuitoCod, nro_documento, RazonSocial, TipoServicio, Tecnologia, desc_observacion_activacion
from data_ultra_procesado_uat d
inner join data_ultra_raw e on d.cod_circuito = (case when e.CircuitoCod is null then 0 else e.CircuitoCod end) 
and d.cod_pedido_ultra = (case when e.IdPedido = '-' then 0 else e.IdPedido end)
WHERE desc_activacion_habil <> 'HABILITADO' order by id_data

select * from data_ultra_procesado_uat where desc_activacion_habil <> 'HABILITADO'

select * from data_ultra_procesado_uat order by nro_documento
select * from data_ultra_procesado_uat WHERE cod_pedido_pf_ultra = 0 order by id_data

SELECT d.nro_documento, d.cod_circuito, d.cod_pedido_ultra, desc_activacion_habil, e.cod_circuito , e.ID_PEDIDO
FROM data_ultra_procesado_uat d
left join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
where e.cod_circuito is null order by d.nro_documento

select id_data, cod_circuito, * from data_ultra_procesado_uat where nro_documento = '43528243'
select id_data, TOTAL, cod_circuito, ID_PEDIDO from data_ultra_emision_202412 where cli_nro_doc = '43528243'
-- HABILITADO
-- NO HABILITADO
-- Cir Exonerado 117010

SELECT d.nro_documento, d.cod_circuito, d.cod_pedido_ultra, desc_activacion_habil, desc_observacion_activacion, e.cod_circuito , e.ID_PEDIDO
FROM data_ultra_procesado_uat d
left join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
where e.cod_circuito is null and d.cod_circuito not in (117010)

SELECT d.nro_documento, d.cod_circuito, d.cod_pedido_ultra, desc_activacion_habil, desc_observacion_activacion, e.cod_circuito , e.ID_PEDIDO, E.desc_situacion
FROM data_ultra_procesado_uat d
INNER join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
where e.desc_situacion = 'FACT' -- and d.cod_circuito not in (117010)

SELECT d.nro_documento, d.cod_circuito, d.cod_pedido_ultra, desc_activacion_habil, desc_observacion_activacion, e.cod_circuito , e.ID_PEDIDO, E.desc_situacion
FROM data_ultra_procesado_uat d
INNER join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
where e.desc_situacion = 'COBR'

UPDATE data_ultra_procesado_uat SET desc_activacion_habil = 'NO HABILITADO', 
desc_observacion_activacion = 'El comprobante del 12/2024 no esta cobrado' where cod_circuito in (
	SELECT d.cod_circuito
	FROM data_ultra_procesado_uat d
	INNER join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
	where e.desc_situacion = 'FACT' and d.cod_circuito <> 0
)

UPDATE data_ultra_procesado_uat SET desc_activacion_habil = 'NO HABILITADO', 
desc_observacion_activacion = 'El comprobante del 12/2024 no esta cobrado' where cod_pedido_ultra in (
	SELECT d.cod_pedido_ultra
	FROM data_ultra_procesado_uat d
	INNER join data_ultra_emision_202412 e on d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
	where e.desc_situacion = 'FACT' and d.cod_pedido_ultra <> 0
)

UPDATE data_ultra_procesado_uat SET desc_activacion_habil = 'NO HABILITADO', 
desc_observacion_activacion = 'No tiene comprobante en el 12/2024' where cod_circuito in ('43420','48902','63733','80524','184438','185456')

select * from data_ultra_procesado_uat where cod_circuito = 117010



select cod_circuito, count(*)
from data_ultra_emision_202412
group by cod_circuito
order by 2 desc

select ID_PEDIDO, count(*)
from data_ultra_emision_202412
group by ID_PEDIDO
order by 2 desc

SELECT * 
INTO #data_uat_temp
FROM OPENQUERY(ULTRACRM, 'SELECT P.PEDI_COD_PEDIDO, T.TIPV_NOMBRE_CORTO, C.CLIV_NUMERO_DOCUMENTO,
C.CLIV_NOMBRES, C.CLIV_APELLIDO_PATERNO, C.CLIV_APELLIDO_MATERNO,
P.PEDV_CORREO, P.PEDV_CELULAR, P.PEDV_TELEFONO,
P.TIPO_DOC_REPRE, P.NUMERO_DOC_REPRE, P.NOMBRES_REPRE
FROM CRM_PEDIDO P
INNER JOIN CRM_CLIENTE C ON P.PEDI_COD_CLIENTE = C.CLII_COD_CLIENTE
INNER JOIN CRM_DIRECCION D ON D.DIRI_COD_DIRECCION = P.PEDI_COD_DIRECCION
LEFT JOIN CRM_TIPO T ON T.TIPC_CODIGO = P.PEDC_COD_TIPO_DOC AND T.TIPC_CONCEPTO = 14;');


select e.cod_circuito, e.cod_pedido_ultra, d.*
from #data_uat_temp d
inner join data_ultra_procesado_uat e on d.PEDI_COD_PEDIDO = e.cod_pedido_pf_ultra
where desc_activacion_habil = 'HABILITADO'


SELECT d.cod_pedido_pf_ultra, CC.CLIV_RAZON_SOCIAL
FROM data_ultra_procesado_uat d
inner join PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE CC ON EP.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE


ALTER TABLE data_ultra_procesado_uat ADD flg_valid_nombres tinyint not null default 0


select d.cod_pedido_pf_ultra, bk.cod_pedido_pf_ultra
from data_ultra_procesado_uat d
inner join data_ultra_procesado_uat_bk11 bk on d.id_data = bk.id_data
where d.cod_pedido_pf_ultra <> bk.cod_pedido_pf_ultra
and bk.cod_pedido_pf_ultra <> 0 and d.cod_pedido_pf_ultra <> 0

update data_ultra_procesado_uat set cod_pedido_pf_ultra = 0;

truncate table data_ultra_proc_detalle

select * from data_ultra_proc_detalle

select * from data_ultra_procesado_prod where desc_activacion_habil = 'HABILITADO' and desc_observacion_activacion <> 'El comprobante del 12/2024 no esta cobrado'

select * from data_ultra_procesado_prod where cod_pedido_ultra <> 0 and desc_activacion_habil = 'HABILITADO'

select top 1 * from data_ultra_procesado_prod order by nro_documento

select distinct cli_nro_doc from data_ultra_emision_202412

select  RUC from data_ultra_raw 


select * from data_ultra_emision_202412 where cli_nro_doc = '20522093743'
select * from data_ultra_emision_202412 where cod_circuito = 0 and ID_PEDIDO <> 0 and flg_status_habil = 0


select d.* 
from data_ultra_emision_202412 d
left join data_ultra_raw p on d.ID_PEDIDO = p.IdPedido
where d.ID_PEDIDO <> 0 and p.IdPedido is null

select *
from data_ultra_emision_202412 d
left join data_ultra_procesado_prod p on d.cli_nro_doc = p.nro_documento
where p.nro_documento is null


select * from data_ultra_emision_202412 

select * from data_ultra_procesado_prod where nro_documento = '004030393'
select * from data_ultra_raw where RUC = 'F60717293'
select * from data_ultra_raw WHERE IdPedido in ('500006', '5000051')


select * from ECOM.ECOM_TABLA_GENERAL


select  * from PE_OPTICAL_ADM_PROD_20241224_060004.ECOM.ECOM_CONTROL_PAGO WHERE SERI_ID_SERVICIO = 91564 ORDER BY CPGV_PERIODO_CONSUMO

select * from data_ultra_procesado_uat where nro_documento = '06383156'
select * from data_ultra_raw where RUC = '6383156'

-- update data_ultra_procesado_prod set nro_documento = '000320768', tipo_documento = 'CE' where nro_documento = '00320768'

select * from data_ultra_procesado_uat order by cod_pedido_pf_ultra


select IdPedido, CircuitoCod, RUC, RazonSocial, TipoServicio, Tecnologia, desc_observacion
from data_ultra_raw
WHERE RUC = '6383156'








