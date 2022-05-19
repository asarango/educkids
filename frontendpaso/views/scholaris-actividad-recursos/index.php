<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisActividadRecursosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Recursos para la actividad: ' . $modelActividad->title
        . ' / ' . $modelActividad->clase->curso->name
        . ' / ' . $modelActividad->clase->paralelo->name
        . ' / ' . $modelActividad->clase->materia->name
;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de destrezas PUD',
    'url' => ['scholaris-plan-pud-detalle/editardestreza', 'destreza' => $modelActividad->destreza_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-recursos-form">

    <div  class="container">
        <div  class="row">

            <?php $form = ActiveForm::begin(['class' => 'form-inline']); ?>
            <?= $form->field($model, 'actividad_id')->hiddenInput(['value' => $modelActividad->id])->label(false) ?>

            <div  class="col-md-3">
                <?=
                $form->field($model, 'tipo_codigo')->dropDownList([
                    'RECURSO' => 'RECURSO',
                    'TIPO' => 'TIPO',
                    'TECNICA' => 'TECNICA',
                    'INSTRUMENTO' => 'INSTRUMENTO',
                ])
                ?>
            </div>

            <div  class="col-md-6">
                <?= $form->field($model, 'nombre')->textarea(['rows' => 1]) ?>
            </div>

            <div  class="col-md-3">
                <br>
                <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <hr>
    <div class="container">



        <div class="row">
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">RECURSOS</div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($modelRecursos as $recurso) { ?>

                                <?php
                                if ($recurso->tipo_codigo == 'RECURSO') {
                                    echo '<li>';
                                    echo Html::a('', ['eliminar', 'id' => $recurso->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                    echo $recurso->nombre;
                                    echo '</li>';
                                }
                                ?>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">TIPOS</div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($modelRecursos as $recurso) { ?>

                                <?php
                                if ($recurso->tipo_codigo == 'TIPO') {
                                    echo '<li>';
                                    echo Html::a('', ['eliminar', 'id' => $recurso->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                    echo $recurso->nombre;
                                    echo '</li>';
                                }
                                ?>


                            <?php } ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">TECNICAS</div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($modelRecursos as $recurso) { ?>

                                <?php
                                if ($recurso->tipo_codigo == 'TECNICA') {
                                    echo '<li>';
                                    echo Html::a('', ['eliminar', 'id' => $recurso->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                    echo $recurso->nombre;
                                    echo '</li>';
                                }
                                ?>


                            <?php } ?>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">INSTRUMENTOS</div>
                    <div class="panel-body">
                        <ul>
                            <?php foreach ($modelRecursos as $recurso) { ?>

                                <?php
                                if ($recurso->tipo_codigo == 'INSTRUMENTO') {
                                   echo '<li>';
                                    echo Html::a('', ['eliminar', 'id' => $recurso->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']);
                                    echo $recurso->nombre;
                                    echo '</li>';
                                }
                                ?>


                            <?php } ?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>




</div>
