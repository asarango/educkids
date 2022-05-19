<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Iniciar Sesión';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="site-login">

    <!--    <div class="row">
            <div class="col-lg-12 col-md-12">
                <img src="ISM/login/ISM-Color-PNG.png" width="200px" class="img-thumbnail">
            </div>
        </div>-->

    <div class="m-0 vh-100 row justify-content-center align-items-center">
        <!--        <div class="col-auto p-5 bg-danger text-center">
                    CENTRAR DIV
                </div>-->

        <div class="card col-lg-5 p-5" style="box-shadow: 20px 20px 20px -20px black">

            <img src="ISM/login/ISM-Color-PNG.png" width="250px" class="">


            <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => '']); ?>
            <!--<form class="login100-form validate-form">-->

            <div class="form-group">
                <label for="username" class="form-label">Usuario:</label>
                <?=
                        $form->field($model, 'username')
                        ->textInput(['autofocus' => true, 'class' => 'form-control', 'placeholder' => 'Usuario'])
                        ->label(false)
                ?>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <?=
                        $form->field($model, 'password')
                        ->passwordInput(['class' => 'form-control', 'placeholder' => 'Contraseña'])
                        ->label(false)
                ?>
            </div>


            <div class="wrap-input100 validate-input" data-validate="Enter password"><br>
                    <!--<input class="input100" type="password" name="pass" placeholder="Password">-->                

                <span class="focus-input100" data-placeholder="&#xf191;"></span>
            </div>

            <?= Html::submitButton('Iniciar Sesión', ['class' => 'my-btn my-primary', 'name' => 'login-button']) ?>

            </form>
            <?php ActiveForm::end(); ?>
        </div>

    </div>


</div>

