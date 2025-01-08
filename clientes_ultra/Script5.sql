
return;


select * from data_ultra_emision_202412

select desc_situacion, SITU, cli_nro_doc, RUC, desc_cliente, CLIENTE, p.ID_PEDIDO, n.ID_PEDIDO
from data_ultra_emision_prod p
inner join data_ultra_emision_prod_new n on p.compro_nro_doc = n.NUM_DOC
ORDER BY 1;

select desc_situacion, SITU
from data_ultra_emision_prod p
inner join data_ultra_emision_prod_new n on p.compro_nro_doc = n.NUM_DOC;

ALTER TABLE data_ultra_emision_prod ADD desc_observacion VARCHAR(150) NOT NULL DEFAULT ''
ALTER TABLE data_ultra_emision_prod ADD es_sva tinyint NOT NULL DEFAULT 0
ALTER TABLE data_ultra_emision_prod ADD sva_cir_codigo int NOT NULL DEFAULT 0

ALTER TABLE data_ultra_procesado_prod ADD flg_check_nom_v2 int NOT NULL DEFAULT 0
ALTER TABLE data_ultra_procesado_prod ADD flg_nombre_validado int NOT NULL DEFAULT 0

select cod_circuito, desc_observacion, ID_PEDIDO, * from data_ultra_emision_prod p wheRe ID_PEDIDO = 0 ORDER BY p.cod_circuito desc, 2 desc;
select cod_circuito, desc_observacion, ID_PEDIDO, * from data_ultra_emision_prod p ORDER BY p.cod_circuito desc, 2 desc;
select distinct desc_observacion  from data_ultra_emision_prod p  ;


SELECT desc_activacion_habil, desc_observacion_activacion, * FROM data_ultra_procesado_prod p order by 1
SELECT cod_pedido_pf_ultra, * FROM data_ultra_procesado_prod p order by 2

select * from data_ultra_procesado_prod where nro_documento = '000320768'


---------------------------------------------------------------------------------

