<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanPduParametros */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-pdu-parametros-form">

    <div class="container">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_parametro')->dropDownList([
                        'EJES' => 'EJES',
                        'VALOR INSTITUCIONAL' => 'VALOR INSTITUCIONAL',
                        'RECURSOS' => 'RECURSOS',
                        'ACTIVIDADES' => 'ACTIVIDADES'
                    ]) 
    ?>

    <?= $form->field($model, 'nombre')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'estado')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
