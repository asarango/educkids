<?php

use Illuminate\Support\Facades\Validator;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ScholarisArchivosprofesor */
/* @var $form yii\widgets\ActiveForm */

$fecha = date("Y-m-d");

?>

<div class="scholaris-archivosprofesor-form">
    <!-- /***** VISUALIZACION DE ARCHVIOS  ***/ -->


    <div class="container-fluid">
        <?php

        if ($model->isNewRecord) {
            $urlPost = 'scholaris-archivosprofesor/create';
        } else {
            $urlPost = 'scholaris-archivosprofesor/update';
        }

        $form = ActiveForm::begin([
            'method' => 'post',
            'action' => [$urlPost, 'id' => $modelActividad->id],
            'options' => ['enctype' => 'multipart/form-data']
        ]);
        ?>
        <div class="row"  >
            <div class="col-lg-1">
                <!-- Orden -->
                <?= $form->field($model, 'orden')->textInput(['maxlength' => 1]) ?>
            </div>
            <div class="col-lg-8">
                <!-- titulo -->
                <?= $form->field($model, 'nombre_archivo')->textInput(['maxlength' => true]); ?>
            </div>
            <div class="col-lg-1">
                    <div class="form-check form-switch ">
                        <?= $form->field($model, 'publicar')->checkbox(['class' => 'form-check-input']) ?>
                    </div> 
            </div>
        </div>
        <!-- Texto -->
        <div>
            <?= $form->field($model, 'texto')->textarea(); ?>

            <script>
                CKEDITOR.replace("scholarisarchivosprofesor-texto", {
                    //toolbar: [ 'bold', 'italic', 'link', 'undo', 'redo', 'numberedList', 'bulletedList' ]
                    customConfig: "/ckeditor_settings/config.js"
                });
            </script>
        </div>
        <br>
        <!-- Id Actvidad -->
        <?= $form->field($model, 'idactividad')->hiddenInput(['value' => $modelActividad->id])->label(False) ?>
        <!-- Fecha -->
        <?php
        if ($model->isNewRecord) {
            echo $form->field($model, 'fechasubido')->hiddenInput(['value' => $fecha])->label(FALSE);
        } else {
            echo $form->field($model, 'fechasubido')->hiddenInput()->label(FALSE);
            echo $form->field($model, 'id')->hiddenInput()->label(FALSE);
        }
        ?>
        <table class="table table-striped table-hover table-responsive">
            <tr>
                <td>
                    <?= $form->field($model, 'archivo')->fileInput(['multiple' => true]) ?>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-success']) ?>
                    </div>
                </td>
            </tr>
        </table>
        <?php ActiveForm::end(); ?>
    </div>
</div>