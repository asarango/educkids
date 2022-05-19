<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPerido = backend\models\ScholarisPeriodo::findOne($periodoId);

$modelBloque = backend\models\ScholarisBloqueActividad::find()
        ->where(['scholaris_periodo_codigo' => $modelPerido->codigo, 'tipo_uso' => $modelClase->tipo_usu_bloque])
        ->orderBy('orden')
        ->all();

$this->title = 'PLANIFICACION INICIAL: ' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
        . ' / ' . $quimestre
;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion',
    'url' => ['scholaris-plan-inicial/index1',
        'id' => $modelClase->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-planificar">

    <div class="alert alert-info">
        <strong>
            <?= $modelAmbito->nombre ?>
        </strong>
    </div>


    <div class="container">
        <div class="table-responsive">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th colspan="2">ACCIONES</th>
                        <th>ESTADO</th>
                        <th>CODIGO</th>
                        <th>DESTREZA</th>
                        
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($modelDestreza as $dest) {
                        
                        $estado = toma_estado_planificacion($dest->codigo, $quimestre, $modelClase->id);
                        if($estado == 'NO'){
                            $est = 'NO USADO';
                            $color = 'danger';
                        }else{
                            $est = $estado;
                            $color = 'primary';
                        }
                                              
                        echo '<tr>';
                        
                        echo '<td>';
                        echo Html::a('', ['asignar', 
                                                'id' => $dest->id,
                                                'quimestre' => $quimestre,
                                                'clase' => $modelClase->id,
                                                'ambitoId' => $dest->ambito_id
                                               ], 
                                               //['class' => 'glyphicon glyphicon-pencil']);
                                               ['class' => 'glyphicon glyphicon-ok-circle']);
                        echo '</td>'; 
                        
                        
                        if($estado == 'NO'){
                            echo '<td></td>';
                        }else{
                        
                        echo '<td>';
                        
                        $totalCalificaciones = devuelve_total_calificaciones($modelClase->id, $quimestre, $dest->id);
                        
                        if($totalCalificaciones<1){
                            echo Html::a('', ['eliminar', 
                                                'id' => $dest->id,
                                                'quimestre' => $quimestre,
                                                'clase' => $modelClase->id,
                                                'ambitoId' => $dest->ambito_id
                                               ], 
                                               //['class' => 'glyphicon glyphicon-pencil']);
                                               ['class' => 'glyphicon glyphicon-trash']);
                        }else{
//                            echo '<span class="glyphicon glyphicon-saved"></span>';
                            echo Html::a('', ['eliminar-todo', 
                                                'id' => $dest->id,
                                                'quimestre' => $quimestre,
                                                'clase' => $modelClase->id,
                                                'ambitoId' => $dest->ambito_id
                                               ], 
                                               //['class' => 'glyphicon glyphicon-pencil']);
                                               ['class' => 'glyphicon glyphicon-trash']);
                        }
                        
                        
                        echo '</td>'; 
                        }
                        
                        echo '<td class="text text-'.$color.'">'.$est.'</td>';
                        echo '<td>'.$dest->codigo.'</td>';
                        echo '<td>'.$dest->nombre.'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>

            </table>
        </div>
    </div>


</div>

<?php
    function toma_estado_planificacion($codigoDestreza, $quimestre, $clase){    
        $model = \backend\models\ScholarisPlanInicial::find()
                ->where([
                        'clase_id' => $clase,
                        'quimestre_codigo' => $quimestre,
                        'codigo_destreza' => $codigoDestreza,
                    ])
                ->one();
        
               
        //if(count($model)>0){
        if(isset($model)>0){
            return $model->estado;
        }else{
            return 'NO';
        }
    }
    
    function devuelve_total_calificaciones($clase, $quimestre, $idDestreza){
        $modelCur = \backend\models\CurCurriculoDestreza::findOne($idDestreza);
        
        $model = \backend\models\ScholarisPlanInicial::find()
                ->where([
                    'clase_id' => $clase,
                    'quimestre_codigo' => $quimestre,
                    'codigo_destreza' => $modelCur->codigo
                ])
                ->one();
        
        $modelCal = \backend\models\ScholarisCalificacionesInicial::find()->where([
            'plan_id' => $model->id
        ])->all();
       
        
        return count($modelCal);
    }

?>