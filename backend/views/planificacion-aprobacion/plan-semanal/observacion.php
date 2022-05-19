<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesor */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Observacion Plan Semanal: '.$model->semana->nombre_semana
        .' / '.$model->semana->bloque->name
        .' / '.$modelComparte->nombre
        ;
//$this->params['breadcrumbs'][] = $this->title;
$this->params['breadcrumbs'][] = ['label' => 'Plan Semanal', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="plan-semanal-observacion">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'observacion')->textarea(['rows' => 10]) ?>

    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
