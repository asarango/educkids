<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login" style="padding: 30px">



    <div class="row">
        <div class="col-lg-3 col-md-3 text-center">
            <div class="divderecho" style="padding-top: 30px; padding-bottom: 30px;">
                <h3><strong>BIENVENIDOS A</strong></h3>
                <img src="plantillav1/login/images/educandilogo.png" width="200px" align="">
                
                <hr>

                <p>Usted va a ingresar con el usuario: <?= $usuario ?></p>    
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->hiddenInput(['value' => $usuario])->label(FALSE) ?>

                <?= $form->field($model, 'password')->hiddenInput(['value' => 12345])->label(FALSE) ?>

                <?= $form->field($model, 'rememberMe')->hiddenInput()->label(FALSE) ?>

                <div class="form-group">
                    <?= Html::submitButton('Ingresar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
        <div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-3 col-md-3"></div>
    </div>
</div>
