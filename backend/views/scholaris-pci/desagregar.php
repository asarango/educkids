<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use backend\models\ScholarisPeriodo;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMateria */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Desagregarndo destreza:' . $model->curso_subnivel_nombre
        . ' / CÓDIGO: ' . $model->destreza_codigo;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Materias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="scholaris-pci-desagregar">

    <div class="container">

        <div class="alert alert-info">
            <strong><?= $model->destreza_codigo ?></strong>
<?= $model->destreza_detalle ?>
        </div>


<?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'evaluacion_id')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'curso_subnivel_id')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'curso_subnivel_nombre')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'destreza_id')->hiddenInput(['maxlength' => true])->label(false) ?>
        <?= $form->field($model, 'destreza_codigo')->hiddenInput(['maxlength' => true])->label(false) ?>

        <?= $form->field($model, 'destreza_detalle')->textarea(['rows' => '6']) ?>

        <?= $form->field($model, 'desagregado')->hiddenInput(['value' => true])->label(false) ?>

        <div class="form-group">
<?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

<?php ActiveForm::end(); ?>

    </div>
</div>
