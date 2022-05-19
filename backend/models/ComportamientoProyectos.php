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
class ComportamientoProyectos extends \yii\db\ActiveRecord {

    private $alumno;
    private $paralelo;
    private $modelParalelo;
    private $seccion;
    private $periodoId;
    private $periodoCodigo;
    private $uso;
    private $modelBloquesQ1;
    private $modelBloquesQ2;
    private $totalPacialesQ1;
    private $totalPacialesQ2;
    public $arrayNotasComp = array();
    public $arrayNotasProy = array();
    private $comportamientoAutomatico;
    
    
    public function __construct($alumno, $paralelo) {
        
        $this->alumno = $alumno;
        $this->paralelo = $paralelo;
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        
        $this->seccion = $this->modelParalelo->course->section0->code;
        
        $modelComportamientoParam = ScholarisParametrosOpciones::find()->where(['codigo' => 'comportamiento'])->one();
        $this->comportamientoAutomatico = $modelComportamientoParam->valor;
        
        ////para encontrar periodos
        if(isset(Yii::$app->user->identity->periodo_id)){
            $this->periodoId = Yii::$app->user->identity->periodo_id;
        }else{
            $modelPer = ScholarisPeriodo::find()->orderBy(['id' => SORT_DESC])->one();
            $this->periodoId = $modelPer->id;
        }
        
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        //////FIN DE ENCUENTRA PERIODOS
        
        
        $modelUso = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $this->uso = $modelUso->tipo_usu_bloque;
        $this->modelBloquesQ1 = $this->consulta_bloques_parcial('QUIMESTRE I');
        $this->modelBloquesQ2 = $this->consulta_bloques_parcial('QUIMESTRE II');
        $this->totalPacialesQ1 = count($this->modelBloquesQ1);
        $this->totalPacialesQ2 = count($this->modelBloquesQ2);
        
        
        $this->notas_comportamiento();
        $this->notas_proyectos();
        
    }
    
    
    public function tiene_proyectos(){
        $con = Yii::$app->db;
        $query = "select 	COUNT(p.id) as total
                    from	op_course_paralelo p
                                    inner join scholaris_malla_curso c on c.curso_id = p.course_id 
                                    inner join scholaris_malla_area ma on ma.malla_id = c.malla_id 
                    where 	p.id = $this->paralelo
                                    and ma.tipo = 'PROYECTOS';";
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res['total'];
        
    }
    
    private function consulta_bloques_parcial($quimestre){
        $model = ScholarisBloqueActividad::find()->where([
            'tipo_bloque' => 'PARCIAL',
            'quimestre' => $quimestre,
            'tipo_uso' => $this->uso,
            'scholaris_periodo_codigo' => $this->periodoCodigo
        ])->orderBy('orden')->all();
        
        return $model;
    }
    
    private function notas_comportamiento(){
        
        if($this->comportamientoAutomatico == 0){
            $this->notas_comportamiento_manuales();
        }else{
            $this->notas_comportamiento_automatico();
        }
        
        
    }
    
    private function notas_comportamiento_automatico(){
        $comportamiento = new SentenciasRepLibreta2();
        $compo = $comportamiento->get_notas_finales_comportamiento($this->alumno);
        
        
        isset($compo[0]) ? $p1 = $compo[0] : $p1 = 'SN';
        isset($compo[1]) ? $p2 = $compo[1] : $p2 = 'SN';
        isset($compo[2]) ? $p3 = $compo[2] : $p3 = 'SN';
        
        $q1 = $p3;
        
        isset($compo[3]) ? $p4 = $compo[3] : $p4 = 'SN';
        isset($compo[4]) ? $p5 = $compo[4] : $p5 = 'SN';
        isset($compo[5]) ? $p6 = $compo[5] : $p6 = 'SN';
        
        $q2 = $p6;
        
        array_push($this->arrayNotasComp,array(
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'q1' => $q1,
            'p4' => $p4,
            'p5' => $p5,
            'p6' => $p6,
            'q2' => $q2
        ));
        
        
    }
    
    
    
