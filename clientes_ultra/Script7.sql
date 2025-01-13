return;

select * from data_ultra_emision;
select * from data_ultra_emision_new;

select * from data_ultra_procesado 
select * from data_ultra_raw;

update data_ultra_emision set cod_circuito = 0, flg_status_habil = 1
update data_ultra_procesado set desc_activacion_habil = '', flg_validate_celular = 0, flg_validate_plan = 0, cod_pedido_pf_ultra = 0


DECLARE @RUC VARCHAR(50) = 'F60717293'
DECLARE @RUC_INT VARCHAR(50) = 'F60717293'
DECLARE @MONTO_USB DECIMAL(18, 2) = 20.00
DECLARE @MONTO_USOL DECIMAL(18, 2) = -1
DECLARE @ID_DATA INT = 145
DECLARE @CIR_CODIGO_REF INT = 226535

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

UPDATE data_ultra_emision SET cod_circuito = 75056 where id_data = 600


SELECT a.id_data, a.cli_nro_doc, a.cod_circuito, a.desc_situacion, a.desc_moneda, a.SUB_TOTAL, a.compro_nro_doc,
a.TOTAL
FROM data_ultra_emision a
WHERE  a.cli_nro_doc = '08248924'

select * from data_ultra_emision order by 1 desc


EXEC sp_columns 'COMPROBANTE_DET'
SELECT TOP 10 * FROM ECOM.COMPROBANTE