return;

select * from data_ultra_emision; -- comprobantes en proceso de cancelacion
select * from data_ultra_emision_new; 


select * from data_ultra_emision
where compro_nro_doc = 'S002-00039757'

update e
set e.desc_situacion = en.situ
from data_ultra_emision e
inner join data_ultra_emision_new en on e.compro_nro_doc = en.num_doc

update data_ultra_emision set SUB_TOTAL=148.31,IGV=26.69,total=175.00,suma_recurrente=175.00
where id_data=68

select en.situ, e.desc_situacion from data_ultra_emision e
inner join data_ultra_emision_new en on e.compro_nro_doc = en.num_doc

select * from data_ultra_emision where compro_nro_doc = 'S002-00039704'

select cod_pedido_pf_ultra cod_pedido_pf_ultra1,status_ingreso_venta,status_resultado,nro_documento
from data_ultra_procesado order by cod_pedido_pf_ultra; -- lee el bot

select * from data_ultra_procesado;

select status_resultado,status_ingreso_venta,desc_latitud,desc_longitud, * from data_ultra_procesado
where estado_programacion = '' and estado_documento = '' and status_ingreso_venta != 10


SELECT id_data, nro_documento, desc_celular, desc_celular2, desc_correo
FROM data_ultra_procesado where flg_validate_celular = 0 order by id_data

select desc_celular,desc_correo,id_data,nro_documento from data_ultra_procesado where id_data >=743 order by nro_documento

select status_ingreso_venta,* from data_ultra_procesado p
inner join data_ultra_emision e on p.cod_circuito = e.cod_circuito and p.cod_pedido_ultra = e.ID_PEDIDO
where p.id_data >=743 order by p.id_data desc


select * from 

select * from data_ultra_emision
where compro_nro_doc in ('S002-00039796',
'S002-00039596',
'S002-00039595',
'S002-00039628',
'S002-00039594',
'S002-00039798'
)

select status_ingreso_venta,* from data_ultra_procesado
where status_ingreso_venta = 1

update data_ultra_procesado set tipo_vivienda = 'Multifamiliar'
where status_ingreso_venta = 1 and tipo_vivienda = 'Condominio/Edificio'


update data_ultra_procesado set status_resultado = '', status_ingreso_venta=1 where id_data >=743

select ec.* from ecom.ECOM_CLIENTE c
inner join ecom.ECOM_CLIENTE_CONTACTO ec on c.CLII_ID_CLIENTE = ec.CLII_ID_CLIENTE
where CLIV_NRO_RUC = '75544787'

update data_ultra_procesado set desc_correo = 'COCOALCAZAR@GMAIL.COM', desc_celular='921591938' where nro_documento = '20612587907';
update data_ultra_procesado set desc_correo = 'ROCIONAPURI337@GMAIL.COM' where nro_documento = '10304413';
update data_ultra_procesado set desc_correo = 'MMEDINA27@HOTMAIL.COM' where nro_documento = '10320394';
update data_ultra_procesado set desc_correo = 'JRIOSG047@GMAIL.COM' where nro_documento = '20440256806';
update data_ultra_procesado set desc_correo = 'C_ALVAREZ85@HOTMAIL.COM' where nro_documento = '42873351';
update data_ultra_procesado set desc_correo = 'ELVISVILCAVILCA@GMAIL.COM' where nro_documento = '74136619';

update data_ultra_procesado set status_resultado = '', status_ingreso_venta=1 where id_data = 765

update data_ultra_procesado set status_resultado = '', status_ingreso_venta=1 where id_data = 759

update data_ultra_procesado set status_resultado = '', status_ingreso_venta=1 where id_data = 744


select * from data_ultra_proc_detalle

update data_ultra_procesado set desc_latitud = -12.103393039975265, desc_longitud = -77.04909683944666 where id_data = 765

update data_ultra_procesado set desc_latitud = -12.164538714669021, desc_longitud = -77.02447469454022 where id_data = 747

update data_ultra_procesado set desc_latitud = -12.081949599706189, desc_longitud = -76.96860557807764 where id_data = 748;

update data_ultra_procesado set desc_latitud = -12.091037470459572, desc_longitud = -77.02615231191712 where id_data = 750;

update data_ultra_procesado set desc_latitud = -12.109901174928561, desc_longitud = -77.04634886220427 where id_data = 755;

SELECT * FROM data_ultra_procesado WHERE desc_activacion_habil = '' order by 1

update data_ultra_procesado set status_resultado = '', status_ingreso_venta = 1
where id_data in(
747
748
750,
755)

update p
set p.desc_activacion_habil = (CASE WHEN e.desc_situacion = 'COBR' THEN 'HABILITADO' ELSE 'NO HABILITADO' END),
 p.desc_observacion_activacion = (CASE WHEN e.desc_situacion = 'COBR' THEN 'ok' ELSE 'El comprobante del 12/2024 no esta cobrado' END)
