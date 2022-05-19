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
class SentenciasBloque extends \yii\db\ActiveRecord {
   
    public function get_bloque_con_fecha($uso, $fecha, $codigo) {
        $con = Yii::$app->db;
        $query = "select id
                    from 	scholaris_bloque_actividad
                    where	tipo_uso = '$uso' 
                                and scholaris_periodo_codigo = '$codigo'
                                    and '$fecha' between bloque_inicia and bloque_finaliza;";

//        echo $query;
//        die();

        $res = $con->createCommand($query)->queryOne();

        return $res['id'];
    }

    public function get_bloque_por_orden($orden, $paralelo, $periodoCodigo, $intituto) {
        
        $modelUso = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        
        $con = \Yii::$app->db;
        $query = "select 	id as bloque_id
                    from 	scholaris_bloque_actividad
                    where	tipo_uso = '$modelUso->tipo_usu_bloque'
                                    and scholaris_periodo_codigo = '$periodoCodigo'
                                    and orden = $orden
                                    and instituto_id = $intituto;";

        $res = $con->createCommand($query)->queryOne();
        return $res['bloque_id'];
    }

    public function get_bloque_por_campo($alumno, $quimestre, $paralelo) {

        $periodo = Yii::$app->user->identity->periodo_id;
        $modelPerido = ScholarisPeriodo::findOne($periodo);

        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;

        switch ($quimestre) {
            case 'p1':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 1,
                            'tipo_uso' => $uso
                        ])->one();
                break;

            case 'p2':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 2,
                            'tipo_uso' => $uso
                        ])->one();                                
                break;

            case 'p3':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 3,
                            'tipo_uso' => $uso
                        ])->one();
                break;


            case 'q1':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 3,
                            'tipo_uso' => $uso
                        ])->one();
                break;


            case 'p4':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 5,
                            'tipo_uso' => $uso
                        ])->one();
                break;

            case 'p5':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 6,
                            'tipo_uso' => $uso
                        ])->one();
                break;

            case 'p6':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 7,
                            'tipo_uso' => $uso
                        ])->one();
                break;


            case 'q2':
                $modelBloque = ScholarisBloqueActividad::find()->where([
                            'scholaris_periodo_codigo' => $modelPerido->codigo,
                            'orden' => 7,
                            'tipo_uso' => $uso
                        ])->one();
                break;
        }
        return $modelBloque->id;
    }

    public function recupera_bloque_por_orden($uso, $orden) {

        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $model = ScholarisBloqueActividad::find()
                ->where([
                    'tipo_uso' => $uso,
                    'orden' => $orden,
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo
                ])
                ->one();

        return $model->id;
    }

    public function recupera_semanas_paralelo($paralelo) {
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);
        $modelUso = \backend\models\ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelUso->tipo_usu_bloque;

        $con = Yii::$app->db;
        $query = "select 	s.id, s.nombre_semana
                    from 	scholaris_bloque_semanas s
                                    inner join scholaris_bloque_actividad b on b.id = s.bloque_id
                    where	b.tipo_uso = '$uso'
                                    and b.scholaris_periodo_codigo = '$modelPeriodo->codigo'
                    order by s.semana_numero;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    public function recupera_bloque_por_orden_sin_examen($paralelo) {

        $modelClase = ScholarisClase::find()->where(['paralelo_id' => $paralelo])->one();
        $uso = $modelClase->tipo_usu_bloque;
  
        $periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodoId);

        $model = ScholarisBloqueActividad::find()
                ->where([
                    'tipo_uso' => $uso,        
                    'scholaris_periodo_codigo' => $modelPeriodo->codigo,
                    'tipo_bloque' => 'PARCIAL'
                ])
                ->orderBy('orden')
                ->all();

        return $model;
    }

}
