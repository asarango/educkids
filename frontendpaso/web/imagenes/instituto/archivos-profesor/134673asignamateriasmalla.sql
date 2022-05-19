update scholaris_clase
set		malla_materia = (select ma.id
										from	scholaris_malla_area ar
												inner join scholaris_malla_materia ma on ma.malla_area_id = ar.id
												inner join scholaris_materia mat on mat.id = ma.materia_id
										where	ar.malla_id = 3
												and mat.name ilike scholaris_materia.name
										)
from 	scholaris_materia		
where	scholaris_materia.id = scholaris_clase.idmateria 
		and scholaris_clase.idcurso = 399;