FROM data_ultra_procesado p
inner join data_ultra_emision e ON e.ID_PEDIDO = p.cod_pedido_ultra
WHERE p.cod_pedido_ultra <> 0 AND e.ID_PEDIDO <> 0
AND p.cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273)
and (e.cod_circuito != 0)

-- casos faltantes
select p.tipo_vivienda,p.*
FROM data_ultra_procesado p
inner join data_ultra_emision e ON e.ID_PEDIDO = p.cod_pedido_ultra
WHERE p.cod_pedido_ultra <> 0 AND e.ID_PEDIDO <> 0
AND p.cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5010273,
5008533
)

-- No puedes continuar con el registro de venta debido a que las CTOs de la Zona se encuentran saturadas o no existen CTOs disponibles

select * from data_ultra_raw where flg_migrado=0

select * from data_ultra_procesado where cod_circuito = 53275

select * from data_ultra_procesado where nro_documento = '48946922'

update data_ultra_procesado
set tipo_vivienda = 'Hogar' where nro_documento = '48946922'

select distinct tipo_vivienda from data_ultra_procesado

update p
set tipo_vivienda = 'Hogar'
FROM data_ultra_procesado p
inner join data_ultra_emision e ON e.ID_PEDIDO = p.cod_pedido_ultra
WHERE p.cod_pedido_ultra <> 0 AND e.ID_PEDIDO <> 0
AND p.cod_pedido_ultra in (5000051,
5000065,
5002114,
5007722,
5008533,
5010273
)

select * from data_ultra_procesado
WHERE fec_baja IS NULL AND estado_pedido = 'Activo' AND status_ingreso_venta = 1 ORDER BY nro_documento


select status_ingreso_venta,estado_pedido,status_resultado,desc_latitud,desc_longitud,* from data_ultra_procesado
order by id_data desc

select status_ingreso_venta,estado_pedido,status_resultado,desc_latitud,desc_longitud,* from data_ultra_procesado where nro_documento ='48946922'

select * from data_ultra_procesado where nro_documento = '48946922'

update data_ultra_procesado set desc_latitud='-12.096506789948245',desc_longitud='-77.0473589867859',status_ingreso_venta=1,status_resultado=''
where nro_documento ='48946922'

update data_ultra_procesado set desc_latitud='-12.09629',desc_longitud='-77.0474',status_ingreso_venta=1,status_resultado=''
where nro_documento ='48946922'


select * from data_ultra_emision
where compro_nro_doc in (
'S002-00040291','S002-00040237','S002-00040139','S002-00040059','S002-00039837','S002-00039797','S002-00039729','S002-00039717','S002-00039696','S002-00039690','S002-00039656','S002-00039577','S002-00039569','S002-00040066','S002-00039572','S002-00040295','S002-00040292','S002-00040287','S002-00040274','S002-00040233','S002-00040227','S002-00040197','S002-00040196','S002-00040163','S002-00040149','S002-00040137','S002-00040106','S002-00040089','S002-00040079','S002-00040077','S002-00040044','S002-00039975','S002-00039960','S002-00039950','S002-00039945','S002-00039920','S002-00039912','S002-00039878','S002-00039874','S002-00039849','S002-00039845','S002-00039833','S002-00039831','S002-00039809','S002-00039790','S002-00039776','S002-00039765','S002-00039764','S002-00039738','S002-00039734','S002-00039728','S002-00039707','S002-00039701','S002-00039634','S002-00039619','S002-00039607','S002-00039591','S002-00039585','S005-00001565','S005-00001556','S005-00001554','S005-00001545','S005-00001542','S005-00001536','S005-00001529','S005-00001526','S005-00001515','S005-00001513','S005-00001510','S005-00001507','S005-00001498','S005-00001496','S005-00001482','S002-00040219','S002-00040134','S002-00040061','S002-00040000','S002-00039952','S002-00039904','S002-00039860','S002-00039739','S002-00039730','S002-00039709','S002-00039682','S005-00001564','S005-00001563','S005-00001558','S005-00001557','S005-00001541','S005-00001523','S005-00001518','S005-00001512','S005-00001509','S005-00001508','S005-00001491','S005-00001490','S005-00001486','S002-00040294','S002-00040284','S002-00040250','S002-00040228','S002-00040207','S002-00040201','S002-00040199','S002-00040189','S002-00040188','S002-00040186','S002-00040182','S002-00040177','S002-00040167','S002-00040166','S002-00040161','S002-00040141','S002-00040122','S002-00040046','S002-00040023','S002-00039999','S002-00039991','S002-00039954','S002-00039947','S002-00039937','S002-00039934','S002-00039929','S002-00039897','S002-00039891','S002-00039890','S002-00039872','S002-00039871','S002-00039869','S002-00039859','S002-00039828','S002-00039815','S002-00039813','S002-00039806','S002-00039771','S002-00039750','S002-00039740','S002-00039735','S002-00039733','S002-00039716','S002-00039705','S002-00039694','S002-00039689','S002-00039662','S002-00039658','S002-00039643','S002-00039637','S002-00039583','S002-00039575','S002-00039574','S002-00039573','S002-00039571'
)
and desc_situacion = 'FACT'

