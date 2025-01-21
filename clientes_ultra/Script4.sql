SELECT S.SERI_ID_SERVICIO, S.SERI_MODALIDAD_EMISION, 
S.SESI_ID_SERVICIO_ESTADO, S.SERV_SITUACION,
SD.SDEI_ID_SERVICIO_DETALLE, SD.CATI_ID_CATALOGO,
SD.SDEI_MONEDA, SD.SDEN_MONTO, SD.SDED_FECHA, SD.SDED_FECHA_FIN,
SD.SDEI_TIPO_EMISION, SD.SDEI_TIPO_DETALLE, SD.SDEI_CANTIDAD,
C.CATV_DESCRIPCION_GLOSA, C.CATV_DESCRIPCION_CONCEPTO, CAST(SD.SDED_FECHA_REGISTRO AS DATE) fec_registro
FROM ECOM.ECOM_SERVICIO S
INNER JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON S.SERI_ID_SERVICIO = SD.SERI_ID_SERVICIO
INNER JOIN ECOM.ECOM_CATALOGO C ON SD.CATI_ID_CATALOGO = C.CATI_ID_CATALOGO
WHERE S.CONI_ID_CONTRATO = 196275 AND S.SERI_ID_SERVICIO = 57002 
AND (SD.SDED_FECHA_FIN IS NULL OR SD.SDED_FECHA_FIN > '2024-06-30') AND SD.SDEN_MONTO <> 0;

select * from ECOM.ECOM_TABLA_GENERAL
SELECT * FROM ECOM.ESTADO
/*
0	- Modalida de Emision
1	Modalidad Adelantada
2	Modalidad Vencida
3	Modalidad Postpago

1	1	Recurrente (Emision Mensual)
1	2	No Recurrente (Emision Unica)
1	3	No Recurrente (Emision Fraccionada)
1	4	Recurrente (Emision Anual)

3	0	- Tipos de Detalle de Servicio
3	1	Contrato Inicial
3	2	Variables
*/

select * from ECOM.ECOM_SERVICIO_DETALLE WHERE SERI_ID_SERVICIO = 67233

SELECT * FROM ECOM.ECOM_CATALOGO WHERE CATI_ID_CATALOGO = 40

SELECT COMD_DES_CONCEPTO FROM ECOM.COMPROBANTE_DET WHERE SERI_ID_SERVICIO = 67233

SELECT * FROM ECOM.ECOM_CONTROL_PAGO WHERE SERI_ID_SERVICIO = 67233

truncate table data_ultra_proc_detalle

select distinct cod_pedido_ultra, cod_circuito from data_ultra_proc_detalle

select * from data_ultra_proc_detalle 

select * from data_ultra_procesado

select top 23 cod_pedido_pf_ultra cod_pedido_pf_ultra1,estado_programacion,p.* from data_ultra_procesado p
inner join data_ultra_emision u on p.cod_pedido_ultra = u.ID_PEDIDO and p.cod_circuito = u.cod_circuito
order by cod_pedido_pf_ultra desc


select * from data_ultra_procesado where cod_pedido_pf_ultra = 733

UPDATE data_ultra_procesado
SET estado_programacion = 'Programada'
WHERE cod_pedido_pf_ultra IN (
    SELECT TOP 23 p.cod_pedido_pf_ultra
    FROM data_ultra_procesado p
    INNER JOIN data_ultra_emision u 
        ON p.cod_pedido_ultra = u.ID_PEDIDO AND p.cod_circuito = u.cod_circuito
    ORDER BY p.cod_pedido_pf_ultra DESC
);

select * from data_ultra_raw where CircuitoCod = 44889


select * from data_ultra_emision where compro_nro_doc = 'S002-00039757'

select * from data_ultra_emision where compro_nro_doc = 'S002-00039637'


select * from data_ultra_procesado where cod_pedido_ultra = 5010273

select * from data_ultra_raw
where IdPedido in (
'5000051',
'5000065',
'5002114',
'5007722',
'5008533',
'5010273'
)


select * from data_ultra_raw where CircuitoCod in (38507,
53275
)

select * from data_ultra_emision where ID_PEDIDO = 5000051



