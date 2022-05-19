<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Menu;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\Operacion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operacion-form">

    <?php $form = ActiveForm::begin(); ?>


    <?php
    $lista = Menu::find()->orderBy("nombre")->all();
    $listData = ArrayHelper::map($lista, 'id', 'nombre');
    echo $form->field($model, 'menu_id')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione menú...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'operacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
