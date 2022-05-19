<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class AlumnoNotasNormales extends \yii\db\ActiveRecord {
    
    public function get_nota_area($areaId, $alumnoId, $paraleloId, $usuario){
        $con = Yii::$app->db;
        $query = "select 	usuario, alumno_id, paralelo_id, area_id, area, total_porcentaje, promedia, se_imprime, p1, p2, p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal, mejora_q1, mejora_q2, final_con_mejora, supletorio, remedial, gracia, final_total 
                    from	scholaris_proceso_areas_calificacion_normal
                    where	area_id = $areaId
                                    and alumno_id = $alumnoId
                                    and paralelo_id = $paraleloId
                                    and usuario = '$usuario'; ";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    /***
     * toma las notas de la asignatura del alumno pero por grupoId
     */
    public function get_nota_materia($grupo_id){
        $con = Yii::$app->db;
        $query = "select id, grupo_id, p1, p2, p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal, mejora_q1, mejora_q2, final_con_mejora, supletorio, remedial, gracia, final_total, estado 
                  from scholaris_clase_libreta where grupo_id = $grupo_id;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    /***
     * toma el promedio del alumno
     */
    public function get_promedio_alumno($alumnoId, $paraleloId, $usuarioId){
        $con = Yii::$app->db;
        $query = "select 	usuario, alumno_id, paralelo_id, p1, p2, p3, pr1, pr180, ex1, ex120, q1, p4, p5, p6, pr2, pr280, ex2, ex220, q2, final_ano_normal, mejora_q1, mejora_q2, final_con_mejora, supletorio, remedial, gracia, final_total 
                    from 	scholaris_proceso_promedios_calificacion_normal
                    where 	alumno_id = $alumnoId
                                    and usuario = '$usuarioId'
                                    and paralelo_id = $paraleloId;";
        $res = $con->createCommand($query)->queryOne();
        return $res;
    }
    
    
    
    /***
     * Toma el promedio de de la materia por parcial por paralelo
     */
    public function get_promedio_materia($materiaId, $paraleloId, $periodoId, $parcial){
        $con = \Yii::$app->db;
        $query = "select 	round(avg($parcial),2) as nota  
from	scholaris_clase_libreta l 
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id 
		inner join scholaris_clase c on c.id = g.clase_id
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
where 	c.idmateria = $materiaId
		and c.paralelo_id = $paraleloId
		and sop.scholaris_id = $periodoId
		and i.inscription_state = 'M';";
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;
        return $nota;
        
    }
    
    
    /****
     * toma promedio final del paralelo por parcial
     */
    
    public function get_promedio_paralelo_parcial($paraleloId, $usuario, $parcial){
        $con = \Yii::$app->db;
        $query = "select 	round(avg(p4),2) as nota  
	from 	scholaris_proceso_promedios_calificacion_normal p
	where 	paralelo_id = $paraleloId
			and usuario = '$usuario';";
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;
        return $nota;
    }
}
