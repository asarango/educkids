<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisIntitutoDatosGeneralesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-intituto-datos-generales-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'instituto_id') ?>

    <?= $form->field($model, 'direccion') ?>

    <?= $form->field($model, 'codigo_amie') ?>

    <?= $form->field($model, 'telefono') ?>

    <?php // echo $form->field($model, 'provincia') ?>

    <?php // echo $form->field($model, 'canton') ?>

    <?php // echo $form->field($model, 'parroquia') ?>

    <?php // echo $form->field($model, 'correo') ?>

    <?php // echo $form->field($model, 'sitio_web') ?>

    <?php // echo $form->field($model, 'sostenimiento') ?>

    <?php // echo $form->field($model, 'regimen') ?>

    <?php // echo $form->field($model, 'modalidad') ?>

    <?php // echo $form->field($model, 'niveles_curriculares') ?>

    <?php // echo $form->field($model, 'subniveles') ?>

    <?php // echo $form->field($model, 'distrito') ?>

    <?php // echo $form->field($model, 'circuito') ?>

    <?php // echo $form->field($model, 'jornada') ?>

    <?php // echo $form->field($model, 'horario_trabajo') ?>

    <?php // echo $form->field($model, 'local') ?>

    <?php // echo $form->field($model, 'genero') ?>

    <?php // echo $form->field($model, 'ejecucion_desde') ?>

    <?php // echo $form->field($model, 'ejecucion_hasta') ?>

    <?php // echo $form->field($model, 'financiamiento') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
