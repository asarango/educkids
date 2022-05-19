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
class AlumnoNotasInterdisciplinar extends \yii\db\ActiveRecord {
    
    private $paraleloId;
    private $alumnoId;
    
    public function get_nota_area($areaId, $alumnoId, $paraleloId, $usuario){
        
        $interdisciplinar = new ProcesaNotasInterdisciplinar($paraleloId, $alumnoId);
        return $interdisciplinar->arrayNotas;
        
    }
    
    
    /***
     * toma las notas de la asignatura del alumno pero por grupoId
     */
    public function get_nota_materia($grupoId){
        
        $grupo = ScholarisGrupoAlumnoClase::findOne($grupoId);
        $arreglo = array();
        
        $interdisciplinar = new ProcesaNotasInterdisciplinar($grupo->clase->paralelo_id, $grupo->estudiante_id);
        
        return $interdisciplinar->arrayNotas[0];
    }
    
    
    /***
     * toma el promedio del alumno
     */
    public function get_promedio_alumno($alumnoId, $paraleloId, $usuarioId){
        $interdisciplinar = new ProcesaNotasInterdisciplinar($paraleloId, $alumnoId);
        
        return $interdisciplinar->arrayNotas[0];
    }
    
    
    
    /***
     * Toma el promedio de de la materia por parcial por paralelo
     */
    public function get_promedio_materia($materiaId, $paraleloId, $periodoId, $parcial){
        
        switch ($parcial){
            case 'p1':
                $orden = 1;
                break;
            
            case 'p2':
                $orden = 2;
                break;
            
            case 'p3':
                $orden = 3;
                break;
            
            case 'ex1':
                $orden = 4;
                break;
                        
            
        }
        
        
        $con = \Yii::$app->db;        
        $query = "select round(avg(nota),2) as nota
                from (
                select 	sum(c.nota) as nota		 
                from 	scholaris_calificaciones_parcial c
                                inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id 
                                inner join scholaris_clase cla on cla.id = g.clase_id 
                                inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                                inner join op_student_inscription i on i.student_id = g.estudiante_id 
                                inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                                inner join op_student s on s.id = i.student_id 
                                inner join scholaris_malla_materia mm on mm.id = cla.malla_materia 
                where 	cla.paralelo_id = $paraleloId
                                and b.orden = $orden
                                and sop.scholaris_id = $periodoId
                                and i.inscription_state = 'M'
                                and mm.tipo = 'COMPORTAMIENTO'
                group by g.id
                ) as nota ";
        $res = $con->createCommand($query)->queryOne();
        isset($res['nota']) ? $nota = $res['nota'] : $nota = 0;
        return $nota;
        
    }
    
    
    /****
     * toma promedio final del paralelo por parcial
     */
    
    public function get_promedio_paralelo_parcial($paraleloId, $usuario, $parcial){
        
        return 1000;
    }
    
    
    

}
