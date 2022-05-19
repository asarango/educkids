<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\OpCourse;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaCurso */
/* @var $form yii\widgets\ActiveForm */

$institutoId = Yii::$app->user->identity->instituto_defecto;
$periodoId = Yii::$app->user->identity->periodo_id;

$modelCursos = OpCourse::find()
        ->innerJoin("op_section","op_section.id = op_course.section")
        ->innerJoin("scholaris_op_period_periodo_scholaris", "scholaris_op_period_periodo_scholaris.op_id = op_section.period_id")
        ->innerJoin("scholaris_periodo","scholaris_periodo.id = scholaris_op_period_periodo_scholaris.scholaris_id")
        ->where([
                 "scholaris_periodo.id" => $periodoId,
                 "op_course.x_institute" => $institutoId
        ])->all();

//echo $modelMalla->id;

?>

<div class="scholaris-malla-curso-form">
    
    <div class="container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'malla_id')->hiddenInput(['value' => $modelMalla->id])->label(false) ?>

    <?php
    
    $listData = ArrayHelper::map($modelCursos, 'id', 'name');
    
    echo $form->field($model, 'curso_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione curso...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