select * from data_ultra_procesado

update data_ultra_emision set desc_situacion = 'COBR'
where id_data in (
174,
686,
705,
719,
732,
737,
752)

ALTER TABLE data_ultra_procesado add estado_programacion varchar(20) not null default '';

ALTER TABLE data_ultra_procesado add estado_documento varchar(20) not null default '';

select * from data_ultra_raw; -- importado desde el excel

select id_data,cod_pedido_pf_ultra,desc_activacion_habil,desc_observacion_activacion,estado_programacion,estado_documento
from data_ultra_procesado

where nro_documento = '47659418';
-- validacion de estados
select * from data_ultra_procesado 
where cod_circuito = '80485'
where desc_activacion_habil = 'NO HABILITADO' and estado_programacion = 'Ejecutada'

select * from tmp_ultra_pedido
where PEDV_NUM_DOCUMENTO = '47659418';

select * from data_ultra_procesado p
inner join tmp_ultra_pedido tp on p.nro_documento = tp.PEDV_NUM_DOCUMENTO
where p.cod_pedido_pf_ultra = 0

select * INTO tmp_ultra_pedido from OPENQUERY(ULTRACRM,'SELECT * from ultra_db_wincrm_20250109.CRM_PEDIDO')


select * INTO #TMP_ESTADO_CRM from OPENQUERY(ULTRACRM,'
SELECT d.DOCI_COD_PEDIDO_REF,t.TIPV_NOMBRE ESTADO_DOCUMENTO,
t2.TIPV_NOMBRE ESTADO_PROGRAMACION
FROM ultra_db_wincrm_20250109.CRM_DOCUMENTO d
INNER JOIN ultra_db_wincrm_20250109.CRM_PROGRAMACION p ON d.DOCI_COD_DOCUMENTO = p.PRGI_COD_DOCUMENTO
INNER JOIN ultra_db_wincrm_20250109.CRM_TIPO t ON d.DOCC_COD_TIPO_ESTADO = t.TIPC_CODIGO AND t.TIPC_CONCEPTO = ''03''
INNER JOIN ultra_db_wincrm_20250109.CRM_TIPO t2 ON p.PRGC_COD_TIPO_ESTADO = t2.TIPC_CODIGO AND t2.TIPC_CONCEPTO = ''32''
')

update p
set estado_programacion = t.ESTADO_PROGRAMACION,
estado_documento = t.ESTADO_DOCUMENTO
from data_ultra_procesado p
inner join #TMP_ESTADO_CRM t on p.cod_pedido_pf_ultra = t.DOCI_COD_PEDIDO_REF

select * from #TMP_ESTADO_CRM

-- reset para los scripts de validacion
update data_ultra_procesado set cod_pedido_pf_ultra = 0, status_ingreso_venta = 10,status_resultado='ok';
update data_ultra_emision set cod_circuito = 0, flg_status_habil = 1;
update data_ultra_procesado set desc_activacion_habil = '', flg_validate_celular = 0, flg_validate_plan = 0, cod_pedido_pf_ultra = 0;

select * from data_ultra_procesado order by desc_activacion_habil 

select * from data_ultra_contacto;

select flg_validate_celular,* from data_ultra_procesado 


SELECT * FROM data_ultra_emision e
inner join data_ultra_raw  r on e.cli_nro_doc = r.RUC
where flg_migrado = 0


select * from data_ultra_emision
where cli_nro_doc = '20522093743'

update data_ultra_emision set flg_status_habil = 0 where id_data = 409

update e
set desc_observacion = '', flg_status_habil = 1
from data_ultra_emision e
where desc_observacion = 'No tiene registro en data_ultra_raw'

select * from data_ultra_procesado where nro_documento = '004030393'

select * from data_ultra_emision where compro_nro_doc = 'S002-00039773'

