<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanPud */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPerido = backend\models\ScholarisPeriodo::findOne($periodoId);

$modelBloque = backend\models\ScholarisBloqueActividad::find()
        ->where(['scholaris_periodo_codigo' => $modelPerido->codigo, 'tipo_uso' => $modelClase->tipo_usu_bloque])
        ->orderBy('orden')
        ->all();


$listaBloque = ArrayHelper::map($modelBloque, 'id', 'name');

$modelProfesor = backend\models\OpFaculty::find()
        ->select(["id","concat(last_name,' ',x_first_name) as last_name"])
        ->all();
$listaProf = ArrayHelper::map($modelProfesor, 'id', 'last_name');

$fecha = date('Y-m-d H:i:s');
?>

<div class="scholaris-plan-pud-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>



        <div class="row">
            <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $modelClase->id])->label(false) ?>

            <div class="col-md-3">

                <?=
                $form->field($model, 'bloque_id')->widget(Select2::className(), [
                    'data' => $listaBloque,
                    'options' => ['placeholder' => 'Seleccione bloque...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ]
                ])
                ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'fecha_inicio')->widget(DatePicker::className(), [
                    'name' => 'fecha_inicio',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione fecha de inicio ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'fecha_finalizacion')->widget(DatePicker::className(), [
                    'name' => 'fecha_finalizacion',
                    'value' => date('d-M-Y', strtotime('+2 days')),
                    'options' => ['placeholder' => 'Seleccione feha de finalización ...'],
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true
                    ]
                ])
                ?>
            </div>
        </div>


        <div class="row">
            <div class="col-md-3">
                <?=
                $form->field($model, 'ac_responsable_dece')->widget(Select2::className(), [
                    'data' => $listaProf,
                    'options' => ['placeholder' => 'Seleccione representante dece...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ]
                ])
                ?>
            </div>

            <div class="col-md-3">
                <?=
                $form->field($model, 'quien_revisa_id')->widget(Select2::className(), [
                    'data' => $listaProf,
                    'options' => ['placeholder' => 'Seleccione quien revisa...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ]
                ])->label('¿Quién revisa?')
                ?>
            </div>
            <div class="col-md-3">
                <?=
                $form->field($model, 'quien_aprueba_id')->widget(Select2::className(), [
                    'data' => $listaProf,
                    'options' => ['placeholder' => 'Seleccione quien aprueba...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ]
                ])->label('¿Quién Aprueba?')
                ?>
            </div>
            <div class="col-md-3">
                <?php
                if ($model->isNewRecord) {
                    echo $form->field($model, 'estado')->hiddenInput(['value' => 'CONSTRUYENDOSE'])->label(false);
                } else {
                    echo $form->field($model, 'estado')->textInput(['maxlength' => true]);
                }
                ?>

            </div>


        </div>



        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'objetivo_unidad')->textarea(['rows' => 6])->label('Objetivo de la unidad: ') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'ac_necesidad_atendida')->textarea(['rows' => 6])->label('Especificación de la necesidad educativa atendida:') ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'ac_adaptacion_aplicada')->textarea(['rows' => 6])->label('Especificación de la adaptación aplicada') ?>
            </div>
        </div>


        <?= $form->field($model, 'bibliografia')->textarea(['rows' => 6])->label('Bibliogarfía/Webgrafía (Utilizar normas APA VI edición)') ?>

        <?= $form->field($model, 'observaciones')->textarea(['rows' => 6])->label('Observaciones') ?>





        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
        } else {
            echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false);
        }
        ?>

        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
        } else {
            echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
        }
        ?>

        <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

        <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>



        <?php ActiveForm::end(); ?>

    </div>
</div>
