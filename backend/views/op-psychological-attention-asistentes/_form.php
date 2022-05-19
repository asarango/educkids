<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttentionAsistentes */
/* @var $form yii\widgets\ActiveForm */
$today = date("Y-m-d H:i:s");
?>

<div class="op-psychological-attention-asistentes-form">

    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <?php $form = ActiveForm::begin(); ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'create_uid')->hiddenInput(['value' => $userId])->label(false);
            } else {
                echo $form->field($model, 'create_uid')->hiddenInput()->label(false);
            }
            ?>

            <?php
            if ($model->isNewRecord) {
                echo $form->field($model, 'create_date')->hiddenInput(['value' => $today])->label(false);
            } else {
                echo $form->field($model, 'create_date')->hiddenInput()->label(false);
            }
            ?>

            <?= $form->field($model, 'name')->textInput()->label('Firma / CI / Nombre / Parentesco') ?>

            <?= $form->field($model, 'write_uid')->hiddenInput(['value' => $userId])->label(false) ?>

            <?php 
                if($model->isNewRecord){
                    echo $form->field($model, 'psychological_attention_id')->hiddenInput(['value' => $attentionId])->label(false);
                }else{
                    echo $form->field($model, 'psychological_attention_id')->hiddenInput()->label(false) ;
                }
                
            ?>

            <?= $form->field($model, 'write_date')->hiddenInput(['value' => $today])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton('Agregar', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-4"></div>

    </div>


</div>