--INSERT INTO PE_OPTICAL_ADM.dbo.data_ultra_emision (id_data, desc_situacion, desc_tipo_doc, cli_nro_doc, compro_nro_doc, codigo_cliente_pago, desc_cliente, FEC_EMIS, FEC_VENC,
--FEC_CANC, desc_moneda, SUB_TOTAL, IGV, TOTAL, SUMA_RECURRENTE, SUMA_NO_RECURRENTE, SALDO, TFAC, ID_PEDIDO, RED, MONTO_FACT_SOLARIZ, cod_circuito, flg_status_habil,
--desc_observacion, es_sva, sva_cir_codigo)
--VALUES ((select max(id_data) + 1 from data_ultra_emision), N'COBR', N'REC', N'20544994779', N'S002-00039773', 52120, N'(52120) - BRECA BANCA S.A.C.', N'2024-12-12', 
--N'2024-12-24', N'2024-12-19', N'DOLARES', 148.31, 26.69, 175.00, 175.00, 0.00, 0.00, N'AUTO', 0, N'MPLS', 3237.61, 0, 1, N'', 0, 0);

select distinct cod_pedido_ultra, cod_circuito from data_ultra_proc_detalle where flg_validacion = 0

select * from data_ultra_procesado order by id_data desc

-- delete from data_ultra_procesado where cod_pedido_ultra = 5000159

select * from data_ultra_procesado where status_ingreso_venta != 10

select * from data_ultra_raw 
where IdPedido != '-' and IdWinforce = '-'

update data_ultra_raw set flg_migrado = 0, desc_observacion = ''
where IdPedido != '-' and IdWinforce = '-'


select * from data_ultra_raw_enero

select * from data_ultra_gpon_raw
where cod_pedido_ultra in (5000159,
5002606,
5007507,
5009570,
5009580,
5000031,
5000077,
5000145)

update data_ultra_gpon_raw set desc_ultimo_periodo = '202412'
where cod_pedido_ultra in (5000159,
5002606,
5007507,
5009570,
5009580,
5000031,
5000077,
5000145)

select * from data_ultra_raw where flg_migrado = 0

update data_ultra_raw set Moneda = 'Soles' where flg_migrado = 0

update data_ultra_raw set flg_migrado = 0 where flg_migrado = 0

update data_ultra_emision set SUB_TOTAL = 148.31, IGV=26.69,TOTAL = 175.00,SUMA_RECURRENTE=175.00

select * from data_ultra_emision where cod_circuito = 45473

select COMC_IMPORTE_TOTAL_USD,* from ecom.COMPROBANTE where COMC_COD_COMPROBANTE = 11967788


select * from ecom.ECOM_CONTROL_PAGO

update c
set c.COMC_IMPORTE_IGV_USD = 26.69, c.COMC_IMPORTE_USD=148.31,COMC_IMPORTE_TOTAL_USD=175.00
from ecom.COMPROBANTE c
inner join ecom.ECOM_CLIENTE cc on c.COMC_COD_ENTIDAD = cc.CLII_ID_CLIENTE
where COMC_IMPORTE_TOTAL_USD = 875.03

select * from data_ultra_raw where CircuitoCod = 45473

select * from data_ultra_raw where IdPedido = 5000159

select * from 

update data_ultra_raw set RentaMensual = 175.00 where CircuitoCod = 45473

update data_ultra_raw set RentaMensual = 175.00 where CircuitoCod = 45481

update data_ultra_raw set RentaMensual = 175.00 where CircuitoCod = 45485

update data_ultra_raw set RentaMensual = 175.00 where CircuitoCod = 45489

update data_ultra_raw set RentaMensual = 175.00 where CircuitoCod = 65752



select cc.CLIV_NRO_RUC,* from ecom.COMPROBANTE c
inner join ecom.ECOM_CLIENTE cc on c.COMC_COD_ENTIDAD = cc.CLII_ID_CLIENTE
where COMC_IMPORTE_TOTAL_USD = 875.03

where compro_nro_doc = 'S002-00039773'

SELECT * FROM data_ultra_procesado WHERE desc_activacion_habil = '' order by 1
-- alter table data_ultra_proc_detalle add flg_validacion int not null default 0

SELECT p.id_data, p.nro_documento, p.cod_pedido_ultra, p.cod_circuito, p.desc_oferta,
p.ecom_id_servicio, p.ecom_id_contrato, p.cod_pedido_pf_ultra, p.desc_moneda
FROM data_ultra_procesado p
LEFT JOIN data_ultra_proc_detalle d ON p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'ok'
AND d.cod_circuito IS NULL 
and p.estado_programacion = 'Programada'
order by p.id_data

select * from data_ultra_proc_detalle where cod_circuito = 38507

-- truncate table data_ultra_proc_detalle

select * from data_ultra_procesado

select distinct desc_concepto from data_ultra_proc_detalle

select distinct cod_circuito  from data_ultra_proc_detalle
where cod_circuito != 0

select * from data_ultra_emision
where sva_cir_codigo in (select distinct cod_circuito  from data_ultra_proc_detalle
where cod_circuito != 0)