SELECT p.id_data, p.nro_documento, p.cod_pedido_ultra, p.cod_circuito, p.desc_oferta,
p.ecom_id_servicio, p.ecom_id_contrato, p.cod_pedido_pf_ultra, p.desc_moneda
FROM data_ultra_procesado p
LEFT JOIN data_ultra_proc_detalle d ON p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.cod_pedido_pf_ultra <> 0 AND p.desc_activacion_habil = 'HABILITADO' AND p.desc_observacion_activacion = 'OK'
AND d.cod_circuito IS NULL and  p.id_data in (744,761,763,760,765,758,759,764,743,749,750,751,752,753,754,755,748,746,747,745,757,756,762)
order by p.id_data

SELECT * FROM data_ultra_proc_detalle WHERE desc_activacion_habil = '' order by 1


SELECT * FROM data_ultra_procesado 
where desc_activacion_habil = 'HABILITADO' AND desc_observacion_activacion = 'OK' AND cod_pedido_pf_ultra = 750
and estado_programacion = 'Programada'

select desc_activacion_habil,desc_observacion_activacion,estado_programacion,* from data_ultra_procesado
where cod_pedido_pf_ultra = 750

select * from data_ultra_emision u

delete from data_ultra_proc_detalle where desc_concepto = 'Descuento'

delete from data_ultra_proc_detalle where desc_concepto = 'Instalación de cableado equipo Mesh'

SELECT CP.CPGI_ID_CONTROL_PAGO, CP.SDEI_ID_SERVICIO_DETALLE, 
CP.CPGI_MONEDA, CPGN_MONTO, CPGI_ESTADO, CPGB_SITUACION, CPGV_PERIODO_CONSUMO,
CPGD_FECHA_CONSUMO_INI, CPGD_FECHA_CONSUMO_FIN
FROM ECOM.ECOM_CONTROL_PAGO CP
WHERE CP.SERI_ID_SERVICIO = 637207 AND CPGV_PERIODO_CONSUMO > '202406'
ORDER BY CP.CPGV_PERIODO_CONSUMO, CP.SDEI_ID_SERVICIO_DETALLE;


SELECT CONCAT(C.COMC_DOC_SERIE,'-',C.COMC_DOC_NUMERO), C.COMC_COD_COMPROBANTE, C.MONI_ID_MONEDA, C.COMV_PERIODO_COMPROBANTE,
C.COMC_IMPORTE_TOTAL_SOLES compro_total_soles, C.COMC_IMPORTE_TOTAL_USD compro_total_usb,
C.COMC_IMPORTE_SOLES, 
CD.COMD_COD_DETALLE, CD.SDEI_ID_SERVICIO_DETALLE,
CD.COMD_IMPORTE_TOTAL_SOL compro_det_total_soles, CD.COMD_IMPORTE_TOTAL_USD cpmpro_det_total_usb,
CD.COMD_IMPORTE_SOL,
CD.CPGI_ID_CONTROL_PAGO,
c.COMC_COD_ENTIDAD,
CD.SERI_ID_SERVICIO,
CC.CLIV_NOMBRE_COMERCIAL,CC.CLIV_NRO_RUC,cc.CLIV_RAZON_SOCIAL
FROM ECOM.COMPROBANTE C
INNER JOIN ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
inner join ECOM.ECOM_CLIENTE CC on C.COMC_COD_ENTIDAD = CC.CLII_ID_CLIENTE
WHERE C.COMV_PERIODO_COMPROBANTE > '202406' -- AND C.COMC_COD_ENTIDAD = 143877 -- AND CD.SERI_ID_SERVICIO = 163857
AND C.ESTI_ID_ESTADO <> 11 AND C.COMC_TIPO_OPERACION IN ('C', 'A')
AND CC.CLIV_NRO_RUC = '25744981'
ORDER BY C.COMV_PERIODO_COMPROBANTE



select * from ecom.MONEDA

SELECT CONCAT(C.COMC_DOC_SERIE,'-',C.COMC_DOC_NUMERO), C.COMC_COD_COMPROBANTE, C.MONI_ID_MONEDA, C.COMV_PERIODO_COMPROBANTE,
C.COMC_IMPORTE_TOTAL_SOLES compro_total_soles, C.COMC_IMPORTE_TOTAL_USD compro_total_usb,
C.COMC_IMPORTE_SOLES,
C.COMC_IMPORTE_IGV_SOLES,
c.COMC_COD_ENTIDAD,
CC.CLIV_NOMBRE_COMERCIAL,CC.CLIV_NRO_RUC,cc.CLIV_RAZON_SOCIAL
FROM ECOM.COMPROBANTE C
-- INNER JOIN ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
inner join ECOM.ECOM_CLIENTE CC on C.COMC_COD_ENTIDAD = CC.CLII_ID_CLIENTE
WHERE C.COMV_PERIODO_COMPROBANTE > '202406' -- AND C.COMC_COD_ENTIDAD = 143877 -- AND CD.SERI_ID_SERVICIO = 163857
AND C.ESTI_ID_ESTADO <> 11 AND C.COMC_TIPO_OPERACION IN ('C', 'A')
AND CC.CLIV_NRO_RUC = '43903005'
ORDER BY C.COMV_PERIODO_COMPROBANTE


