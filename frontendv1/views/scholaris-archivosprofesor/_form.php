<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ScholarisArchivosprofesor */
/* @var $form yii\widgets\ActiveForm */

$fecha = date("Y-m-d");

?>

<div class="scholaris-archivosprofesor-form">

    <div class="container">

    <?php $form = ActiveForm::begin([
                                    'options' => ['enctype' => 'multipart/form-data']
                                    ]); 
    ?>

    <?= $form->field($model, 'idactividad')->hiddenInput(['value' => $modelActividad->id])->label(FALSE) ?>

    <?php //$form->field($model, 'archivo')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'archivo')->fileInput() ?>

        
        <br><br><br>
        
    <?php 
    if($model->isNewRecord){
        echo $form->field($model, 'fechasubido')->hiddenInput(['value' => $fecha])->label(FALSE);
    }else{
        echo $form->field($model, 'fechasubido')->hiddenInput()->label(FALSE);
    }
    ?>

    <?= $form->field($model, 'nombre_archivo')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Subir archivo', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