UPDATE data_ultra_procesado_prod SET ape_paterno = 'PUYÓ' WHERE id_data = '2'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 2;
UPDATE data_ultra_procesado_prod SET nombres = 'JP SA COUTO PERU S.A.C.' WHERE id_data = '18'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 18; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'LEÓN' WHERE id_data = '26'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 26; 
UPDATE data_ultra_procesado_prod SET nombres = 'PRODUCCIONES FOTOGRAFICAS SOCIEDAD ANONIMA - PROFOT S.A.' WHERE id_data = '27'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 27; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'DE LA PEÑA' WHERE id_data = '36'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 36; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'MUÑIZ' WHERE id_data = '38'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 38; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'DEL SOLAR DE ONRUBIA' WHERE id_data = '45'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 45; 
UPDATE data_ultra_procesado_prod SET nombres = 'MCC FILMS SAC' WHERE id_data = '51'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 51; 
UPDATE data_ultra_procesado_prod SET nombres = 'THOMAS RUTHERFORD' WHERE id_data = '64'; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'MOORE' WHERE id_data = '64'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'HUYETT' WHERE id_data = '64'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '00320768' WHERE id_data = '64'; 
UPDATE data_ultra_procesado_prod SET nombres = 'HERNAN LESTER' WHERE id_data = '71'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 71; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'REAÑO' WHERE id_data = '87'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 87; 
UPDATE data_ultra_procesado_prod SET nombres = 'CASTRO DE LA MATA SOCIEDAD CIVIL DE RESPONSABILIDAD LIMITADA' WHERE id_data = '91'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 91; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'AGÜERO' WHERE id_data = '129'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 129; 
UPDATE data_ultra_procesado_prod SET nombres = 'BACK OFFICE ALRO S.A.C' WHERE id_data = '135'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 135;
UPDATE data_ultra_procesado_prod SET nombres = 'DIANA' WHERE id_data = '137'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 137; 
UPDATE data_ultra_procesado_prod SET nombres = 'ARCH PARTNERS INVERSIONES INMOBILIARIAS E.I.R.L.' WHERE id_data = '143'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 143; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'GONZALES' WHERE id_data = '154'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 154; 
UPDATE data_ultra_procesado_prod SET nombres = '4D2 STUDIOS' WHERE id_data = '156'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 156; 
UPDATE data_ultra_procesado_prod SET nombres = 'JUAN JOSÉ' WHERE id_data = '158'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 158; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'RODRIGUEZ DE POBLETE' WHERE id_data = '163'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 163; 
UPDATE data_ultra_procesado_prod SET nombres = 'MARIA JOSEFINA CARMEN' WHERE id_data = '164'; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'ARGUEDAS' WHERE id_data = '164'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'MATUTE DE MARTELES' WHERE id_data = '164'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 164; 
UPDATE data_ultra_procesado_prod SET nombres = 'MARÍA ESTHER' WHERE id_data = '165'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 165; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'HARRINSON' WHERE id_data = '168'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 168; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'AÑAÑOS' WHERE id_data = '169'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 169; 
UPDATE data_ultra_procesado_prod SET nombres = 'INMOBILIARIA ALDABAS SAC' WHERE id_data = '170'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 170; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'GALDÓS' WHERE id_data = '172'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 172; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'COVEÑAS' WHERE id_data = '175'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 175; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'HERNÁNDEZ' WHERE id_data = '191'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 191; 
UPDATE data_ultra_procesado_prod SET nombres = 'COMPAÑIA DE JESUS COMUNIDAD DE FATIMA' WHERE id_data = '206'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 206; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'VACCARI VDA DE MUSIRIS' WHERE id_data = '219'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 219; 
UPDATE data_ultra_procesado_prod SET nombres = 'ENRIQUE- ZICROM GROUP INTERNATIONAL' WHERE id_data = '221'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 221; 
UPDATE data_ultra_procesado_prod SET nombres = 'STEWART FREIRE & ASOCIADOS S.A.C. CORREDORES DE SEGUROS' WHERE id_data = '235'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 235; 
UPDATE data_ultra_procesado_prod SET nombres = 'INVESTMENT & HEALTH SERVICES S.A.C' WHERE id_data = '236'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 236; 
UPDATE data_ultra_procesado_prod SET nombres = 'FANG CONSTRUCTORES S.A.C' WHERE id_data = '237'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 237; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'LÓPEZ-ALFARO DE ROMERO' WHERE id_data = '250'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 250; 
UPDATE data_ultra_procesado_prod SET nombres = 'MARGARITA E.' WHERE id_data = '269'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'SILVERA DE GUIZADO' WHERE id_data = '269'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 269; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'MUÑOZ' WHERE id_data = '284'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 284; 
UPDATE data_ultra_procesado_prod SET nombres = 'MARIA' WHERE id_data = '296'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 296; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'HAMEAU DE RUIZ DE SOMOCURCIO' WHERE id_data = '304'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 304; 
UPDATE data_ultra_procesado_prod SET nombres = 'MAS COMUNICADORES SOCIEDAD ANONIMA CERRADA' WHERE id_data = '327'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 327; 
UPDATE data_ultra_procesado_prod SET nombres = 'NICOLAS ENRIQUE' WHERE id_data = '336'; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '336'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 336; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'FARIAS' WHERE id_data = '354'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 354; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'GOÑI' WHERE id_data = '362'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 362; 
UPDATE data_ultra_procesado_prod SET nombres = 'ALFREDO' WHERE id_data = '398';
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 398; 
UPDATE data_ultra_procesado_prod SET nombres = 'NUMA AMERICAS CORP' WHERE id_data = '408'; 
UPDATE data_ultra_procesado_prod SET ape_paterno = '.' WHERE id_data = '408'; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '408'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 408; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'PEÑA' WHERE id_data = '410'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 410; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'CHACÓN' WHERE id_data = '414'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 414; 
UPDATE data_ultra_procesado_prod SET nombres = 'GERMAN HORACIO' WHERE id_data = '427'; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '427'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 427; 
UPDATE data_ultra_procesado_prod SET nombres = 'BETTINA' WHERE id_data = '439'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 439; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'NEYRA' WHERE id_data = '444'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 444; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'FLOREZ - ESTRADA' WHERE id_data = '452'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 452; 
UPDATE data_ultra_procesado_prod SET nombres = 'JOSÉ FERNANDO' WHERE id_data = '458'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 458; 
UPDATE data_ultra_procesado_prod SET nombres = 'JOSÉ ALONSO' WHERE id_data = '479'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 479;
UPDATE data_ultra_procesado_prod SET ape_paterno = 'NUÑEZ' WHERE id_data = '497'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 497; 
UPDATE data_ultra_procesado_prod SET nombres = 'E-LEMENTAL PERU S.A.C.' WHERE id_data = '505'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 505; 
UPDATE data_ultra_procesado_prod SET nombres = 'ARMANDO RODOLFO MIGUEL' WHERE id_data = '507'; 
UPDATE data_ultra_procesado_prod SET nombres = 'HARDCOMP S.R.L.' WHERE id_data = '510'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 510; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'DAHUPD' WHERE id_data = '512';
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 512; 
UPDATE data_ultra_procesado_prod SET nombres = 'TAMESIS ADVISORS S.A.C.' WHERE id_data = '515'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 515; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'LUNA' WHERE id_data = '521'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'REQUENA DE SIGARROSTEGUI' WHERE id_data = '521'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 521; 
UPDATE data_ultra_procesado_prod SET nombres = 'FARM2MARKET SOCIEDAD ANONIMA CERRADA - F2M S.A.C.' WHERE id_data = '522'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 522; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '524'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 524; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'FLOREZ-ESTRADA' WHERE id_data = '526'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 526; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'SANCHEZ - MANRIQUE' WHERE id_data = '528'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 528; 
UPDATE data_ultra_procesado_prod SET nombres = 'DIEGO' WHERE id_data = '531'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 531; 
UPDATE data_ultra_procesado_prod SET nombres = 'ROD & REN SOCIEDAD ANONIMA CERRADA - ROD & REN SAC' WHERE id_data = '532'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 532; 
UPDATE data_ultra_procesado_prod SET nombres = 'MARCO ANTONIO' WHERE id_data = '533'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 533; 
UPDATE data_ultra_procesado_prod SET nombres = 'FRANCESCA' WHERE id_data = '539'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'BONAZZI' WHERE id_data = '539'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 539; 
UPDATE data_ultra_procesado_prod SET nombres = 'CARMEN ROSA' WHERE id_data = '545'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 545; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '548'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 548; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'MUÑOZ NAJAR' WHERE id_data = '551'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 551; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'RUIZ-ELDREDGE' WHERE id_data = '554'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 554; 
UPDATE data_ultra_procesado_prod SET nombres = 'JRLG CONSULTORIA Y CONSTRUCCION SAC' WHERE id_data = '557'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 557; 
UPDATE data_ultra_procesado_prod SET ape_materno = '.' WHERE id_data = '565'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 565; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'GONZÁLEZ' WHERE id_data = '573'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'RÍOS' WHERE id_data = '573'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 573; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 579; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 579; 
UPDATE data_ultra_procesado_prod SET nro_documento = 'AG774859' WHERE id_data = '582'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '20190065' WHERE id_data = '584';
UPDATE data_ultra_procesado_prod SET nro_documento = '01655107' WHERE id_data = '585'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 587; 
UPDATE data_ultra_procesado_prod SET nro_documento = '945609' WHERE id_data = '591'; 
UPDATE data_ultra_procesado_prod SET nro_documento = 'A9155857' WHERE id_data = '594'; 
UPDATE data_ultra_procesado_prod SET nro_documento = 'AUN B49521' WHERE id_data = '596'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 597; 
UPDATE data_ultra_procesado_prod SET nro_documento = '00000733531' WHERE id_data = '598'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '00000574646' WHERE id_data = '599'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'LOREÑA' WHERE id_data = '603'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 603; 
UPDATE data_ultra_procesado_prod SET nombres = 'COGNITTIVA PSICOLOGIA INTEGRAL S.A.C.' WHERE id_data = '606'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 606; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'DE VIVERO' WHERE id_data = '610';
UPDATE data_ultra_procesado_prod SET nombres = 'ADRIAN GABRIEL LUIS FABIANO BEJAR' WHERE id_data = '610';
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 610; 
UPDATE data_ultra_procesado_prod SET nombres = 'CHUQUIHUANCA GARCIA SOCIEDAD CIVIL DE RESPONSABILI' WHERE id_data = '625'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 625; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'FERNANDEZ' WHERE id_data = '636'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 636; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'DE ROMAÑA' WHERE id_data = '683'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 683; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'MUNHOZ' WHERE id_data = '692'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 692; 
UPDATE data_ultra_procesado_prod SET nombres = 'TALIA MARIA DEL ROSARIO MARTINA' WHERE id_data = '697'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 697; 
UPDATE data_ultra_procesado_prod SET nombres = 'JAVIER ANDRÉS' WHERE id_data = '701'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 701; 
UPDATE data_ultra_procesado_prod SET nombres = 'ALMACENES FRIO NARVAL S.A.C - ALMACENES NARVAL S.A.C' WHERE id_data = '722'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 722; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'ROMERO' WHERE id_data = '725'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = 725; 

