<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanCurriculoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$dataEval = ArrayHelper::map($modelEvaluaciones, 'id', 'codigo');


$this->title = 'PCI: ' . $modelSubNivel->nombre . ' / ' . $modelPci->materia_curriculo_nombre;
$pdfTitle = $this->title;

$this->params['breadcrumbs'][] = ['label' => 'Opciones PCI', 'url' => ['scholaris-pci/index']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-pci-detalle">

    <div class="container">
        <div class="row">
            <?php echo Html::beginForm(['create', 'post']); ?>

            <div class="col-md-2"><?php echo '<label class="control-label">Agregar criterio de evaluación :</label>' ?></div>
            <div class="col-md-5">
                <?php
                echo Select2::widget([
                    'name' => 'evaluaciones',
                    'value' => 0,
                    'data' => $dataEval,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione Criterio evaluación',
                    //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>

            <div class="col-md-3">
                <input type="hidden" name="pci" value="<?= $modelPci->id ?>">
                <?php
                echo Html::submitButton(
                        'Aceptar',
                        ['class' => 'btn btn-primary']
                );
                ?>
            </div>
            <?php echo Html::endForm(); ?>         

        </div>

    </div>
    <hr>

    <div class="table table-responsive">
        <table class="table table-hover table-condensed table-bordered tamano10">
            <thead>
                <tr bgcolor="<?= $modelPci->materia_curriculo_color ?>">
                    <th rowspan="2">CRITERIOS DE EVALUACIÓN</th>
                    <th colspan="3">DESTREZAS CON CRITERIOS DE DESEMPEÑO</th>
                </tr>
                <tr>
                    <?php
                    foreach ($modelCursos as $curso) {
                        echo '<th bgcolor="' . $modelPci->materia_curriculo_color . '">' . $curso->nombre . '</th>';
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($modelPciEvalu as $eva) {
                    echo '<tr>';

                    echo '<td>' . '<strong>' . $eva->codigo_criterio_evaluacion . '</strong>' . $eva->descripcion_criterio_evaluacion;
                    
                    echo Html::beginForm(['createdestreza', 'post']);
                    echo '<label class="control-label">Agregar destreza con criterio de desempeño:</label>';
                    
                    $lista = backend\models\CurCurriculo::find()
                            ->select(['id', "concat(codigo,' ',detalle,' (imprencindible: ',imprencindible,')') as codigo"])
                            ->where([
                                'tipo_referencia' => 'destrezas',
                                'pertence_a' => $eva->codigo_criterio_evaluacion
                            ])
                            ->orderBy('codigo')
                            ->all();
                    $dataDest = ArrayHelper::map($lista, 'id', 'codigo');
                    
                    echo Select2::widget([
                        'name' => 'destreza',
                        'value' => 0,
                        'data' => $dataDest,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione Destreza',
                        //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    
                    echo '<label class="control-label">En que curso?:</label>';
                    
                    $lista = backend\models\GenCurso::find()
                            ->where([
                                'subnivel_id' => $modelPci->subnivel_id
                            ])
                            ->orderBy('orden')
                            ->all();
                    $dataCurso = ArrayHelper::map($lista, 'id', 'nombre');
                    
                    echo Select2::widget([
                        'name' => 'curso',
                        'value' => 0,
                        'data' => $dataCurso,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione Curso',
                        //'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    
                    echo '<input type="hidden" name="evaluacion" value="'.$eva->id.'">';
                    echo '<input type="hidden" name="desagrega" value="false">';
                    
                    echo Html::submitButton(
                        'Aceptar',
                        ['class' => 'btn btn-primary']
                );
                    echo Html::endForm();
                                        
                    echo Html::a('', ['eliminareval','evaluacion' => $eva->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                    
                     
                    echo '</td>';
                    
                    foreach ($modelCursos as $curso) {
                        echo '<td>';
                        echo '<table border="0">';
                        $modelDestreza = \backend\models\ScholarisPlanPciEvaluacionDestrezas::find()
                                ->where([
                                          'curso_subnivel_id' => $curso->id,
                                          'evaluacion_id' => $eva->id
                                       ])
                                ->orderBy('destreza_codigo')
                                ->all();
                        
                                              
                        if($modelDestreza){
                           
                            foreach ($modelDestreza as $dest){
//                                                              
                                $modelColor = backend\models\CurCurriculo::find()->where(['id' => $dest->destreza_id])->one();
                                echo '<tr>';
                                if($modelColor){
                                if($modelColor->imprencindible == true){
                                    echo '<td bgcolor="'.$modelPci->materia_curriculo_color.'">'.'<strong>'.$dest->destreza_codigo.'</strong>'.$dest->destreza_detalle;
                                    echo '<br>';
                                    echo Html::a('', ['eliminar','destreza' => $dest->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                    echo Html::a('Desagregar', ['desagregar','destreza' => $dest->id], ['class' => 'btn btn-link']);
                                    echo '</td>';
                                }else{
                                    echo '<td>'.'<strong>'.$dest->id.$dest->destreza_codigo.'</strong>'.$dest->destreza_detalle;
                                   echo '<br>';
                                   echo Html::a('', ['eliminar','destreza' => $dest->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                   echo Html::a('Desagregar', ['desagregar','destreza' => $dest->id], ['class' => 'btn btn-link']);
                                   echo '</td>'; 
                               }
                                   
//                                }
                                }
//                                
                                echo '</tr>';
//                                
//                                
                            }                            
                        }else{
                            echo '-';
                        }
                        echo '</table>';
                        echo '</td>';
                        
                        
                    }

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</div>