INSERT INTO PE_OPTICAL_ADM.dbo.data_ultra_proc_detalle
(cod_pedido_ultra, cod_circuito, desc_concepto, cod_moneda, monto, cantidad, megas_cantidad, tipo_modalidad,
tipo_naturaleza, tipo_emision, tipo_cuotas, cantidad_cuotas, fecha_inicio, fecha_fin, tipo_producto, flg_validacion
-- desc_validacion, flg_validacion_plan
)
VALUES
(0,49410,'Alquiler Mesh/AP','01',37.00,1,0,'02','01','01','02', null, null, null,'01',1),
(0,184441,'Alquiler Mesh/AP','01',50.00,1,0,'02','01','01','02', null, null, null,'01',1),
(0,216871,'Alquiler Mesh/AP','01',20.00,1,0,'02','01','01','02', null, null, null,'01',1);

select distinct cod_circuito,cod_pedido_ultra from data_ultra_proc_detalle where cod_circuito in  (49410,
184441,
216871)

select * from data_ultra_procesado

update data_ultra_proc_detalle set fecha_inicio = '2024-12-31'

update data_ultra_emision 
set desc_situacion = 'COBR'
where id_data in (356,
449,
640)

select * from data_ultra_procesado where cod_circuito = 38507

update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_circuito = 38507
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_circuito = 222175
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_circuito = 38860
-- --------------------------------------------------------------------------------------------------
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5001998
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000114
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000046
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000037
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000061
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000043
update data_ultra_procesado set desc_observacion_activacion = 'otros' where cod_pedido_ultra = 5000026


select * from data_ultra_procesado  where desc_activacion_habil = 'HABILITADO' and estado_programacion = 'Programada'

select * from data_ultra_proc_detalle

select * from data_ultra_emision

update p
set  u.desc_situacion = 'COBR' -- p.desc_activacion_habil = '', p.desc_observacion_activacion = '',
from data_ultra_procesado p
inner join data_ultra_emision u on u.ID_PEDIDO = p.cod_pedido_ultra and u.cod_circuito = p.cod_circuito
where compro_nro_doc in (
'S002-00040291','S002-00040237','S002-00040139','S002-00040059','S002-00039837','S002-00039797','S002-00039729','S002-00039717','S002-00039696','S002-00039690','S002-00039656','S002-00039577','S002-00039569','S002-00040066','S002-00039572','S002-00040295','S002-00040292','S002-00040287','S002-00040274','S002-00040233','S002-00040227','S002-00040197','S002-00040196','S002-00040163','S002-00040149','S002-00040137','S002-00040106','S002-00040089','S002-00040079','S002-00040077','S002-00040044','S002-00039975','S002-00039960','S002-00039950','S002-00039945','S002-00039920','S002-00039912','S002-00039878','S002-00039874','S002-00039849','S002-00039845','S002-00039833','S002-00039831','S002-00039809','S002-00039790','S002-00039776','S002-00039765','S002-00039764','S002-00039738','S002-00039734','S002-00039728','S002-00039707','S002-00039701','S002-00039634','S002-00039619','S002-00039607','S002-00039591','S002-00039585','S005-00001565','S005-00001556','S005-00001554','S005-00001545','S005-00001542','S005-00001536','S005-00001529','S005-00001526','S005-00001515','S005-00001513','S005-00001510','S005-00001507','S005-00001498','S005-00001496','S005-00001482','S002-00040219','S002-00040134','S002-00040061','S002-00040000','S002-00039952','S002-00039904','S002-00039860','S002-00039739','S002-00039730','S002-00039709','S002-00039682','S005-00001564','S005-00001563','S005-00001558','S005-00001557','S005-00001541','S005-00001523','S005-00001518','S005-00001512','S005-00001509','S005-00001508','S005-00001491','S005-00001490','S005-00001486','S002-00040294','S002-00040284','S002-00040250','S002-00040228','S002-00040207','S002-00040201','S002-00040199','S002-00040189','S002-00040188','S002-00040186','S002-00040182','S002-00040177','S002-00040167','S002-00040166','S002-00040161','S002-00040141','S002-00040122','S002-00040046','S002-00040023','S002-00039999','S002-00039991','S002-00039954','S002-00039947','S002-00039937','S002-00039934','S002-00039929','S002-00039897','S002-00039891','S002-00039890','S002-00039872','S002-00039871','S002-00039869','S002-00039859','S002-00039828','S002-00039815','S002-00039813','S002-00039806','S002-00039771','S002-00039750','S002-00039740','S002-00039735','S002-00039733','S002-00039716','S002-00039705','S002-00039694','S002-00039689','S002-00039662','S002-00039658','S002-00039643','S002-00039637','S002-00039583','S002-00039575','S002-00039574','S002-00039573','S002-00039571'
)
and (u.cod_circuito != 0 or ID_PEDIDO != 0)