--------------------------------------------------------------------------------

UPDATE data_ultra_procesado_prod SET ape_materno = 'DE LA PEÑA' WHERE id_data = '70'; 
UPDATE data_ultra_procesado_prod SET ape_materno = 'DEL SOLAR DE ONRUBIA' WHERE id_data = '77'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '00353411' WHERE id_data = '579'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = '585'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '20211001' WHERE id_data = '587'; 
UPDATE data_ultra_procesado_prod SET flg_check_nom_v2 = 1 WHERE id_data = '587'; 
UPDATE data_ultra_procesado_prod SET nro_documento = '00001262357' WHERE id_data = '597'; 
UPDATE data_ultra_procesado_prod SET ape_paterno = 'AÑAÑOS' WHERE id_data = '689'; 

SELECT p.id_data, 
	p.nro_documento,
	CASE WHEN p.nombres <> '' then p.nombres else p.razon_social end proce_nombres,
    CASE WHEN p.ape_paterno <> '' then p.ape_paterno else '.' end proce_ape_paterno,
    CASE WHEN p.ape_materno <> '' then p.ape_materno else '.' end proce_ape_materno, 
	d.nro_documento,
    CASE WHEN d.nombres <> '' then d.nombres else d.razon_social end proce_nombres,
    CASE WHEN d.ape_paterno <> '' then d.ape_paterno else '.' end proce_ape_paterno,
    CASE WHEN d.ape_materno <> '' then d.ape_materno else '.' end proce_ape_materno
