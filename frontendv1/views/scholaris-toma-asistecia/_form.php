<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTomaAsistecia */
/* @var $form yii\widgets\ActiveForm */


$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="scholaris-toma-asistecia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'paralelo_id')->hiddenInput(['value' => $paralelo])->label(false) ?>

    <?=
    $form->field($model, 'fecha')->widget(DatePicker::className(), [
        'name' => 'fecha',
        'value' => date('d-M-Y', strtotime('+2 days')),
        'options' => ['placeholder' => 'Seleccione fecha ...'],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true
        ]
    ]);
    ?>

    <?= $form->field($model, 'bloque_id')->hiddenInput(['value' => 0])->label(false) ?>

    <?= $form->field($model, 'hubo_clases')->checkbox(['value' => true]) ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 6]) ?>

    <?php
    if($model->isNewRecord){
        echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(false);
    }else{
        echo $form->field($model, 'creado_por')->hiddenInput(['maxlength' => true])->label(false) ;
    }
    ?>

    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false);
    }else{
        echo $form->field($model, 'creado_fecha')->hiddenInput()->label(false);
    }
    ?>

    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>

    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

    <div class="form-group">
<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
