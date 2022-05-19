<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanCurriculoObjetivos */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-curriculo-objetivos-form">
    <div class="container">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'distribucion_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

        <?= $form->field($model, 'codigo_ministerio')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'descripcion')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'tipo_objetivo')->dropDownList([                                                    
                                                    'nivel' => 'POR NIVEL'
                                                 ]); 
         ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