FROM data_ultra_procesado_prod p
INNER JOIN data_ultra_procesado_prod_bk2 d on p.id_data = d.id_data
where p.nro_documento <> d.nro_documento or
(CASE WHEN p.nombres <> '' then p.nombres else p.razon_social end) <> (CASE WHEN d.nombres <> '' then d.nombres else d.razon_social end) or
(CASE WHEN p.ape_paterno <> '' then p.ape_paterno else '.' end) <> (CASE WHEN d.ape_paterno <> '' then d.ape_paterno else '.' end) or
(CASE WHEN p.ape_materno <> '' then p.ape_materno else '.' end) <> (CASE WHEN d.ape_materno <> '' then d.ape_materno else '.' end) or
p.id_data in ('2', '18', '26', '27', '36', '38', '45', '51', '64', '71', '87', '91', '129', '135', '137', '143', '154', '156', '158', '163', '164', '165', '168', '169', '170', '172', '175', 175, '191', 191, '206', 206, '219', 219, '221', 221, '235', 235, '236', 236, '237', 237, '250', 250, '269', '269', 269, '284', 284, '296', 296, '304', 304, '327', 327, '336', '336', 336, '354', 354, '362', 362, 398, 408, '410', 410, '414', 414, '427', '427', 427, '439', 439, '444', 444, '452', 452, '458', 458, '479', 47, '497', 497, '505', 505, '507', '510', 510,  512, '515', 515, '521', '521', 521, '522', 522, '524', 524, '526', 526, '528', 528, '531', 531, '532', 532, '533', 533, '539', '539', 539, '545', 545, '548', 548, '551', 551, '554', 554, '557', 557, '565', 565, '573', '573', 573, 579, 579, '582', 584, '585', 587, '591', '594', '596', 597, '598', '599', '603', 603, '606', 610, '625', 625, '636', 636, '683', 683, '692', 692, '697', 697, '701', 701, '722', 722, '725', 725, '70', '77', '579', '585', '587', '597', '689')
order by 1


