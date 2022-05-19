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
class SentenciasClase extends \yii\db\ActiveRecord {
    
    
    public function get_alumnos_clase($clase, $periodo){
        $con = Yii::$app->db;
        $query = "select g.id as grupo_id
                                    ,s.id as alumno_id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,cur.name as curso
                                    ,i.parallel_id
                                    ,p.name as paralelo
                                    ,i.inscription_state
                                    ,i.id as inscription_id
                    from	op_student_inscription i
                                    inner join op_student s on s.id = i.student_id
                                    inner join scholaris_grupo_alumno_clase g on g.estudiante_id = s.id
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                                    inner join scholaris_periodo sp on sp.id = sop.scholaris_id
                                    inner join op_course_paralelo p on p.id = i.parallel_id
                                    inner join op_course cur on cur.id = p.course_id
                    where	g.clase_id = $clase
                                and sp.id = $periodo
                    order by s.last_name, s.first_name, s.middle_name";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }


    
    public function get_alumnos_curso($clase, $curso){
        $con = Yii::$app->db;
        $query = "select s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,concat(s.last_name,' ',s.first_name) as nombre
                    from	op_student_inscription i
                                    inner join op_course_paralelo p on p.id = i.parallel_id
                                    inner join op_course c on c.id = p.course_id
                                    inner join op_student s on s.id = i.student_id
                    where	p.course_id = $curso
                                    and s.id not in (select estudiante_id from scholaris_grupo_alumno_clase where clase_id = $clase)
                    order by s.last_name
                                    ,s.first_name
                                    ,s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;        
    }

    public function get_alumnos_todos(){
        $con = Yii::$app->db;
        $query = "select s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,concat(s.last_name,' ',s.first_name) as nombre
                    from	op_student_inscription i
                                    inner join op_course_paralelo p on p.id = i.parallel_id
                                    inner join op_course c on c.id = p.course_id
                                    inner join op_student s on s.id = i.student_id
                    order by s.last_name
                                    ,s.first_name
                                    ,s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;        
    }


    
    public function ingresar_alumnos_todos($clase, $paralelo){
        $con = Yii::$app->db;
        $query = "insert into scholaris_grupo_alumno_clase(clase_id, estudiante_id)
                    select $clase, i.student_id
                    from 	op_student_inscription i
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                                    and i.student_id not in (select estudiante_id from scholaris_grupo_alumno_clase where clase_id = $clase);";
        
        $con->createCommand($query)->execute();
        
    }
    
    
    
    public function get_horas_horario($cabecera){
        $con = Yii::$app->db;
        $query = "select hor.id
                                    ,hor.sigla
                    from 	scholaris_horariov2_detalle det
                                    inner join scholaris_horariov2_hora hor on hor.id = det.hora_id
                    where	det.cabecera_id = $cabecera
                    group by hor.id, hor.sigla
                    order by hor.numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res; 
    }
    
    public function get_materia_horario($paralelo, $dia, $hora){
        $con = Yii::$app->db;
        $query = "select h.clase_id
                                ,h.detalle_id
                                ,m.nombre as materia
                from 	scholaris_horariov2_horario h
                                inner join scholaris_clase c on c.id = h.clase_id
                                inner join scholaris_horariov2_detalle det on det.id = h.detalle_id
                                inner join ism_area_materia am on am.id = c.ism_area_materia_id  
                                inner join ism_materia m on m.id = am.materia_id
                where	c.paralelo_id = $paralelo
                                and det.dia_id = $dia
                                and det.hora_id = $hora "
                . "and c.es_activo = true;";
        
        $res = $con->createCommand($query)->queryOne();
        return $res; 
    }
    
    public function quitar_clase_horario($clase, $detalle){
        $con = Yii::$app->db;
        $query = "delete from scholaris_horariov2_horario where detalle_id = $detalle and clase_id = $clase;";
        $con->createCommand($query)->execute();
        
    }
    
    
    public function asignar_clase_horario($clase, $detalle){
        $con = Yii::$app->db;
        $query = "insert into scholaris_horariov2_horario  values($detalle, $clase)";
        $con->createCommand($query)->execute();
        
    }
    
    
    public function get_actividades_calificadas_alumnos($grupo){
        $con = Yii::$app->db;
        $query = "select a.bloque_actividad_id
                                ,a.id
                                ,a.title
                                ,c.calificacion
                                ,g.estudiante_id
                                ,g.clase_id
                                ,b.name as bloque
                from 	scholaris_grupo_alumno_clase g
                                inner join scholaris_actividad a on a.paralelo_id = g.clase_id
                                inner join scholaris_calificaciones c on c.idactividad = a.id
                                                and c.idalumno = g.estudiante_id
                                inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id
                where	g.id = $grupo
                order by b.orden;";
                        $res = $con->createCommand($query)->queryAll();
        return $res; 
    }
    
    public function eliminar_alumno_clase($grupo){
        
        $modelGrupo = ScholarisGrupoAlumnoClase::find()->where(['id' => $grupo])->one();
        
        $con = Yii::$app->db;
        $query1 = "delete from scholaris_clase_libreta where grupo_id = $grupo;";
        

        
        $query2 = "delete from 	scholaris_calificaciones 
                    where	idalumno = $modelGrupo->estudiante_id
                                    and idactividad in (select id from scholaris_actividad where paralelo_id = $modelGrupo->clase_id)";
        
        $query3 = "delete from scholaris_resumen_parciales where clase_id = $modelGrupo->clase_id and alumno_id = $modelGrupo->estudiante_id;";
        
        $query4 = "delete from scholaris_calificaciones_parcial where grupo_id = $grupo;";        
        $eliminaParcialCambios = "delete from scholaris_calificaciones_parcial_cambios where grupo_id = $grupo;";        
        $eliminaDeInicial = "delete from scholaris_calificaciones_inicial where grupo_id = $grupo;";        
        
        $con->createCommand($query1)->execute();
        $con->createCommand($query2)->execute();
        $con->createCommand($query3)->execute();
        $con->createCommand($query4)->execute();
        $con->createCommand($eliminaParcialCambios)->execute();
        $con->createCommand($eliminaDeInicial)->execute();
        $modelGrupo->delete();
        
        
    }
    
    
    public function consulta_materias_normales($alumnoId){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        
        $con = Yii::$app->db;
        
        $query = "select 	c.id, m.name as materia, g.id as grupo_id
from 	scholaris_clase c
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join scholaris_materia m on m.id = mm.materia_id 
where 	c.periodo_scholaris = '$modelPeriodo->codigo'
		and mm.tipo in ('NORMAL','OPTATIVAS')
		and g.estudiante_id = $alumnoId
order by m.name;";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }
    
    
}
