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
class InformeCalculoNotas extends \yii\db\ActiveRecord {

    /**
     * TOMA LAS MALLAS DE LAS AREAS
     * @param type $paralelo
     * @param type $periodoCodigo
     * @return type
     */
    public function get_malla_areas_paralelo($paralelo, $periodoCodigo){
        $con = \Yii::$app->db;
        $query = "select 	ma.id
                                ,a.name
                                ,ma.se_imprime
                                ,ma.promedia
                from	op_student_inscription i
                                left join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                left join scholaris_clase c on c.id = g.clase_id
                                left join scholaris_malla_materia mm on mm.id = c.malla_materia
                                left join scholaris_malla_area ma on ma.id = mm.malla_area_id
                                left join scholaris_area a on a.id = ma.area_id
                where	i.parallel_id = $paralelo		
                                and c.periodo_scholaris = '$periodoCodigo'
                                and ma.tipo not in ('COMPORTAMIENTO','PROYECTOS')
                group by ma.id
                                ,a.name
                order by ma.orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    public function get_malla_materias_paralelo($paralelo, $periodoCodigo, $mallaAreaId){
        $con = \Yii::$app->db;
        $query = "select 	m.name		
                                    ,mm.total_porcentaje
                                    ,mm.promedia
                                    --,f.last_name
                                    --,f.x_first_name
                                    ,m.id as materia_id
                                    --,c.id
                    from	op_student_inscription i
                                    left join scholaris_grupo_alumno_clase g on g.estudiante_id = i.student_id
                                    left join scholaris_clase c on c.id = g.clase_id
                                    left join scholaris_malla_materia mm on mm.id = c.malla_materia
                                    left join scholaris_materia m on m.id = mm.materia_id
                                    left join op_faculty f on f.id = c.idprofesor
                    where	i.parallel_id = $paralelo		
                                    and c.periodo_scholaris = '$periodoCodigo'
                                    and mm.tipo not in ('COMPORTAMIENTO','PROYECTOS')
                                    and mm.malla_area_id = $mallaAreaId
                    group by m.name, mm.orden
                                    ,mm.total_porcentaje
                                    ,mm.promedia
                                    --,f.last_name
                                    --,f.x_first_name
                                    ,m.id
                                    --,c.id                                    
                    order by mm.orden;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    /**
     * ALUMNOS DEL PARALELO
     * @param type $paralelo
     * @return type
     */
    public function get_alumnos($paralelo){
        $modelAlmunos = OpStudent::find()
                ->innerJoin("op_student_inscription", "op_student_inscription.student_id = op_student.id")
                ->where([
                    'op_student_inscription.parallel_id' => $paralelo,
                    'op_student_inscription.inscription_state' => 'M'
                ])
                ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name")
                ->all();
        
        return $modelAlmunos;
    }
    
    
    
    public function calcula_nota_proyectos($alumno, $materiaId, $periodo) {

        $con = \Yii::$app->db;

        $query = "select 	l.p1,l.p2,l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1
                               ,l.p4,l.p5,l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal
            
from 	scholaris_grupo_alumno_clase g
	 	inner join scholaris_clase c on c.id = g.clase_id
	 	inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodo'
		and c.idmateria = $materiaId;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }
    
    
    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function calcula_nota($alumno, $materiaId, $periodo) {

        $con = \Yii::$app->db;

        $query = "select 	l.p1,l.p2,l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1
                               ,l.p4,l.p5,l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal
                               ,l.mejora_q1, l.mejora_q2
                               ,l.final_con_mejora
                               ,l.supletorio
                               ,l.remedial
                               ,l.gracia
                               ,l.final_total
from 	scholaris_grupo_alumno_clase g
	 	inner join scholaris_clase c on c.id = g.clase_id
	 	inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodo'
		and c.idmateria = $materiaId;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

    public function calcula_promedio($alumno, $periodo) {
        $con = \Yii::$app->db;

        $query = "select 	trunc(avg(l.q1),2) as q1,trunc(avg(l.final_ano_normal),2) as final_ano_normal
from 	scholaris_grupo_alumno_clase g
	 	inner join scholaris_clase c on c.id = g.clase_id
	 	inner join scholaris_clase_libreta l on l.grupo_id = g.id
where 	g.estudiante_id = $alumno
		and c.periodo_scholaris = '$periodo'
		and c.promedia = 1;	";

//        echo $query;
//        die();


        $res = $con->createCommand($query)->queryOne();

        return $res;
    }
    
    
    public function calcula_promedio_materia($paralelo, $materia, $periodo) {
        $con = \Yii::$app->db;

        $query = "select trunc(avg(l.p1),2) as p1 
                        ,trunc(avg(l.p2),2) as p2
                        ,trunc(avg(l.p3),2) as p3
                        ,trunc(avg(l.pr1),2) as pr1
                        ,trunc(avg(l.pr180),2) as pr180
                        ,trunc(avg(l.ex1),2) as ex1
                        ,trunc(avg(l.ex120),2) as ex120
                        ,trunc(avg(l.q1),2) as q1
                        ,trunc(avg(l.p4),2) as p4 
                        ,trunc(avg(l.p5),2) as p5
                        ,trunc(avg(l.p6),2) as p6
                        ,trunc(avg(l.pr2),2) as pr2
                        ,trunc(avg(l.pr280),2) as pr280
                        ,trunc(avg(l.ex2),2) as ex2
                        ,trunc(avg(l.ex220),2) as ex220
                        ,trunc(avg(l.q2),2) as q2
                        ,trunc(avg(l.final_ano_normal),2) as final_ano_normal
from 	scholaris_clase c
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id
		inner join op_student_inscription i on i.student_id = g.estudiante_id
							and c.paralelo_id = i.parallel_id
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
where	c.paralelo_id = $paralelo
		and c.idmateria = $materia
		and c.periodo_scholaris = '$periodo'
		and i.inscription_state = 'M';";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }
    
    
    public function calcula_promedio_paralelo($paralelo, $peridoCodigo){
        $con = \Yii::$app->db;

//        $query = "select 	trunc((avg(q1)),2) as q1
//		,trunc((avg(q2)),2) as q2
//		,trunc((avg(final_ano_normal)),2) as final_ano_normal
//from(
//                select 	trunc(avg(l.q1),2) as q1 
//		,trunc(avg(l.q2),2) as q2 
//		,trunc(avg(l.final_ano_normal),2) as final_ano_normal 
//from 	scholaris_clase c 
//		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
//		inner join op_student_inscription i on i.student_id = g.estudiante_id 
//		and c.paralelo_id = i.parallel_id 
//		inner join scholaris_clase_libreta l on l.grupo_id = g.id 
//where	c.paralelo_id = $paralelo
//		and c.promedia = 1 
//		and c.periodo_scholaris = '$peridoCodigo' 
//		and i.inscription_state = 'M' group by i.student_id) as q1;";
        
        $query = "select 	trunc(avg(l.q1),2) as q1 ,trunc(avg(l.q2),2) as q2 ,trunc(avg(l.final_ano_normal),2) as final_ano_normal 
from 	scholaris_clase c 
		inner join scholaris_grupo_alumno_clase g on g.clase_id = c.id 
		inner join op_student_inscription i on i.student_id = g.estudiante_id and c.paralelo_id = i.parallel_id 
		inner join scholaris_clase_libreta l on l.grupo_id = g.id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
where	c.paralelo_id = $paralelo 
		and mm.promedia = true 
		and c.periodo_scholaris = '$peridoCodigo' 
		and i.inscription_state = 'M';";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }
    
    
    
    public function homologa_promedio($nota) {

        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);

        $con = Yii::$app->db;
        $query = "select 	abreviatura, descripcion 
                    from 	scholaris_tabla_escalas_homologacion
                    where	corresponde_a = 'APROVECHAMIENTO'
                                    and scholaris_periodo = '$modelPeriodo->codigo'
                                    and $nota between rango_minimo and rango_maximo;";
        $res = $con->createCommand($query)->queryOne();

        return $res;
    }

}
