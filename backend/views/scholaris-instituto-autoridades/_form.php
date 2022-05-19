<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisInstitutoAutoridades */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-instituto-autoridades-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'periodo_id')->hiddenInput(['value' => $periodoId])->label(FALSE) ?>

        <?= $form->field($model, 'instituto_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

        <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'cargo')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'tipo_autoridad')->dropDownList([
                        'rector' => 'RECTOR',
                        'subrector' => 'SUBDIRECTOR',
                        'inspector' => 'INSPECTOR',
                    ]) 
        ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>