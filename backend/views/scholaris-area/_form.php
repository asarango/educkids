<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArea */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$modelUsuario = \backend\models\ResUsers::find()->where(['login' => $usuario])->one();
$fecha = date("Y-m-d H:i:s");

?>

    <div class="scholaris-area-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'create_uid')->hiddenInput(['value' => $modelUsuario->id])->label(false) ?>

        <?= $form->field($model, 'create_date')->hiddenInput(['value' => $fecha])->label(false) ?>

        <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $modelUsuario->id])->label(false) ?>

        <?= $form->field($model, 'write_date')->hiddenInput(['value' => $fecha])->label(false) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Nombre Area') ?>

        <?= $form->field($model, 'period_id')->hiddenInput(['value' => 'z'])->label(false) ?>

        <?= $form->field($model, 'idcategoriamateria')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'section_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'estado_codigo')->hiddenInput(['maxlength' => true])->label(false) ?>

        <?= $form->field($model, 'promedia')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'codigo')->hiddenInput(['maxlength' => true])->label(false) ?>

        <?= $form->field($model, 'ministeriable')->hiddenInput(['value' => 1])->label(false) ?>

        <?= $form->field($model, 'nombre_mec')->textInput(['maxlength' => true])->label('Nombre MEC') ?>

        <?php $var = [ 'PEP' => 'PEP','PAI' => 'PAI', 'DIPLOMA' => 'DIPLOMA'] ?>

<!-- $form->field($model, 'horario_id')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]); -->
        <?= $form->field($model, 'seccion_general')->dropDownList($var,['prompt' => 'Seleccionar'])->label('Sección') ?>

        <?= $form->field($model, 'orden')->hiddenInput()->label(false) ?>

        <div class="form-group" style="margin-top: 10px">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