SELECT * FROM ECOM.ECOM_SERVICIO 
select * from data_utra_e

update data_ultra_proc_detalle_pr set flg_validacion = 0
select * from data_ultra_proc_detalle_pr where flg_validacion = 0 order by 2


select * from data_ultra_proc_detalle_pr;

select * INTO data_ultra_proc_detalle_pr_bk4 from data_ultra_proc_detalle_pr;
select * from data_ultra_proc_detalle_pr;
select distinct desc_concepto from data_ultra_proc_detalle_pr;
SELECT * FROM data_ultra_procesado_prod WHERE cod_circuito = 31151

UPDATE data_ultra_proc_detalle_pr SET desc_concepto = 'Ultra 600' where desc_concepto in ('Servicio de Internet Ultra')
and cod_circuito in (select cod_circuito from data_ultra_procesado_prod where desc_oferta = 'Migración Ultra 600 - MPLS');



select * from data_ultra_proc_detalle_pr where desc_concepto in ('Servicio de Internet Ultra')
and cod_circuito in (select cod_circuito from data_ultra_procesado_prod where desc_oferta = 'Migración Ultra 1000 - MPLS');

select cod_circuito from data_ultra_procesado_prod where desc_oferta = 'Migración Ultra 800 - MPLS'
select cod_circuito from data_ultra_procesado_prod where desc_oferta = 'Migración Ultra 600 - MPLS'

update data_ultra_proc_detalle_pr set desc_concepto = 'Ultra 800' where desc_concepto in ('Migracion a Ultra 800')

select nro_documento, *
from data_ultra_proc_detalle_pr p
inner join data_ultra_procesado_prod d on p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.desc_concepto = 'Servicio de Internet Ultra';


	select *
	into #data_proe
	from (
		select d.nro_documento, p.cod_moneda, d.cod_circuito, d.cod_pedido_ultra, d.cod_pedido_pf_ultra, SUM(p.monto) total, count(*) cant
		from data_ultra_proc_detalle_pr p
		inner join data_ultra_procesado_prod d on p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
		group by d.nro_documento, p.cod_moneda, d.cod_circuito, d.cod_pedido_ultra, d.cod_pedido_pf_ultra
	) a

select *
from #data_proe a
inner join #tab_compro T ON a.cod_pedido_pf_ultra = T.cod_pedido
where total <> moneda

select * from data_ultra_emision


SELECT * 
into #tab_compro
FROM OPENQUERY([ULTRACRM], 'SELECT COMC_PEDI_COD_PEDIDO cod_pedido, CASE WHEN COMI_ID_MONEDA = 1 THEN COMC_IMPORTE_TOTAL_SOLES ELSE COMC_IMPORTE_TOTAL_USD END moneda FROM db_wincrm_prod.CRM_COMPROBANTE_FACT;');
select *
from data_ultra_proc_detalle_pr
where cod_circuito in ( '79710', '36957', '49101', '37720', '37671', '44205', '189261', '82327', '54231', '37893')


select d.nro_documento
from data_ultra_proc_detalle_pr p
inner join data_ultra_procesado_prod d on p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
inner join #tab_compro pedi on d.cod_pedido_pf_ultra = pedi.cod_pedido




select * from data_ultra_proc_detalle_pr where cod_circuito = 189261

SELECT * FROM data_ultra_proc_detalle_pr

UPDATE data_ultra_proc_detalle_pr SET fecha_inicio = '2024-12-31'

select distinct desc_oferta, ancho_banda
from data_ultra_procesado_prod

select * from data_ultra_raw

select *
from data_ultra_proc_detalle_pr
where cod_circuito in
(
select cod_circuito -- , desc_oferta, ancho_banda
from data_ultra_procesado_prod
where (CASE 
			WHEN desc_oferta = 'Migración Ultra 600' AND ancho_banda <> '600 Mbps' THEN 1 
			WHEN desc_oferta = 'Migración Ultra 1000 - MPLS' AND ancho_banda <> '1 Gbps' THEN 1 
			WHEN desc_oferta = 'Migración Ultra 600 - MPLS' AND ancho_banda <> '600 Mbps' THEN 1 
			WHEN desc_oferta = 'Migración Ultra 800 - MPLS' AND ancho_banda <> '800 Mbps' THEN 1 
		ELSE 0 END) = 1
		) and desc_concepto not in ('Servicio de Internet Ultra')