select * from data_ultra_emision

select * from data_ultra_procesado

select * from data_ultra_raw

select * from data_ultra_gpon_raw

select * from data_ultra_contacto

SELECT CP.CPGI_ID_CONTROL_PAGO, CP.SDEI_ID_SERVICIO_DETALLE, 
CP.CPGI_MONEDA, CPGN_MONTO, CPGI_ESTADO, CPGB_SITUACION, CPGV_PERIODO_CONSUMO,
CPGD_FECHA_CONSUMO_INI, CPGD_FECHA_CONSUMO_FIN, SD.CATI_ID_CATALOGO
FROM ECOM.ECOM_CONTROL_PAGO CP
LEFT JOIN ECOM.ECOM_SERVICIO_DETALLE SD ON CP.SDEI_ID_SERVICIO_DETALLE = SD.SDEI_ID_SERVICIO_DETALLE
WHERE CP.SERI_ID_SERVICIO = 61182 AND CPGN_MONTO <> 0 AND CPGV_PERIODO_CONSUMO > '202406' 
ORDER BY CP.CPGV_PERIODO_CONSUMO, CP.SDEI_ID_SERVICIO_DETALLE;

SELECT * FROM ECOM.COMPROBANTE_DET WHERE CPGI_ID_CONTROL_PAGO = 48742107 -- 14345891

SELECT C.COMC_COD_COMPROBANTE id_compro, C.MONI_ID_MONEDA moneda, C.COMV_PERIODO_COMPROBANTE periodo,
case when C.MONI_ID_MONEDA = 1 then C.COMC_IMPORTE_TOTAL_SOLES else C.COMC_IMPORTE_TOTAL_USD end compro_total, 
case when C.MONI_ID_MONEDA = 1 then C.COMC_IMPORTE_SOLES else C.COMC_IMPORTE_USD end compro_importe, 
CD.COMD_DES_CONCEPTO,
CD.SDEI_ID_SERVICIO_DETALLE id_ser_det,
case when C.MONI_ID_MONEDA = 1 then CD.COMD_IMPORTE_SOL else CD.COMD_IMPORTE_USD end compro_det,
case when C.MONI_ID_MONEDA = 1 then CD.COMD_IMPORTE_TOTAL_SOL else CD.COMD_IMPORTE_TOTAL_USD end compro_det_total,
CD.CPGI_ID_CONTROL_PAGO
FROM ECOM.COMPROBANTE C
INNER JOIN ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
WHERE C.COMC_COD_COMPROBANTE = 13383286


SELECT C.COMC_COD_COMPROBANTE id_compro, C.MONI_ID_MONEDA moneda, C.COMV_PERIODO_COMPROBANTE periodo,
case when C.MONI_ID_MONEDA = 1 then C.COMC_IMPORTE_TOTAL_SOLES else C.COMC_IMPORTE_TOTAL_USD end compro_total, 
case when C.MONI_ID_MONEDA = 1 then C.COMC_IMPORTE_SOLES else C.COMC_IMPORTE_USD end compro_importe, 
CD.COMD_DES_CONCEPTO,
CD.SDEI_ID_SERVICIO_DETALLE id_ser_det,
CD.COMD_IMPORTE_TOTAL_SOL compro_det_total_soles, CD.COMD_IMPORTE_TOTAL_USD cpmpro_det_total_usb,
CD.COMD_IMPORTE_SOL,
CD.CPGI_ID_CONTROL_PAGO
FROM ECOM.COMPROBANTE C
INNER JOIN ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
WHERE C.COMV_PERIODO_COMPROBANTE > '202406' AND CD.SERI_ID_SERVICIO = 70180
AND C.ESTI_ID_ESTADO <> 11 AND C.COMC_TIPO_OPERACION IN ('C', 'A')
ORDER BY C.COMV_PERIODO_COMPROBANTE

