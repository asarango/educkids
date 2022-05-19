<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Area */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-area-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'malla_id')->hiddenInput(['value' => $modelMalla->id])->label(false) ?>

        <?=
        $form->field($model, 'tipo')->dropDownList([
            'NORMAL' => 'NORMAL',
            'PROYECTOS' => 'PROYECTOS',
            'COMPORTAMENTAL' => 'COMPORTAMENTAL'
        ])
        ?>

        <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

<?php ActiveForm::end(); ?>

    </div>
</div>