update u
set  u.desc_situacion = 'COBR' -- p.desc_activacion_habil = '', p.desc_observacion_activacion = '',
from data_ultra_emision u
inner join data_ultra_procesado p on u.ID_PEDIDO = p.cod_pedido_ultra and u.cod_circuito = p.cod_circuito
where compro_nro_doc in (
'S002-00040291','S002-00040237','S002-00040139','S002-00040059','S002-00039837','S002-00039797','S002-00039729','S002-00039717','S002-00039696','S002-00039690','S002-00039656','S002-00039577','S002-00039569','S002-00040066','S002-00039572','S002-00040295','S002-00040292','S002-00040287','S002-00040274','S002-00040233','S002-00040227','S002-00040197','S002-00040196','S002-00040163','S002-00040149','S002-00040137','S002-00040106','S002-00040089','S002-00040079','S002-00040077','S002-00040044','S002-00039975','S002-00039960','S002-00039950','S002-00039945','S002-00039920','S002-00039912','S002-00039878','S002-00039874','S002-00039849','S002-00039845','S002-00039833','S002-00039831','S002-00039809','S002-00039790','S002-00039776','S002-00039765','S002-00039764','S002-00039738','S002-00039734','S002-00039728','S002-00039707','S002-00039701','S002-00039634','S002-00039619','S002-00039607','S002-00039591','S002-00039585','S005-00001565','S005-00001556','S005-00001554','S005-00001545','S005-00001542','S005-00001536','S005-00001529','S005-00001526','S005-00001515','S005-00001513','S005-00001510','S005-00001507','S005-00001498','S005-00001496','S005-00001482','S002-00040219','S002-00040134','S002-00040061','S002-00040000','S002-00039952','S002-00039904','S002-00039860','S002-00039739','S002-00039730','S002-00039709','S002-00039682','S005-00001564','S005-00001563','S005-00001558','S005-00001557','S005-00001541','S005-00001523','S005-00001518','S005-00001512','S005-00001509','S005-00001508','S005-00001491','S005-00001490','S005-00001486','S002-00040294','S002-00040284','S002-00040250','S002-00040228','S002-00040207','S002-00040201','S002-00040199','S002-00040189','S002-00040188','S002-00040186','S002-00040182','S002-00040177','S002-00040167','S002-00040166','S002-00040161','S002-00040141','S002-00040122','S002-00040046','S002-00040023','S002-00039999','S002-00039991','S002-00039954','S002-00039947','S002-00039937','S002-00039934','S002-00039929','S002-00039897','S002-00039891','S002-00039890','S002-00039872','S002-00039871','S002-00039869','S002-00039859','S002-00039828','S002-00039815','S002-00039813','S002-00039806','S002-00039771','S002-00039750','S002-00039740','S002-00039735','S002-00039733','S002-00039716','S002-00039705','S002-00039694','S002-00039689','S002-00039662','S002-00039658','S002-00039643','S002-00039637','S002-00039583','S002-00039575','S002-00039574','S002-00039573','S002-00039571'
)
and (u.cod_circuito != 0 or ID_PEDIDO != 0)

alter table data_ultra_proc_detalle add flg_validacion int not null default 0

select distinct desc_concepto from data_ultra_proc_detalle where monto like '%.01'

select * from data_ultra_proc_detalle where desc_concepto = 'Ultra 600Mbps'

update data_ultra_proc_detalle set desc_concepto = 'Ultra 600' where desc_concepto = 'Ultra 600Mbps'


update data_ultra_proc_detalle set desc_concepto = 'Decremento de Plan Ultra' where desc_concepto = 'Decremento de plan Ultra'

select p.ancho_banda, p.desc_oferta,pd.megas_cantidad,desc_concepto
from data_ultra_procesado p
inner join data_ultra_proc_detalle pd on p.cod_circuito = pd.cod_circuito
where p.cod_circuito != 0
and desc_concepto = 'Servicio de Internet Ultra'
and p.desc_oferta = 'Migración Ultra 1000 - MPLS'

update pd
set desc_concepto = 'Ultra 1000'
from data_ultra_proc_detalle pd
inner join data_ultra_procesado p on p.cod_circuito = pd.cod_circuito
where p.cod_circuito != 0
and desc_concepto = 'Servicio de Internet Ultra'
and p.desc_oferta = 'Migración Ultra 1000 - MPLS'

select * from data_ultra_proc_detalle

update data_ultra_proc_detalle set desc_concepto = 'Ultra 600' where cod_pedido_ultra in ( 5000159,
5002606,
5007507,
5009570,
5009580,
5000031,
5000077,
5000145)

select desc_oferta,* from data_ultra_procesado

select * from data_ultra_proc_detalle
where flg_validacion != 1

