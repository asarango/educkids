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
class SentenciasPadre extends \yii\db\ActiveRecord {

    public function get_mis_hijos($usuario, $periodo){
        
        $con = \Yii::$app->db;
        
        $query = "select st.id
		,st.last_name
		,st.first_name
		,st.middle_name
		,pa.name as paralelo
		,pa.id as paralelo_id
		,c.name as curso
		,se.name as seccion
		,se.code
from	res_users u
		inner join op_parent p on p.name = u.partner_id
		inner join op_parent_op_student_rel s on s.op_parent_id = p.id
		inner join op_student st on st.id = s.op_student_id
		inner join op_student_inscription i on i.student_id = st.id
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
		inner join scholaris_periodo sp on sp.id = sop.scholaris_id
		inner join op_course_paralelo pa on pa.id = i.parallel_id
		inner join op_course c on c.id = pa.course_id
		inner join op_section se on se.id = c.section
where	login = '$usuario'
		and sp.id = $periodo;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
        
    }
    
    public function actividades_hijo($alumno, $periodo, $tiempo){
        $con = \Yii::$app->db;
        $fechaIni = date('Y-m-d 00:00:00');
        $fechaFin = date('Y-m-d 23:59:59');
        
        if($tiempo == 'anterior'){
            $condicion = " and a.inicio < '$fechaIni' "; 
        }elseif($tiempo == 'futuro'){
            $condicion = " and a.inicio > '$fechaFin' "; 
        }else{
            $condicion = " and a.inicio between '$fechaIni' and '$fechaFin' "; 
        }
        
        $query = "select b.name as bloque
                ,m.name as materia
		,a.bloque_actividad_id
		,c.id
		,t.nombre_nacional
		,a.id as actividad_id
		,a.title
		,a.inicio
		,a.calificado
		,cal.calificacion
                ,s.nombre_semana
                ,cal.observacion
                ,a.videoconfecia
from	scholaris_actividad a
		inner join scholaris_clase c on c.id = a.paralelo_id
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
		inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
		left join scholaris_calificaciones cal on cal.idactividad = a.id
					and cal.idalumno = g.estudiante_id
		inner join scholaris_tipo_actividad t on t.id = a.tipo_actividad_id
                inner join scholaris_materia m on m.id = c.idmateria
                left join scholaris_bloque_semanas s on s.id = a.semana_id
where	c.periodo_scholaris = '$periodo'
		and g.estudiante_id = $alumno
                $condicion
order by a.inicio asc, b.orden desc;";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    public function comportamiento_hijo($alumno, $periodo){
        $con = \Yii::$app->db;
        
        $query = "select a.fecha
		,m.name as materia
		,f.last_name
		,f.x_first_name
		,com.nombre as tipo
		,det.codigo
		,det.nombre as detalle
		,j.motivo_justificacion
                ,n.observacion 
from 	scholaris_asistencia_alumnos_novedades n
		inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id
		inner join scholaris_materia m on m.id = c.idmateria
		inner join op_faculty f on f.id = c.idprofesor
		inner join scholaris_asistencia_comportamiento_detalle det on det.id = n.comportamiento_detalle_id
		inner join scholaris_asistencia_comportamiento com on com.id = det.comportamiento_id
		left join scholaris_asistencia_justificacion_alumno j on j.novedad_id = n.id
where	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodo'
order by a.fecha desc;";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

}
