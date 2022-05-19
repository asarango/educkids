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
class SentenciasAlumnos extends \yii\db\ActiveRecord {
    
    
    public function get_alumnos_paralelo($paralelo){
        $con = Yii::$app->db;
        $query = "select 	s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,i.id as inscription_id
                                    ,inscription_state
                                    ,p.numero_identificacion
                    from	op_student_inscription i
                                    inner join op_student s on s.id = i.student_id
                                    inner join res_partner p on p.id = s.partner_id
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                    order by s.last_name, s.first_name, s.middle_name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function get_alumnos_paralelo_alumno($paralelo, $alumnoId){
        $con = Yii::$app->db;
        $query = "select 	s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,i.id as inscription_id
                                    ,p.numero_identificacion
                    from	op_student_inscription i
                                    inner join op_student s on s.id = i.student_id
                                    inner join res_partner p on p.id = s.partner_id
                    where	i.parallel_id = $paralelo
                                    and i.inscription_state = 'M'
                                    and i.student_id = $alumnoId
                    order by s.last_name, s.first_name, s.middle_name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function get_alumnos_paralelo_todos($paralelo){
        $con = Yii::$app->db;
        $query = "select 	s.id
                                    ,s.last_name
                                    ,s.first_name
                                    ,s.middle_name
                                    ,inscription_state
                                    ,i.inscription_state
                    from	op_student_inscription i
                                    inner join op_student s on s.id = i.student_id
                    where	i.parallel_id = $paralelo
                                and i.inscription_state in ('M','R')
                                --and i.inscription_state in ('M')
                    order by s.last_name, s.first_name, s.middle_name;";        
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    public function get_alumno_inscription($alumno){
        $periodoId = Yii::$app->user->identity->periodo_id;
        
        $con = Yii::$app->db;
        $query = "select 	i.id as inscripcion_id
		,s.id as estudiante_id
from 	op_student s
		inner join op_student_inscription i on i.student_id = s.id
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
		inner join scholaris_periodo p on p.id = sop.scholaris_id 
where 	s.id = $alumno
		and p.id = $periodoId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
        
    }
    
    public function get_grupo($alumnoId, $materiaId){
        $periodoId = Yii::$app->user->identity->periodo_id;
        $con = Yii::$app->db;
        $query = "select 	g.id 
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase c on c.id = g.clase_id
		inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
where	g.estudiante_id = $alumnoId
		and c.idmateria = $materiaId
		and p.id = $periodoId;";
        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['id'])){
            $grupoId = $res['id'];
        }else{
            $grupoId = false;
        }
        
        return $grupoId;
    }
    
    public function get_paralelo_id_periodoScholaris($alumnoId, $periodoScholaris){
        $con = Yii::$app->db;
        $query = "select 	c.id, c.paralelo_id 
from  	op_student s 
		inner join scholaris_grupo_alumno_clase g on g.estudiante_id = s.id
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_periodo p on p.codigo = c.periodo_scholaris 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
where	s.id = $alumnoId
		and p.id = $periodoScholaris
		and mm.tipo = 'COMPORTAMIENTO';";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
  
    public function get_estudiantes_x_instituto_periodo($periodoId, $institutoId){
        $con = Yii::$app->db;
        $query = "select 	s.id 
                                    ,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student 
                                    ,i.course_id 
                                    ,i.parallel_id 
                    from	op_student s
                                    inner join op_student_inscription i on i.student_id = s.id 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id  = i.period_id 
                    where 	sop.scholaris_id = $periodoId
                                    and x_institute = $institutoId
                    order by s.last_name asc, s.first_name asc, s.middle_name asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function get_estudiantes_x_instituto_periodo_x_id($periodoId, $institutoId, $id){
        $con = Yii::$app->db;
        $query = "select 	s.id 
                                    ,concat(s.last_name, ' ', s.first_name, ' ', s.middle_name) as student 
                                    ,i.course_id 
                                    ,i.parallel_id 
                    from	op_student s
                                    inner join op_student_inscription i on i.student_id = s.id 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id  = i.period_id 
                    where 	sop.scholaris_id = $periodoId
                                    and x_institute = $institutoId
                                    and s.id = $id
                    order by s.last_name asc, s.first_name asc, s.middle_name asc;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    
    
}