select * from data_ultra_proc_detalle_pr where cod_circuito in
(select p.cod_circuito
from data_ultra_proc_detalle_pr p
inner join data_ultra_procesado_prod d on p.cod_circuito = d.cod_circuito and p.cod_pedido_ultra = d.cod_pedido_ultra
where p.desc_concepto like '%a Ultra 800'
);


select * from data_ultra_emision_prod where desc_observacion = 'No tiene registro en data_ultra_raw'
select * from data_ultra_raw


select * from data_ultra_emision_prod where desc_observacion <> 'ok' and es_sva = 0 
and cod_circuito = 0

select * from data_ultra_emision_prod where cod_circuito = 216723
select * from data_ultra_proc_detalle_pr where cod_circuito = 216723
select * from data_ultra_procesado_prod where cod_circuito = 216723

select sva_cir_codigo, desc_observacion, SUMA_RECURRENTE, desc_moneda 
from data_ultra_emision_prod where es_sva = 1 and sva_cir_codigo in ('53578', '55255', '64928', '100705', '189261', '209538', '48694', '226535')

select * from data_ultra_procesado_prod where cod_circuito in (
select sva_cir_codigo from data_ultra_emision_prod where es_sva = 1
)  


select * into data_ultra_proc_detalle_pr from data_ultra_proc_detalle


select distinct cod_pedido_ultra, cod_circuito from data_ultra_proc_detalle_pr where flg_validacion = 0

select SUMA_RECURRENTE from data_ultra_emision_prod where ID_PEDIDO = 0 and cod_circuito = 0;

select d.cod_pedido_ultra, d.cod_circuito, desc_concepto, cod_moneda, monto, p.ecom_id_servicio
from data_ultra_proc_detalle_pr d
inner join data_ultra_procesado_prod p on d.cod_circuito = p.cod_circuito and d.cod_pedido_ultra = p.cod_pedido_ultra
where d.flg_validacion = 0 and d.cod_circuito = 29640 and d.cod_pedido_ultra = 0

select cod_pedido_ultra, cod_circuito, desc_concepto, cod_moneda, monto from data_ultra_proc_detalle_pr where flg_validacion = 0

alter table data_ultra_proc_detalle_pr add flg_validacion TINYINT NOT NULL DEFAULT 0, desc_validacion VARCHAR(200) not null default '';
alter table data_ultra_proc_detalle_pr add flg_validacion_plan TINYINT NOT NULL DEFAULT 0, desc_validacion VARCHAR(200) not null default '';

SELECT * INTO data_ultra_proc_detalle_pr_bk1 from data_ultra_proc_detalle_pr

select distinct cod_circuito from data_ultra_proc_detalle_pr
-- truncate table data_ultra_proc_detalle_pr

	SELECT nro_documento, cod_circuito, cod_pedido_ultra,
	desc_direccion, desc_latitud, desc_longitud, desc_distrito, desc_provincia, desc_region, desc_ubigeo 
	FROM data_ultra_procesado_prod order by id_data

select * from data_raw_ultra_bk2
select * from data_ultra_raw -- -12.103031	-77.047382

select desc_latitud, desc_longitud, ubigeo, desc_distrito, desc_provincia, desc_region
from data_raw_ultra_bk2
where cod_pedido_ultra = 0

select * into data_ultra_procesado_prod_bk4 from data_ultra_procesado_prod

ALTER TABLE data_ultra_procesado_prod ADD desc_ubigeo varchar(10) NOT NULL DEFAULT ''

UPDATE data_ultra_procesado_prod SET desc_direccion = ''

SELECT d.id_data, d.cod_circuito, flg_config_address, d.cod_pedido_ultra, d.desc_direccion, r.Direccion, s.SERV_DIRECCION
FROM data_ultra_procesado_prod d
INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
INNER JOIN PE_OPTICAL_ADM_PROD_20250103_103852.ECOM.ECOM_SERVICIO s ON s.SERI_ID_SERVICIO = d.ecom_id_servicio
WHERE flg_config_address = 0
order by d.id_data

