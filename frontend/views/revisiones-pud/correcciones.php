<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRefuerzoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Correciones a Pud: '
;
?>
<div class="revisiones-pud-correcciones">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <?php echo Html::a('Puds para revision', ['revisiones-pud/index1']); ?>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>

    <div class="container">
        <div class="alert alert-info">
            <div class="row">
                <div class="col-md-2"><?= $modelPud->clase->course->name . ' ' . $modelPud->clase->paralelo->name ?></div>
                <div class="col-md-3"> / <?= $modelPud->clase->materia->name ?></div>
                <div class="col-md-3"> / <?= $modelPud->clase->profesor->last_name . ' ' . $modelPud->clase->profesor->x_first_name ?></div>
                <div class="col-md-3"> / <?= $modelPud->titulo ?></div>
            </div>

            <?php
            if ($modelReporte->valor == 1) {
                echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-info']);
            } elseif ($modelReporte->valor == 2) {
                echo Html::a('Generar Reporte PDF - PROV', ['reporte-pud-prov/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-info']);
            } else {
                echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-info']);
                echo Html::a('Generar Reporte PDF - PROV', ['reporte-pud-prov/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-info']);
            }
            ?>
        </div>
    </div>

    <div class="container">
        <!--
            <div class="col-md-8">
    
                <div class="table table-responsive">
    
                    <table class="table table-condensed table-striped table-hover" style="font-size: 10px;">
    
                        <tr>
                            <th>FECHA DE INICIO:</th>
                            <td><?= $modelPud->fecha_inicio ?></td>
                        </tr>
                        <tr>
                            <th>FECHA DE FINALIZACION:</th>
                            <td><?= $modelPud->fecha_finalizacion ?></td>
                        </tr>
                        <tr>
                            <th>OBJETIVO DE LA UNIDAD:</th>
                            <td><?= $modelPud->objetivo_unidad ?></td>
                        </tr>
    
                        <tr>
                            <th>ESPECIFICACIÓN DE LA NECESIDAD EDUCATIVA ATENDIDA:</th>
                            <td><?= $modelPud->ac_necesidad_atendida ?></td>
                        </tr>
    
                        <tr>
                            <th>ESPECIFICACIÓN DE LA ADAPTACIÓN APLICADA:</th>
                            <td><?= $modelPud->ac_adaptacion_aplicada ?></td>
                        </tr>
    
                        <tr>
                            <th>BIBLIOGRAFÍA/ WEBGRAFÍA:</th>
                            <td><?= $modelPud->bibliografia ?></td>
                        </tr>
    
                        <tr>
                            <th>OBSERVACIONES:</th>
                            <td><?= $modelPud->observaciones ?></td>
                        </tr>
                    </table>
                    <hr>
    
                </div>
                <strong><u>DETALLE</u></strong>
                <div class="table table-responsive">
    
                    <table class="table table-condensed table-striped table-hover" style="font-size: 10px;">
    
                        <tr>
                            <td>TIPO</td>
                            <td>CODIGO</td>
                            <td>CONTENIDO</td>
                        </tr>
    
        <?php
        foreach ($modelPudDetalle as $detalle) {
            ?>
                                <tr>
                                    <td><?= $detalle->tipo ?></td>
                                    <td><?= $detalle->codigo ?></td>
                                    <td><?= $detalle->contenido ?></td>
                                </tr>
            <?php
        }
        ?>
                    </table>
                    <hr>
    
                </div>
    
            </div>
        -->


        <div class="row">

            <div class="col-md-8">
                <div class="well">
                <strong>CORRECCIONES</strong>

                <?php
                $usuario = Yii::$app->user->identity->usuario;
                $fecha = date('Y-m-d H:m:s');
                ?>

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'pud_id')->hiddenInput(['value' => $modelPud->id])->label(false) ?>

                <?= $form->field($model, 'detalle_cambios')->texTarea(['rows' => '10']) ?>

                <?= $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false) ?>
                <?= $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>
                <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>
                <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>


                <div class="form-group">
                    <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
            </div>

            <div class="col-md-4">
                
                <div class="row well">
                    <p class="text-info">Si el PUD se encuentra correcto por favor enviar a Vicerrector / a para su respectiva revision y aprobacion</p>
                    <?php echo Html::a('ENVIAR A VICERRECTOR', ['enviarvicerrector', 'pudId' => $modelPud->id], ['class' => 'btn btn-warning form-control']); ?>
                </div>
            </div>

        </div>




    </div>


</div>
