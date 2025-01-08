SELECT dir.des_direccion, dir.sed_map_x, dir.sed_map_y, dir.dir_ubigeo,
ub1.descripcion distrito, ub2.descripcion provincia, ub3.descripcion region
FROM noc_circuitos cir 
inner join coti_mae_sede dir on dir.id_sede = cir.cir_direccion_id
INNER JOIN opti_ubigeo ub1 on dir.dir_ubigeo = ub1.ubigeo_id and ub1.nivel = 4
INNER JOIN opti_ubigeo ub2 on ub1.ubigeo_padre = ub2.ubigeo_id
INNER JOIN opti_ubigeo ub3 on ub2.ubigeo_padre = ub3.ubigeo_id
where cir.cir_codigo = 34148;

-- update noc_circuitos set where cir_codigo = 37716;
update coti_mae_sede set dir_ubigeo = '00150140' where id_sede in (
select cir.cir_direccion_id from noc_circuitos cir where cir.cir_codigo = 37716);

select ot.* 
from opti_orden_trabajo ot
inner join noc_circuitos cir on cir.cir_codigo = ot.cir_codigo
where cir.cir_codigo = 45664 or cir.cir_padre_id  = 45664;

select * from opti_orden_servicio limit 10


select * from opti_usuarios

update opti_usuarios set user_passwd = 'Optica!@!#@dede213212'

 select * from noc_circuitos where cir_codigo = 180381;