<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisBloqueComparte;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMalla */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-malla-form">

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'codigo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'periodo_id')->hiddenInput(['value' => $modelPeriodo->id])->label(false) ?>

    <?php
    $listData = ArrayHelper::map($modelSection, 'id', 'name');
    echo $form->field($model, 'section_id')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione sección...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <?= $form->field($model, 'nombre_malla')->textInput(['maxlength' => true]) ?>

    <?php
    $lista = ScholarisBloqueComparte::find()
            ->select(["id", "concat(nombre, ' ', valor) as nombre"])
            ->all();
    $listData = ArrayHelper::map($lista, 'id', 'nombre');
    echo $form->field($model, 'tipo_uso')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione tipo de bloque...'],
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