    private function notas_comportamiento_manuales(){
        $compoP1 = $this->consulta_comportamientos_parciales($this->alumno, 1);
        $compoP2 = $this->consulta_comportamientos_parciales($this->alumno, 2);
        
        if(count($this->modelBloquesQ1) > 2){
            $compoP3 = $this->consulta_comportamientos_parciales($this->alumno, 3);
            $compoQ1 = $compoP3;
        }else{
            $compoP3 = 0;
            $compoQ1 = $compoP2;
        }
        
        $compoP4 = $this->consulta_comportamientos_parciales($this->alumno, 5);
        $compoP5 = $this->consulta_comportamientos_parciales($this->alumno, 6);
        
        if(count($this->modelBloquesQ2) > 2){
            $compoP6 = $this->consulta_comportamientos_parciales($this->alumno, 7);
            $compoQ2 = $compoP6;
        }else{
            $compoP6 = 0;
            $compoQ2 = $compoP5;
        }
        
        array_push($this->arrayNotasComp,array(
            'p1' => $compoP1,
            'p2' => $compoP2,
            'p3' => $compoP3,
            'q1' => $compoQ1,
            'p4' => $compoP4,
            'p5' => $compoP5,
            'p6' => $compoP6,
            'q2' => $compoQ2,
        ));
    }
    
    
    
    
    private function consulta_comportamientos_parciales($alumnoId, $orden) {
        
        $sentencias = new Notas();       
        
        $modelInscription = OpStudentInscription::find()->where([
            'student_id' => $alumnoId,
            'parallel_id' => $this->paralelo
        ])->one();
        
        $con = Yii::$app->db;
        $query = "select 	calificacion 
from 	scholaris_califica_comportamiento c
		inner join scholaris_bloque_actividad b on b.id = c.bloque_id
where	c.inscription_id = $modelInscription->id
		and b.orden = $orden;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['calificacion'])){
            $resCalificacion = $res['calificacion'];
        }else{
            $resCalificacion = 0;
        }
        
        
        $nota = $sentencias->homologa_comportamiento($resCalificacion, $this->seccion);
        
        
        
        return $nota;
        
    }
    
    public function notas_proyectos(){
        
        $sentencias = new SentenciasRepLibreta2();
        
        $con = \Yii::$app->db;
        $query = "select 	l.id, l.grupo_id, l.p1, l.p2, l.p3, l.pr1, l.pr180, l.ex1, l.ex120, l.q1, 
			l.p4, l.p5, l.p6, l.pr2, l.pr280, l.ex2, l.ex220, l.q2, l.final_ano_normal, 
			l.mejora_q1, l.mejora_q2, l.final_con_mejora, l.supletorio, l.remedial, l.gracia, l.final_total, l.estado 
	from 	scholaris_clase_libreta l
			inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
			inner join scholaris_clase c on c.id = g.clase_id
			inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
	where	g.estudiante_id = $this->alumno
			and c.periodo_scholaris = '$this->periodoCodigo'
			and mm.tipo = 'PROYECTOS';";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        isset($res['p2']) ? $p2 = $res['p2'] : $p2 = 0;
        isset($res['p5']) ? $p5 = $res['p5'] : $p5 = 0;
        
        
        if(count($this->modelBloquesQ1)>2){
            if(isset($res['p3'])){
                $p3 = $res['p3'];
            }else{
                $p3 = 0;
            }
            
            $q1 = $p3;
        }else{
            $p3 = 0;
            $q1 = $p2;
        }
        
        if(count($this->modelBloquesQ2)>2){
            if(isset($res['p3'])){
                $p6 = $res['p6'];
            }else{
                $p6 = 0;
            }
            
            $q2 = $p6;
        }else{
            $p6 = 0;
            $q2 = $p5;
        }
              
        if(isset($res['p1'])){
            $p1 = $res['p1'];
        }else{
            $p1 = 0;
        }
        
        if(isset($res['p2'])){
            $p2 = $p2;
        }else{
            $p2 = 0;
        }
        
        if(isset($res['p4'])){
            $p4 = $res['p4'];
        }else{
            $p4 = 0;
        }
        
        if(isset($res['p5'])){
            $p5 = $p5;
        }else{
            $p5 = 0;
        }
        
        $p1 = $sentencias->homologaProyectos($p1);        
        $p2 = $sentencias->homologaProyectos($p2);
        $p3 = $sentencias->homologaProyectos($p3);
        $q1 = $sentencias->homologaProyectos($q1);
        
        $p4 = $sentencias->homologaProyectos($p4);
        $p5 = $sentencias->homologaProyectos($p5);
        $p6 = $sentencias->homologaProyectos($p6);
        $q2 = $sentencias->homologaProyectos($q2);
        
        
        
        
        array_push($this->arrayNotasProy,array(
            'p1' => $p1,
            'p2' => $p2,
            'p3' => $p3,
            'q1' => $q1,
            'p4' => $p4,
            'p5' => $p5,
            'p6' => $p6,
            'q2' => $q2
        ));
        
        
    }
    

    public function get_area_proyectos_bloque(){
        
        $sentencias = new SentenciasRepLibreta2();
        
        $notasProyectos = array();
        
        $con = Yii::$app->db;
        $query = "select 	sum(l.p1 * mm.total_porcentaje / 100 ) as p1
		,sum(l.p2 * mm.total_porcentaje / 100 ) as p2
		,sum(l.p3 * mm.total_porcentaje / 100 ) as p3
		,sum(l.pr1 * mm.total_porcentaje / 100 ) as pr1
		,sum(l.ex1 * mm.total_porcentaje / 100 ) as ex1
		,sum(l.q1 * mm.total_porcentaje / 100 ) as q1
		,sum(l.p4 * mm.total_porcentaje / 100 ) as p4
		,sum(l.p5 * mm.total_porcentaje / 100 ) as p5
		,sum(l.p6 * mm.total_porcentaje / 100 ) as p6
		,sum(l.pr2 * mm.total_porcentaje / 100 ) as pr2
		,sum(l.ex2 * mm.total_porcentaje / 100 ) as ex2
		,sum(l.q2 * mm.total_porcentaje / 100 ) as q2
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join op_student_inscription i on i.student_id = g.estudiante_id
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
where 	g.estudiante_id = $this->alumno
		and i.parallel_id = $this->paralelo
		and c.periodo_scholaris = '$this->periodoCodigo'
		and mm.tipo = 'PROYECTOS';";
        $res = $con->createCommand($query)->queryOne();
        
        $p1 = $sentencias->homologaProyectos($res['p1']);
        $p2 = $sentencias->homologaProyectos($res['p2']);
        $p3 = $sentencias->homologaProyectos($res['p3']);
        $pr1 = $sentencias->homologaProyectos($res['pr1']);
        $ex1 = $sentencias->homologaProyectos($res['ex1']);
        $q1 = $sentencias->homologaProyectos($res['q1']);
        $p4 = $sentencias->homologaProyectos($res['p4']);
        $p5 = $sentencias->homologaProyectos($res['p5']);
        $p6 = $sentencias->homologaProyectos($res['p6']);
        $pr2 = $sentencias->homologaProyectos($res['pr2']);
        $ex2 = $sentencias->homologaProyectos($res['ex2']);
        $q2 = $sentencias->homologaProyectos($res['q2']);
        
        
        array_push($notasProyectos, array(
            'p1' => $p1['abreviatura'],
            'p2' => $p2['abreviatura'],
            'p3' => $p3['abreviatura'],
            'pr1' => $pr1['abreviatura'],
            'ex1' => $ex1['abreviatura'],
            'q1' => $q1['abreviatura'],
            'p4' => $p4['abreviatura'],
            'p5' => $p5['abreviatura'],
            'p6' => $p6['abreviatura'],
            'pr2' => $pr2['abreviatura'],
            'ex2' => $ex2['abreviatura'],
            'q2' => $q2['abreviatura'],
        ));
        
        return $notasProyectos;
        
    }
    
    public function consulta_materias_proyectos(){
        $con = Yii::$app->db;
        $query = "select 	m.name as materia
		,l.p1, l.p2,l.p3, l.pr1, l.ex1, l.q1 
		,l.p4, l.p5,l.p6, l.pr2, l.ex2, l.q2 
from 	scholaris_clase_libreta l
		inner join scholaris_grupo_alumno_clase g on g.id = l.grupo_id
		inner join op_student_inscription i on i.student_id = g.estudiante_id
		inner join scholaris_clase c on c.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = c.malla_materia
		inner join scholaris_materia m on m.id = mm.materia_id 
where 	g.estudiante_id = $this->alumno
		and i.parallel_id = $this->paralelo
		and c.periodo_scholaris = '$this->periodoCodigo'
		and mm.tipo = 'PROYECTOS';";
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

    
}
