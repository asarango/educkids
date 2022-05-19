<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $codigo
 * @property string $nombre
 * @property int $orden
 *
 * @property Operacion[] $operacions
 */
class SentenciasLeccionario extends \yii\db\ActiveRecord {
    
    
    public function get_clases_fecha($paralelo, $diaNumero){
        $con = Yii::$app->db;
        $query = "select 	ho.sigla
		,ho.desde
		,ho.hasta
                ,ho.id as hora_id
		,mat.name as materia		
		,f.last_name
		,f.x_first_name
                ,c.id as clase_id
from	scholaris_horariov2_horario h
		inner join scholaris_clase c on c.id = h.clase_id
		inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
		inner join scholaris_horariov2_dia di on di.id = d.dia_id
		inner join scholaris_horariov2_hora ho on ho.id = d.hora_id
		inner join scholaris_materia mat on mat.id = c.idmateria
		inner join op_faculty f on f.id = c.idprofesor
where	c.paralelo_id = $paralelo
		and di.numero = $diaNumero
order by ho.numero;";     
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();     
        return $res;
    }
    
    public function get_estudiantes($fecha, $paralelo){
        $con = Yii::$app->db;
        $query = "select 	ho.sigla
		,m.name as materia
		,f.last_name
		,f.x_first_name
		,s.last_name as est_apellido
		,s.first_name as est_nombre1
		,s.middle_name as est_nombre2
		,com.nombre
                ,com.codigo
                ,t.nombre as comportamiento
                ,n.id as novedad_id
                ,n.observacion
from 	scholaris_asistencia_profesor p
		inner join scholaris_clase c on c.id = p.clase_id
		inner join scholaris_horariov2_hora ho on ho.id = p.hora_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join op_faculty f on f.id = c.idprofesor
		inner join scholaris_asistencia_alumnos_novedades n on n.asistencia_profesor_id = p.id
		inner join scholaris_grupo_alumno_clase gru on gru.id = n.grupo_id
		inner join op_student s on s.id = gru.estudiante_id
		inner join scholaris_asistencia_comportamiento_detalle com on com.id = n.comportamiento_detalle_id
                inner join scholaris_asistencia_comportamiento t on t.id = com.comportamiento_id 
where	p.fecha = '$fecha'
		and c.paralelo_id = $paralelo
order by ho.numero;";          
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();     
        return $res;
    }
    
    
    public function get_temas($clase,$fecha){
        $con = Yii::$app->db;
        $query = "select 	t.tema
                    from 	scholaris_asistencia_clase_tema t
                                    inner join scholaris_asistencia_profesor a on a.id = t.asistencia_profesor_id
                    where	t.clase_id = $clase
                                    and a.fecha = '$fecha';";          
        $res = $con->createCommand($query)->queryAll();     
        return $res;
    }
    
    
}
