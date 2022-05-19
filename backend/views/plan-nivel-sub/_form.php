<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\OpCourseTemplate;

/* @var $this yii\web\View */
/* @var $model backend\models\PlanNivelSub */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="plan-nivel-sub-form">

    <div class="container">
    <?php $form = ActiveForm::begin(); ?>

    <?php 
    $lista = OpCourseTemplate::find()->orderBy('name')->all();
    $listData = ArrayHelper::map($lista, 'id', 'name');
    
    echo $form->field($model, 'curso_template_id')->widget(Select2::className(),[
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Curso...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); 
            
    ?>

    <?= $form->field($model, 'nivel_id')->hiddenInput(['value' => $id])->label(FALSE) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
