<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\ResUsers;
use backend\models\Rol;
use backend\models\OpInstitute;
use backend\models\ScholarisPeriodo;

/* @var $this yii\web\View */
/* @var $model backend\models\Usuario */
/* @var $form yii\widgets\ActiveForm */
?>

 


<div class="upload-avatar">

    <div class="container">

        <?php
        $form = ActiveForm::begin([
            'action' => ['upload-firma'],
            'method' => 'post',
            'options' => ['enctype' => 'multipart/form-data']
        ]);
        ?>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <img src="ISM/firmas/<?= $model->firma ?>" width="200px" class="img-thumbnail">
            </div>

            <div class="col-lg-4 col-md-4"><?= $form->field($model, 'firma')->fileInput() ?></div>

            <div class="col-lg-4 col-md-4"><?= $form->field($model, 'usuario')->hiddenInput(['value' => $model->usuario])->label(false) ?></div>


        </div> <!-- Fin de primer row -->

        <br>
        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>

