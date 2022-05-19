<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArea */
/* @var $form yii\widgets\ActiveForm */

//echo '<pre>';
//print_r($model);
//die();
?>

<div class="scholaris-area-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--$form->field($model, 'create_uid')->hiddenInput(['value' => $modelUsuario->id])->label(false)-->


    <!--$var = [ 'PEP' => 'PEP','PAI' => 'PAI', 'DIPLOMA' => 'DIPLOMA']-->
    <!-- $form->field($model, 'horario_id')->dropDownList($var, ['prompt' => 'Seleccione Uno' ]); -->
    <?php
    $var = ['EJE-TRANSVERSAL' => 'EJE TRANSVERSAL',
        'ACTV-APRENDIZAJE' => 'ACTV.APRENDIZAJE',
        'RECURSO' => 'RECURSO',
        'TECNICA-INSTRUMENTO' => 'TÉCNICA E INSTRUMENTO',
        'REFLEXION' => 'REFLEXIÓN'
            ]
    ?>
    <?= $form->field($model, 'tipo')->dropDownList($var, ['prompt' => 'Seleccione Uno']); ?>

    <?php
    $var2 = ['' => 'EJE TRANSVERSAL',
        'aprend-proyecto' => 'APRENDIZAJE BASADO EN PROYECTOS',
        'aprend-colaboativo' => 'APRENDIZAJE COLABORATIVO',
        'aprend-induccion' => 'APRENDIZAJE POR INDUCCIÓN',
        'recurso-didactico' => 'RECURSO DIDÁCTICO',
        'recurso-digital' => 'RECURSO DIGITAL',
        'eval-formativa' => 'EVALUACIÓN FORMATIVA',
        'eval-sumativa' => 'EVALUACIÓN SUMATIVA',
        'antes' => 'ANTES',
        'mientras' => 'MIENTRAS',
        'despues' => 'DESPUÉS'
            ]
    ?>
    <?= $form->field($model, 'categoria')->dropDownList($var2, ['prompt' => 'Seleccione Uno']); ?>

    <?= $form->field($model, 'opcion')->textarea(['rows' => '3'])->label('DESCRIPCIÓN') ?>

    <?php $var3 = ['PEP' => 'PEP', 'PAI' => 'PAI', 'DIPLOMA' => 'DIPLOMA'] ?>
    <?= $form->field($model, 'seccion')->dropDownList($var3, ['prompt' => 'Seleccione Uno']); ?>

    <?=
    $form->field($model, 'estado')->checkBox(['label' => 'Estado', 'data-size' => 'small', 'class' => 'form-checkbox'
        , 'style' => 'margin-bottom:10px;margin-left:4px', 'id' => 'active']);
    ?>

    <div class="form-group" style="margin-top: 10px">
        <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
