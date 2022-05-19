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
class NotasEnLibreta extends \yii\db\ActiveRecord {

    public function actualizaParcialesLibreta($clase){
        
//        $sentencias = new \frontend\models\SentenciasSql(); 
//        
//        $periodoId = Yii::$app->user->identity->periodo_id;
//        $modelPeriodo = ScholarisPeriodo::find()->where(['id' => $periodoId])->one();
//        
//        $modelClase = ScholarisClase::find()->where(['id'=>$clase])->one();
//        
//        $modelBloques = ScholarisBloqueActividad::find()
//                ->where([
//                    'tipo_uso' => $modelClase->tipo_usu_bloque,
//                    'scholaris_periodo_codigo' => $modelPeriodo->codigo
//                    ])
//                ->andFilterWhere(['IN','tipo_bloque',['PARCIAL', 'EXAMEN']])
//                ->orderBy("orden")
//                ->all();       
//        
//        $modelGrupo = ScholarisGrupoAlumnoClase::find()
//                ->where(['clase_id' => $clase])
//                ->all();       
//        
//        foreach ($modelGrupo as $grupo){
//            
//            $this->validaAlumnoLibreta($grupo->estudiante_id, $clase);
//            foreach ($modelBloques as $bloque){
//                $sentencias->actualizaLibreta($grupo->estudiante_id, $bloque->id, $clase);
//                
//            }
//            
//        }
               
        
    }
    
    private function validaAlumnoLibreta($alumno, $clase){
        $modelGrupo = ScholarisGrupoAlumnoClase::find()
                ->where(['estudiante_id' => $alumno, 'clase_id' => $clase])
                ->one();
        
        $modelLibreta = ScholarisClaseLibreta::find()
                ->where(['grupo_id' => $modelGrupo->id])
                ->one();
        
        if(!$modelLibreta){
            
//            echo 'Ingresar';
//            die();
            
            $model = new ScholarisClaseLibreta();
            $model->grupo_id = $modelGrupo->id;
            $model->save();
        }
        
    }
    
    
    public function calcula_promedios_clase($clase){
        
//        $sentencias = new SentenciasNotas();
//        $digito = 2;
//        
//        $con = \Yii::$app->db;
//        $query = "select l.id
//                         ,l.p1,l.p2,l.p3,ex1
//                         ,l.p4,l.p5,l.p6,ex2
//from 	scholaris_grupo_alumno_clase g
//		inner join scholaris_clase_libreta l on l.grupo_id = g.id
//where 	g.clase_id = $clase;";
//        
//        $alumnos = $con->createCommand($query)->queryAll();
//        
////        print_r($alumnos);
////        die();
//        
//        foreach ($alumnos as $data){
//            $pr1 = ($data['p1']+$data['p2']+$data['p3'])/3;
//            $pr1 = $sentencias->truncarNota($pr1, $digito);
//                        
//            $pr180 = $pr1 * 80 /100;
//            $pr180 = $sentencias->truncarNota($pr180, $digito);                        
//            
//            $ex120 = $data['ex1'] * 20 /100;            
//            $ex120 = $sentencias->truncarNota($ex120, $digito);        
//            
//            $q1 = $pr180 + $ex120;
//            
//            
//            $pr2 = ($data['p4']+$data['p5']+$data['p6'])/3;
//            $pr2 = $sentencias->truncarNota($pr2, $digito);
//                        
//            $pr280 = $pr2 * 80 /100;
//            $pr280 = $sentencias->truncarNota($pr280, $digito);                        
//            
//            $ex220 = $data['ex2'] * 20 /100;            
//            $ex220 = $sentencias->truncarNota($ex220, $digito);            
//            
//            $q2 = $pr280 + $ex220;
//            
//            $final = ($q1 + $q2) / 2;
//            $final = $sentencias->truncarNota($final, $digito);
//            
//            $this->actualiza_calculo($pr1, $pr180, $ex120, $q1, $pr2, $pr280, $ex220, $q2, $final, $data['id']);
            
//        }
        
    }
    
    private function actualiza_calculo($pr1, $pr180, $ex120, $q1, $pr2, $pr280, $ex220, $q2, $final, $id){
        $con = \Yii::$app->db;
        
        $query = "update scholaris_clase_libreta
                    set pr1 = $pr1, pr180 = $pr180, ex120 = $ex120, q1 = $q1
                            ,pr2 = $pr2, pr280 = $pr280, ex220 = $ex220, q2 = $q2
                            ,final_ano_normal = $final
                    where id = $id;";
        
        $con->createCommand($query)->execute();
        
    }
    
    public function promedios_clase($clase){
        
        $periodo = Yii::$app->user->identity->periodo_id;
        
        $con = \Yii::$app->db;
        
        $query = "select trunc(avg(p1),2) as p1
		,trunc(avg(p2),2) as p2
		,trunc(avg(p3),2) as p3
		,trunc(avg(pr1),2) as pr1
		,trunc(avg(pr180),2) as pr180
		,trunc(avg(ex1),2) as ex1
		,trunc(avg(ex120),2) as ex120
		,trunc(avg(q1),2) as q1
		,trunc(avg(p4),2) as p4
		,trunc(avg(p5),2) as p5
		,trunc(avg(p6),2) as p6
		,trunc(avg(pr2),2) as pr2
		,trunc(avg(pr280),2) as pr280
		,trunc(avg(ex2),2) as ex2
		,trunc(avg(ex220),2) as ex220
		,trunc(avg(q2),2) as q2
		,trunc(avg(l.final_ano_normal),2) as final
from 	scholaris_grupo_alumno_clase g
		inner join scholaris_clase_libreta l on l.grupo_id = g.id
                inner join op_student_inscription i on i.student_id = g.estudiante_id
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
		inner join scholaris_periodo p on p.id = sop.scholaris_id
where 	g.clase_id = $clase and p.id = $periodo
		and i.inscription_state = 'M';";         
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
    
    
    
    public  function totales_cuadro($clase, $minimo, $maximo, $campo){
        $con = Yii::$app->db;
        $query = "select count(l.id) as total
                    from	scholaris_grupo_alumno_clase g
                                    inner join scholaris_clase_libreta l on l.grupo_id = g.id 		
                    where	g.clase_id = $clase
                                    and $campo between $minimo and $maximo;";
        $res = $con->createCommand($query)->queryOne();
        
        return $res;
    }
    
    

}
