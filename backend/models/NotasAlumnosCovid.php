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
class NotasAlumnosCovid extends \yii\db\ActiveRecord {

    private $grupoId;
    private $modelBloqueQ1;
    private $modelBloqueQ2;
    private $periodoId;
    private $institutoId;
    private $periodoCodigo;
    public $arrayNotasQ1 = array();
    public $arrayNotasQ2 = array();
    public $promedio = array();
    

    public function __construct($grupoId) {

        $this->grupoId = $grupoId;
        $this->verifica_bloques();
        $this->verifica_notas_q1();
        $this->verifica_notas_q2();
        $this->calcula_promedio_final_normal();
    }
    
    
    private function verifica_bloques(){
        
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $this->institutoId = Yii::$app->user->identity->instituto_defecto;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $modelGrupo = ScholarisGrupoAlumnoClase::findOne($this->grupoId);
        
        $uso =  $modelGrupo->clase->tipo_usu_bloque;
        
        $this->modelBloqueQ1 = \backend\models\ScholarisBloqueActividad::find()->where([
            'scholaris_periodo_codigo' => $modelPeriodo->codigo,
            'tipo_bloque' => 'PARCIAL',
            'estado' => 'activo',
            'quimestre' => 'QUIMESTRE I',
            'tipo_uso' => $uso,
            'instituto_id' => $this->institutoId
        ])->orderBy('orden')->all();
        
        $this->modelBloqueQ2 = \backend\models\ScholarisBloqueActividad::find()->where([
            'scholaris_periodo_codigo' => $modelPeriodo->codigo,
            'tipo_bloque' => 'PARCIAL',
            'estado' => 'activo',
            'quimestre' => 'QUIMESTRE II',
            'tipo_uso' => $uso,
            'instituto_id' => $this->institutoId
        ])->orderBy('orden')->all();
        
    }
    
    private function verifica_notas_q1(){
        
        $sentenciasNotas = new Notas();
        $digito = 2;
        $arrayAux = array();
        
        
        
        foreach ($this->modelBloqueQ1 as $q1){
            $arrayNotas = $this->calcula_normales($q1->id);
            
            
            if(count($arrayNotas)>0 ){
                $suma = 0;                                
                
                foreach ($arrayNotas as $nota){
                    
                    $nuevaNota = $this->busca_nota_mejorada($nota['codigo_que_califica'], $q1->id);
                    if($nuevaNota > 0){
                        $suma = $suma + $nuevaNota;
                    }else{
                        $suma = $suma + $nota['nota'];
                    }
                }
            }else{
                $suma = 0;
            }
            
            
            
            array_push($arrayAux, array(
                'p'.$q1->orden => $q1->orden,
                'nota' => $suma
            ));
            
        }
        

        
        if(count($this->modelBloqueQ1) > 2){
            $pr1 = $sentenciasNotas->truncarNota(($arrayAux[0]['nota'] + $arrayAux[1]['nota'] + $arrayAux[2]['nota'])/count($this->modelBloqueQ1), $digito);
        
            $p3 = $arrayAux[2]['nota'];
        }else{
            
            $pr1 = $sentenciasNotas->truncarNota(($arrayAux[0]['nota'] + $arrayAux[1]['nota'])/count($this->modelBloqueQ1), $digito);
            //$pr1 = ($arrayAux[0]['nota'] + $arrayAux[1]['nota'])/count($this->modelBloqueQ1);
        
            $p3 = 0;
        }
        
        $pr180 = $sentenciasNotas->truncarNota(($pr1*80/100), $digito);
        $modelEx = ScholarisClaseLibreta::find()->where(['grupo_id' => $this->grupoId])->one();
        $ex1 = 0;
        if(isset($modelEx->ex1)==null){
            $ex1 = 0;
        }else{
            $ex1 = $modelEx->ex1;
        }
        $ex120 = $sentenciasNotas->truncarNota(($ex1*20/100), $digito);
        $q1 = $pr180+$ex120;
        
        array_push($this->arrayNotasQ1,array(
            'p1' => $arrayAux[0]['nota'],
            'p2' => $arrayAux[1]['nota'],
            'p3' => $p3,
            'pr1' => $pr1,
            'pr180' => $pr180,
            'ex1' => $ex1,
            'ex120' => $ex120,
            'q1' => $q1
        ));
        
    }
    