select * from data_ultra_emision u
inner join data_ultra_procesado p on u.ID_PEDIDO = p.cod_pedido_ultra and u.cod_circuito = p.cod_circuito
where compro_nro_doc in (
'S002-00040291','S002-00040237','S002-00040139','S002-00040059','S002-00039837','S002-00039797','S002-00039729','S002-00039717','S002-00039696','S002-00039690','S002-00039656','S002-00039577','S002-00039569','S002-00040066','S002-00039572','S002-00040295','S002-00040292','S002-00040287','S002-00040274','S002-00040233','S002-00040227','S002-00040197','S002-00040196','S002-00040163','S002-00040149','S002-00040137','S002-00040106','S002-00040089','S002-00040079','S002-00040077','S002-00040044','S002-00039975','S002-00039960','S002-00039950','S002-00039945','S002-00039920','S002-00039912','S002-00039878','S002-00039874','S002-00039849','S002-00039845','S002-00039833','S002-00039831','S002-00039809','S002-00039790','S002-00039776','S002-00039765','S002-00039764','S002-00039738','S002-00039734','S002-00039728','S002-00039707','S002-00039701','S002-00039634','S002-00039619','S002-00039607','S002-00039591','S002-00039585','S005-00001565','S005-00001556','S005-00001554','S005-00001545','S005-00001542','S005-00001536','S005-00001529','S005-00001526','S005-00001515','S005-00001513','S005-00001510','S005-00001507','S005-00001498','S005-00001496','S005-00001482','S002-00040219','S002-00040134','S002-00040061','S002-00040000','S002-00039952','S002-00039904','S002-00039860','S002-00039739','S002-00039730','S002-00039709','S002-00039682','S005-00001564','S005-00001563','S005-00001558','S005-00001557','S005-00001541','S005-00001523','S005-00001518','S005-00001512','S005-00001509','S005-00001508','S005-00001491','S005-00001490','S005-00001486','S002-00040294','S002-00040284','S002-00040250','S002-00040228','S002-00040207','S002-00040201','S002-00040199','S002-00040189','S002-00040188','S002-00040186','S002-00040182','S002-00040177','S002-00040167','S002-00040166','S002-00040161','S002-00040141','S002-00040122','S002-00040046','S002-00040023','S002-00039999','S002-00039991','S002-00039954','S002-00039947','S002-00039937','S002-00039934','S002-00039929','S002-00039897','S002-00039891','S002-00039890','S002-00039872','S002-00039871','S002-00039869','S002-00039859','S002-00039828','S002-00039815','S002-00039813','S002-00039806','S002-00039771','S002-00039750','S002-00039740','S002-00039735','S002-00039733','S002-00039716','S002-00039705','S002-00039694','S002-00039689','S002-00039662','S002-00039658','S002-00039643','S002-00039637','S002-00039583','S002-00039575','S002-00039574','S002-00039573','S002-00039571'
)
and (u.cod_circuito != 0 or ID_PEDIDO != 0)
and p.desc_activacion_habil = ''

select * from data_ultra_emision where desc_observacion != 'ok' and es_sva = 0 AND cod_circuito = 0 AND ID_PEDIDO = 0

select desc_situacion,cli_nro_doc,compro_nro_doc,desc_cliente, desc_moneda
from data_ultra_emision
where desc_observacion != 'ok' and es_sva = 0 AND cod_circuito = 0 AND ID_PEDIDO = 0


and p.cod_circuito is null

and desc_observacion != 'ok'

select * from data_u


SELECT * FROM data_ultra_raw WHERE flg_migrado = 0

update data_ultra_raw set flg_migrado = 1 where CircuitoCod = 44878

 select * from data_ultra_procesado
 order by id_data desc

 select * from data_ultra_raw

 delete data_ultra_procesado where id_data = 745

select * into #tmp_opti_clientes FROM	OPENROWSET('MSDASQL', 'Driver=PostgreSQL ANSI(x64);uid=postgres;Server=10.1.4.25;port=5432;database=opticalip;pwd='' ',
						'SELECT cli_codigo,cli_razon_social,cli_nro_ruc FROM opti_clientes')

select * from data_ultra_raw u
inner join #tmp_opti_clientes c on u.RUC = c.cli_nro_ruc
where u.flg_migrado = 0
and CircuitoCod is not null

select *  INTO DATA_ULTRA_RAW_BK from data_ultra_raw


update u
set u.ClienteID = c.cli_codigo
from data_ultra_raw u
inner join #tmp_opti_clientes c on u.RUC = c.cli_nro_ruc
where u.flg_migrado = 0
and CircuitoCod is not null

select * from #tmp_opti_clientes

select * from sys.procedures 
where OBJECT_DEFINITION(OBJECT_ID) like '%10.1.2.182%'

