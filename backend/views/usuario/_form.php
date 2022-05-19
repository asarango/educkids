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

<div class="usuario-form">

    <div class="container">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <?php
                $lista = ResUsers::find()->where(['active' => TRUE])->all();
                $listData = ArrayHelper::map($lista, 'login', 'login');

                echo $form->field($model, 'usuario')->widget(Select2::className(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Seleccione usuario...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>

            <div class="col-lg-4 col-md-4"><?= $form->field($model, 'clave')->passwordInput() ?></div>
            <div class="col-lg-4 col-md-4"><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>

        </div> <!-- Fin de primer row -->



        <?= $form->field($model, 'auth_key')->hiddenInput(['rows' => 6])->label(false) ?>

        <?= $form->field($model, 'access_token')->hiddenInput(['rows' => 6])->label(false) ?>

        <?= $form->field($model, 'avatar')->hiddenInput(['rows' => 6])->label(false) ?>
        
        <?= $form->field($model, 'firma')->hiddenInput(['rows' => 6])->label(false) ?>

        <div class="row">
            <div class="col-lg-4 col-md-4">
                <?php
                $lista = Rol::find()->all();
                $listData = ArrayHelper::map($lista, 'id', 'rol');
                echo $form->field($model, 'rol_id')->widget(Select2::className(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Seleccione perfil...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>

            <div class="col-lg-4 col-md-4">
                <?php
                $lista = OpInstitute::find()->all();
                $listData = ArrayHelper::map($lista, 'id', 'name');

                echo $form->field($model, 'instituto_defecto')->widget(Select2::className(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Seleccione instituto...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ])
                ?>
            </div>
            <div class="col-lg-4 col-md-4">
                <?php
                $lista = ScholarisPeriodo::find()->all();
                $listData = ArrayHelper::map($lista, 'id', 'nombre');
                echo $form->field($model, 'periodo_id')->widget(Select2::className(), [
                    'data' => $listData,
                    'options' => ['placeholder' => 'Seleccione periodo...'],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-3 col-md-3"><?= $form->field($model, 'activo')->checkbox() ?></div>
        </div>




        <br>
        <div class="form-group">
            <?= Html::submitButton('Grabar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>