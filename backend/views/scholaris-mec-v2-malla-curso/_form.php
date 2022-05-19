<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaCurso */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-mec-v2-malla-curso-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo $form->field($model, 'malla_id')->hiddenInput(['value' => $modelMalla->id])->label(false);
    ?>

    <?php
    $data = ArrayHelper::map($cursos, 'id', 'name');
    echo $form->field($model, 'curso_id')->widget(Select2::className(), [
        'data' => $data,
        'options' => ['placeholder' => 'Seleccione curso...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <div class="form-group">
<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