SELECT desc_direccion, cod_circuito, cod_pedido_ultra FROM data_ultra_procesado_prod
SELECT Direccion, CircuitoCod, IdPedido FROM data_ultra_raw



---------------------------------------------------------------------------------


SELECT d.cod_circuito, d.cod_pedido_ultra,
d.id_data, d.flg_nombre_validado, d.flg_check_nom_v2, CC.CLIV_NRO_RUC, CC.CLIV_RAZON_SOCIAL, 
d.nro_documento,
CASE WHEN d.razon_social = '' then d.nombres else d.razon_social end nombres_prod,
CASE WHEN d.razon_social = '' THEN d.ape_paterno else '.' end ape_paterno,
CASE WHEN d.razon_social = '' THEN d.ape_materno else '.' end ape_materno,
e.cli_nro_doc, e.desc_cliente
FROM data_ultra_procesado_prod d
INNER JOIN data_ultra_raw r ON d.cod_circuito = r.CircuitoCod OR d.cod_pedido_ultra = (CASE WHEN r.IdPedido = '-' THEN -1 ELSE r.IdPedido END)
LEFT JOIN data_ultra_emision_prod e ON d.cod_circuito = e.cod_circuito and d.cod_pedido_ultra = e.ID_PEDIDO
INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CONTRATO CO ON d.ecom_id_contrato = CO.CONI_ID_CONTRATO
INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_EMPRESA_CLIENTE EP ON CO.EMCI_ID_EMP_CLI = EP.EMCI_ID_EMP_CLI
INNER JOIN PE_OPTICAL_ADM_PORTAL.ECOM.ECOM_CLIENTE CC ON EP.CLII_ID_CLIENTE = CC.CLII_ID_CLIENTE
WHERE d.flg_nombre_validado = 0 and d.nro_documento = '20604806152'

select * from data_ultra_procesado_prod where nro_documento = '20604806152'



-- El comprobante del 12/2024 no esta cobrado
-- El documento no es RUC para que este asociado a una Empresa -- 06383156
-- No tiene comprobante en el 12/2024
-- NO HABILITADO
-- HABILITADO

-- UPDATE data_ultra_procesado_prod SET desc_activacion_habil = '', desc_observacion_activacion = '';

select * from data_ultra_raw where RUC like '%281151%'

select TOP 10 C.COMC_COD_COMPROBANTE, C.COMV_PERIODO_COMPROBANTE PERIODO, 
-- COMC_DOC_NUMERO, C.COMC_IMPORTE_SOLES IMP_SOL,COMC_IMPORTE_TOTAL_SOLES TOTAL_SOL, CD.COMD_IMPORTE_TOTAL_SOL,
COMC_DOC_NUMERO, C.COMC_IMPORTE_USD IMP_USD,COMC_IMPORTE_TOTAL_USD TOTAL_USD, CD.COMD_IMPORTE_TOTAL_USD,
CD.COMD_DES_CONCEPTO, CD.SERI_ID_SERVICIO, CD.SDEI_ID_SERVICIO_DETALLE, CD.*
from PE_OPTICAL_ADM_PROD_20250103_103852.ECOM.COMPROBANTE C
INNER JOIN PE_OPTICAL_ADM_PROD_20250103_103852.ECOM.COMPROBANTE_DET CD ON C.COMC_COD_COMPROBANTE = CD.COMC_COD_COMPROBANTE
INNER JOIN PE_OPTICAL_ADM_PROD_20250103_103852.ECOM.ECOM_CLIENTE CLI ON C.COMC_COD_ENTIDAD = CLI.CLII_ID_CLIENTE
WHERE CLI.CLIV_NRO_RUC = 'F60717293' AND C.COMV_PERIODO_COMPROBANTE > '202409' 
ORDER BY C.COMV_PERIODO_COMPROBANTE, C.COMC_COD_COMPROBANTE, CD.COMD_IMPORTE_TOTAL_SOL	


-- update data_ultra_emision_prod set flg_status_habil = 1, cod_circuito = 0, desc_observacion = '', es_sva = 0, sva_cir_codigo = 0 where ID_PEDIDO = 0

