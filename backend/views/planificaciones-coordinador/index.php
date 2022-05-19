<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisiones de Plan Semanal: ' . $modelParalelo->course->name . ' / '
        . $modelParalelo->name. ' / '
        .$modelSemana->nombre_semana
;
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Detalle Desempeño', 'url' => ['desempeno/detalle', 'id' => $modelParalelo->id]];
$this->params['breadcrumbs'][] = $this->title;


$data = ArrayHelper::map($modelSemanas, 'id', 'nombre_semana');
?>


<div class="planificaciones-coordinador-index1">

    <div class="container">


        <h3><?= Html::encode($this->title) ?></h3>
        <?php // echo $this->render('_search', ['model' => $searchModel]);  ?>

        <div class="container">
            
            <?= Html::beginForm(['index1', 'id' => $modelParalelo->id], 'GET'); ?>

            <?php
            echo '<label class="control-label">Semana:</label>';
            echo Select2::widget([
                'name' => 'semanaId',
                'value' => 0,
                'data' => $data,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione Semana...',
//                    'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);

            
            echo Html::submitButton(
                    'Aceptar',
                    ['class' => 'btn btn-primary']
            );
            ?>


            <?php echo Html::endForm(); ?>

            <div class="table table-responsive">
                <table class="table table-hover table-bordered table-condensed">
                    <tr>
                        <td><strong>DíA</strong></td>
                        <td><strong>HORA</strong></td>
                        <td><strong>FECHA</strong></td>
                        <td><strong>DOCENTE</strong></td>
                        <td><strong>OBSERVACIÓN</strong></td>
                        
                        <td colspan="3" align="center"><strong>ACCIONES</strong></td>
                    </tr>

                    <?php
                                foreach ($modelHorario as $horario){
                                    echo '<tr>';
                                    echo '<td>'.$horario['nombre'].'</td>';
                                    echo '<td>'.$horario['sigla'].'</td>';
                                    echo '</tr>';
                                }
                    ?>


                </table>
            </div>
        </div>

    </div>
</div>