    private function verifica_notas_q2(){
        
        $sentenciasNotas = new Notas();
        $digito = 2;
        $arrayAux = array();
        
        
        
        foreach ($this->modelBloqueQ2 as $q2){
            $arrayNotas = $this->calcula_normales($q2->id);
            
            
            if(count($arrayNotas)>0 ){
                $suma = 0;                                
                
                foreach ($arrayNotas as $nota){
                    
                    $nuevaNota = $this->busca_nota_mejorada($nota['codigo_que_califica'], $q2->id);
                    if($nuevaNota > 0){
                        $suma = $suma + $nuevaNota;
                    }else{
                        $suma = $suma + $nota['nota'];
                    }
                }
            }else{
                $suma = 0;
            }
            
            
            
            array_push($arrayAux, array(
                'p'.$q2->orden => $q2->orden,
                'nota' => $suma
            ));
            
        }
        

        
        if(count($this->modelBloqueQ2) > 2){
            $pr2 = $sentenciasNotas->truncarNota(($arrayAux[0]['nota'] + $arrayAux[1]['nota'] + $arrayAux[2]['nota'])/count($this->modelBloqueQ2), $digito);
        
            $p6 = $arrayAux[2]['nota'];
        }else{
            
            $pr2 = $sentenciasNotas->truncarNota(($arrayAux[0]['nota'] + $arrayAux[1]['nota'])/count($this->modelBloqueQ2), $digito);
            //$pr1 = ($arrayAux[0]['nota'] + $arrayAux[1]['nota'])/count($this->modelBloqueQ1);
        
            $p6 = 0;
        }
        
        $pr280 = $sentenciasNotas->truncarNota(($pr2*80/100), $digito);
        $modelEx = ScholarisClaseLibreta::find()->where(['grupo_id' => $this->grupoId])->one();
        $ex2 = 0;
        if(isset($modelEx->ex2)==null){
            $ex2 = 0;
        }else{
            $ex2 = $modelEx->ex2;
        }
        $ex220 = $sentenciasNotas->truncarNota(($ex2*20/100), $digito);
        $q2 = $pr280+$ex220;
        
        array_push($this->arrayNotasQ2,array(
            'p4' => $arrayAux[0]['nota'],
            'p5' => $arrayAux[1]['nota'],
            'p6' => $p6,
            'pr2' => $pr2,
            'pr280' => $pr280,
            'ex2' => $ex2,
            'ex220' => $ex220,
            'q2' => $q2
        ));
        
    }
    
    
    private function calcula_promedio_final_normal(){
        
        $sentencias = new Notas();
        
        $this->promedio = $sentencias->truncarNota(($this->arrayNotasQ1[0]['q1'] + $this->arrayNotasQ2[0]['q2'])/2, 2) ;
        
        
        
    }

    
    private function busca_nota_mejorada($codigoQueCalifica, $bloqueId){
        $con = Yii::$app->db;
        $query = "select 	nota_nueva 
                    from 	scholaris_calificaciones_parcial_cambios c
                    where 	c.grupo_id = $this->grupoId
                                    and c.codigo_que_califica = '$codigoQueCalifica'
                                        and c.bloque_id = $bloqueId
                    order by id desc
                    limit 1;";
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryOne();
        if($res){
            return $res['nota_nueva'];
        }else{
            return 0;
        }
        
    }


    private function calcula_normales($bloqueId){
        $con = Yii::$app->db;
        $query = "select 	b.id 
                                    ,b.orden
                                    ,c.codigo_que_califica 
                                    ,c.nota 
                    from 	scholaris_calificaciones_parcial c
                                    inner join scholaris_bloque_actividad b on b.id = c.bloque_id 
                    where 	c.grupo_id = $this->grupoId
                                    and b.id = $bloqueId;";
        
//        echo $query;
//        die();
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    

    
}