SELECT * FROM data_ultra_emision_prod where cli_nro_doc like '%281151%'
SELECT * FROM data_ultra_procesado_prod where nro_documento like '%08800237%'
SELECT * FROM data_ultra_raw

-- -------------------------------------------------------------------------

UPDATE data_ultra_emision_prod SET desc_observacion = 'Ciercuito Baja', flg_status_habil = 0 where compro_nro_doc = 'S002-00039989';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 48694 where compro_nro_doc = 'S002-00039835';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 38748 where compro_nro_doc = 'S002-00039643';
UPDATE data_ultra_emision_prod SET desc_observacion = 'En data_raw esta mal la renta mensual', cod_circuito = 79710 where compro_nro_doc = 'S002-00040276';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 37906 where compro_nro_doc = 'S002-00039617';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 100705 where compro_nro_doc = 'S002-00040128';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA - Alquiler de Equipo ROUTER EERO PRO (ROUTER/EXTENDER)', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 49410 where compro_nro_doc = 'S002-00040267';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 184441 where compro_nro_doc = 'S002-00040185';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 216871 where compro_nro_doc = 'S002-00040251';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta - OT de Baja', cod_circuito = 54897 where compro_nro_doc = 'S002-00039918';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 66497 where compro_nro_doc = 'S002-00040291';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 55255 where compro_nro_doc = 'S002-00039923';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 50978 where compro_nro_doc = 'S002-00039871';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de Baja', cod_circuito = 42075 where compro_nro_doc = 'S002-00039697';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 189261 where compro_nro_doc = 'S002-00040203';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 189261 where compro_nro_doc = 'S002-00040204';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 81137 where compro_nro_doc = 'S002-00040061';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de Baja', cod_circuito = 59826 where compro_nro_doc = 'S002-00039974';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 209538 where compro_nro_doc = 'S002-00040244';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Tiene OT de decremento de renta', cod_circuito = 75056 where compro_nro_doc = 'S002-00040027';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 64928 where compro_nro_doc = 'S002-00039983';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA - Ultra AP EERO Pro', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 53578 where compro_nro_doc = 'S002-00039888';
UPDATE data_ultra_emision_prod SET desc_observacion = 'Es SVA', flg_status_habil = 0, es_sva = 1, sva_cir_codigo = 226535 where compro_nro_doc = 'S002-00040259';
update data_ultra_emision_prod set desc_observacion = 'ok' where ID_PEDIDO <> 0;

-- -------------------------------------------------------------------------


select * from data_ultra_procesado_uat order by id_data
SELECT * FROM data_ultra_procesado_prod order by id_data
SELECT * FROM data_ultra_procesado_prod WHERE nro_documento = '000733531'
SELECT * FROM data_ultra_procesado_prod WHERE status_ingreso_venta NOT in (10) order by id_data
SELECT * FROM data_ultra_procesado_prod WHERE status_ingreso_venta in (1) order by nro_documento
 
SELECT id_data, nro_documento, status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_prod p WHERE status_ingreso_venta in (10) order by p.nro_documento
SELECT id_data, nro_documento, status_ingreso_venta, status_resultado, * FROM data_ultra_procesado_prod p where p.status_ingreso_venta not in ('10', 1)  order by p.updated_at

select * from data_ultra_procesado_prod where 
status_resultado = 'Error en los nombres' and status_ingreso_venta = 23


SELECT id_data, nro_documento, status_ingreso_venta, status_resultado,
ape_paterno, ape_materno, nombres, tipo_documento,
* FROM data_ultra_procesado_prod WHERE nro_documento = '20603206925'

update data_ultra_procesado_prod set flg_check_nombres = 1, status_ingreso_venta = 1 where status_resultado = 'Error en los nombres' and status_ingreso_venta = 23


UPDATE data_ultra_procesado_prod SET desc_latitud = '-12.151686044989946', desc_longitud = '-77.0228522552664' where id_data = 284


update data_ultra_procesado_prod set status_ingreso_venta = 1 where nro_documento = '20603206925'

SELECT cod_circuito, cod_pedido_ultra, nro_documento, 
case when razon_social = '' then CONCAT(ape_paterno, ' ', ape_materno, ' ', nombres) else razon_social end nombres, 
desc_correo, desc_celular, desc_celular2 FROM data_ultra_procesado_prod p 

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








