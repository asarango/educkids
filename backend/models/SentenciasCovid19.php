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
class SentenciasCovid19 extends \yii\db\ActiveRecord {

    public function get_calificaciones_paralelo($paralelo, $tipoQuimestre) {

        $con = Yii::$app->db;
        $query = "select 	c.inscription_id 
		,c.tipo_quimestre_id 
		,s.last_name 
		,s.first_name 
		,s.middle_name 
		,c.portafolio 
		,c.padre
		,c.contenido 
		,c.presentacion 
                                    ,c.total
                            from 	scholaris_calificacion_covid19 c
                                            inner join op_student_inscription i ON i.id = c.inscription_id
                                            inner join op_student s on s.id = i.student_id 
                            where 	i.parallel_id = $paralelo
                                            and i.inscription_state = 'M'
                                               and c.tipo_quimestre_id = $tipoQuimestre
order by s.last_name, s.first_name, s.middle_name;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    public function calcula_total_quimestre($inscriptionId, $tipoQuimestre) {
        $model = \backend\models\ScholarisCalificacionCovid19::find()->where([
                    'inscription_id' => $inscriptionId,
                    'tipo_quimestre_id' => $tipoQuimestre
                ])->one();
        
        $model->total = $model->padre + $model->portafolio + $model->contenido + $model->presentacion;
//        echo '<pre>';
//        echo $model->total;
//        die();
        
        $model->save();
    }

    
    
    /**
     * ENTREGA NOTAS DE ACUERSO AL QUIMESTRE Y A COVID 19
     * @param type $modelAlumnos
     * @param type $mallaId
     * @param type $modelTipoQuimestre
     * @return array
     */
    public function calcula_notas_paralelo($modelAlumnos, $mallaId, $modelTipoQuimestre) {
        $sentenciasNotas = new \backend\models\SentenciasRepLibreta2();
        $usuario = \Yii::$app->user->identity->usuario;
        $arreglo = array();

        foreach ($modelAlumnos as $alumno) {
            $notas = $sentenciasNotas->get_notas_finales($alumno['id'], $usuario, $mallaId);
            $notaQ1 = $this->consulta_nota_covid(1, $alumno['inscription_id']);
            $notaQ2 = $this->consulta_nota_covid(2, $alumno['inscription_id']);
            
            array_push($arreglo, array(
                'id' => $alumno['inscription_id'],
                'nombre' => $alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'],
                'q1' => $notas['q1'],
                'q2' => $notas['q2'],
                'covidq1' => $notaQ1,
                'covidq2' => $notaQ2
            ));
        }
        
        return $arreglo;
      
    }

    private function consulta_nota_covid($quimestre, $inscriptionId) {
        $model = ScholarisCalificacionCovid19::find()
                        ->innerJoin("scholaris_quimestre_tipo_calificacion t", "t.id = scholaris_calificacion_covid19.tipo_quimestre_id")
                        ->where([
                            'inscription_id' => $inscriptionId,
                            't.quimestre_id' => $quimestre
                        ])->one();
        
        if(isset($model->total)){
            return $model->total;
        }else{
            return 0;
        }
    }

}