INSERT INTO dbo.data_ultra_emision
(id_data,desc_situacion, desc_tipo_doc, cli_nro_doc, compro_nro_doc, codigo_cliente_pago, desc_cliente, FEC_EMIS, FEC_VENC, FEC_CANC, desc_moneda, SUB_TOTAL,
IGV, TOTAL, SUMA_RECURRENTE, SUMA_NO_RECURRENTE, SALDO, TFAC, ID_PEDIDO, RED, MONTO_FACT_SOLARIZ, cod_circuito, flg_status_habil)
VALUES ((select max(id_data)+1 from data_ultra_emision),N'COBR', N'REC', N'08248924', N'S002-00039704', 47334, N'(47334) - VALLE RISSO, TOMAS MANUEL',
N'2024-12-12', N'2024-12-24', N'2024-12-19', N'DOLARES', 148.31, 26.69, 175.00, 175.00,
0.00, 0.00, N'AUTO', 0, N'MPLS', 1295.00, 0, 1);
-- 184654

DECLARE @RUC VARCHAR(50) = '20604712905'
DECLARE @RUC_INT VARCHAR(50) = '20604712905'
DECLARE @MONTO_USB DECIMAL(18, 2) = 30.00
DECLARE @MONTO_USOL DECIMAL(18, 2) = -1
DECLARE @ID_DATA INT = 606
DECLARE @CIR_CODIGO_REF INT = 209538

SELECT * FROM data_ultra_raw WHERE RUC = @RUC_INT;

SELECT DISTINCT C.CLIV_NRO_RUC, CA.CATV_DESCRIPCION_GLOSA
FROM ECOM.ECOM_CLIENTE C
INNER JOIN ECOM.COMPROBANTE CO ON C.CLII_ID_CLIENTE = CO.COMC_COD_ENTIDAD
INNER JOIN ECOM.COMPROBANTE_DET CD ON CO.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
INNER JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON SD.SDEI_ID_SERVICIO_DETALLE = CD.SDEI_ID_SERVICIO_DETALLE
INNER JOIN ECOM.ECOM_CATALOGO CA ON SD.CATI_ID_CATALOGO = CA.CATI_ID_CATALOGO
WHERE C.CLIV_NRO_RUC = @RUC AND (CO.COMC_IMPORTE_TOTAL_USD = @MONTO_USB OR CO.COMC_IMPORTE_TOTAL_SOLES = @MONTO_USOL)


UPDATE data_ultra_emision SET es_sva = 1, sva_cir_codigo = @CIR_CODIGO_REF, flg_status_habil = 0
where id_data = @ID_DATA AND (TOTAL = @MONTO_USB OR TOTAL = @MONTO_USOL) AND cli_nro_doc = @RUC;

-------------
select * from data_ultra_emision 
where ID_PEDIDO = 0 and cod_circuito = 0 and es_sva = 0

UPDATE data_ultra_emision SET desc_observacion = 'ok' where id_data = 63;

UPDATE data_ultra_emision SET desc_observacion = 'ok' where id_data = 135;

UPDATE data_ultra_emision SET desc_observacion = 'ok' where id_data in (529,670);





SELECT DISTINCT C.CLIV_NRO_RUC, CA.CATV_DESCRIPCION_GLOSA
FROM ECOM.ECOM_CLIENTE C
INNER JOIN ECOM.COMPROBANTE CO ON C.CLII_ID_CLIENTE = CO.COMC_COD_ENTIDAD
INNER JOIN ECOM.COMPROBANTE_DET CD ON CO.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
INNER JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON SD.SDEI_ID_SERVICIO_DETALLE = CD.SDEI_ID_SERVICIO_DETALLE
INNER JOIN ECOM.ECOM_CATALOGO CA ON SD.CATI_ID_CATALOGO = CA.CATI_ID_CATALOGO
where co.COMC_DOC_NUMERO = 00038501 and CO.COMC_DOC_SERIE = 'S002'
WHERE C.CLIV_NRO_RUC = @RUC AND (CO.COMC_IMPORTE_TOTAL_USD = @MONTO_USB OR CO.COMC_IMPORTE_TOTAL_SOLES = @MONTO_USOL)


select sva_cir_codigo from data_ultra_emision where es_sva = 1

select desc_activacion_habil, updated_at as updated_at2,* from data_ultra_procesado where cod_circuito in (select sva_cir_codigo from data_ultra_emision where es_sva = 1)
order by updated_at

select updated_at from data_ultra_procesado order by updated_at desc

select * from data_ultra_emision where cod_circuito in (38748,
50978,
81137,
66497)

SELECT a.id_data, a.cli_nro_doc, a.cod_circuito, a.desc_situacion, a.desc_moneda, a.SUB_TOTAL, a.compro_nro_doc,
a.TOTAL
FROM data_ultra_emision a
WHERE  a.cli_nro_doc = '08248924'

select * from data_ultra_emision order by 1 desc


EXEC sp_columns 'COMPROBANTE_DET'
SELECT TOP 10 * FROM ECOM.COMPROBANTE