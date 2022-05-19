<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ScholarisArea;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaArea */
/* @var $form yii\widgets\ActiveForm */

$periodoId = Yii::$app->user->identity->periodo_id;
$modelPeriodo = backend\models\ScholarisPeriodo::find()->where(['id' => $periodoId])->one();

?>

<div class="scholaris-malla-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'malla_id')->hiddenInput(['value' => $id])->label(false) ?>

    <?php
//    $lista = ScholarisArea::find()
//            ->select(['id', "concat(name,' ',period_id) as name"])
////            ->where(['period_id' => $modelPeriodo->codigo])
//            ->where(['period_id' => '2018-2019'])
//            ->all();
    $listData = ArrayHelper::map($modelAreas, 'id', 'name');
    echo $form->field($model, 'area_id')->widget(Select2::className(), [
        'data' => $listData,
        'options' => ['placeholder' => 'Seleccione Área...'],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'tipo')->dropDownList([
        'NORMAL' => 'NORMAL',
        'OPTATIVAS' => 'OPTATIVAS',
        'PROYECTOS' => 'PROYECTOS',        
        'DHI' => 'DHI',        
        'COMPORTAMIENTO' => 'COMPORTAMIENTO'
            ]
    );
    ?>

    <?= $form->field($model, 'total_porcentaje')->textInput() ?>
    
    <?= $form->field($model, 'orden')->textInput() ?>

    <?= $form->field($model, 'se_imprime')->checkbox() ?>

    <?= $form->field($model, 'promedia')->checkbox() ?>



    <?= $form->field($model, 'es_cuantitativa')->checkbox() ?>    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
