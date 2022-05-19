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
class ComportamientoAutomatico extends \yii\db\ActiveRecord {

    private $alumno;
    private $paralelo;
    private $modelParalelo;
    private $quimestre;
    private $periodoId;
    private $claseId;
    private $periodoCodigo;
    private $uso;
    private $modelBloquesQ1;
    private $modelBloquesQ2;
    private $actividadIdQ1;
    private $actividadIdQ2;
    private $totalPacialesQ1;
    private $totalPacialesQ2;
    public $notaQ1 = 0;
    public $notaQ2 = 0;

    public function __construct($alumno, $paralelo) {
        
        $this->alumno = $alumno;
        $this->paralelo = $paralelo;
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
        
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
                
        $modelUso = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $this->uso = $modelUso->tipo_usu_bloque;
        $this->modelBloquesQ1 = $this->consulta_bloques_parcial('QUIMESTRE I');
        $this->modelBloquesQ2 = $this->consulta_bloques_parcial('QUIMESTRE II');
        $this->totalPacialesQ1 = count($this->modelBloquesQ1);
        $this->totalPacialesQ2 = count($this->modelBloquesQ2);        
        
        $this->claseId = $this->consulta_clase_id();
        $this->consulta_actividad_id();
                
        
        //if($this->consulta_nota($this->actividadIdQ1)){
            $this->notaQ1 = $this->consulta_nota($this->actividadIdQ1);
            $this->notaQ2 = $this->consulta_nota($this->actividadIdQ2);
        //}else{
        //    $this->notaQ1 = 0;
        //}                
        
        
//        $this->notaQ2 = $this->consulta_nota($this->actividadIdQ2);
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
    
    private function consulta_actividad_id(){
        $this->totalPacialesQ1 == 3 ? $orden1 = 3 : $orden1 = 2;
        $this->totalPacialesQ2 == 3 ? $orden2 = 7 : $orden2 = 6;
        
        $this->actividadIdQ1 = $this->consulta_actividad_comportamiento($orden1);
        $this->actividadIdQ2 = $this->consulta_actividad_comportamiento($orden2);
        
                
    }
    
    private function consulta_actividad_comportamiento($orden){
        $con = Yii::$app->db;
        $query = "select 	a.id 
                    from 	scholaris_actividad a
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_actividad_id 
                    where 	a.paralelo_id = $this->claseId
                                    and b.orden = $orden and a.calificado = 'SI';";        
        
        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['id'])){
            $actividadId = $res['id'];
        }else{
            $actividadId = false;
        }
        
        return $actividadId;
    }
    
    
    private function consulta_clase_id(){
        $con = Yii::$app->db;
        $query = "select 	c.id 
                    from 	scholaris_clase c
                                    inner join scholaris_malla_materia mm on mm.id = c.malla_materia 
                    where 	c.paralelo_id = $this->paralelo
                                    and mm.tipo = 'COMPORTAMIENTO';";
        
        
        $res = $con->createCommand($query)->queryOne();
        
        if(isset($res['id'])){
            return $res['id'];
        }else{
            return false;
        }
        
        
    }
    
    
    private function consulta_nota($actividadId){
        
        $sentencias = new Notas();
        $sugerido = new ComportamientoSugerido();
        
        $modelActividad = ScholarisActividad::findOne($actividadId);
        
                
        if(isset($modelActividad)){
            $modelNota = '';
            $modelNota = $sugerido->toma_nota_actividad($this->alumno, $modelActividad);                       
            
            if($modelNota == ''){
              $nota = 0;  
            }else{
                $nota = $modelNota->calificacion;
            }
            
        }else{
            $nota = 0;
        }
        

        $seccion = $this->modelParalelo->course->section0->code;
        $notaH = $sentencias->homologa_comportamiento($nota, $seccion);
        
        return $notaH;
        
        
    }
    

    
}
