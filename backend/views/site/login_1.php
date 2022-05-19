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



    <div class="limiter">
        <div class="container-login100" style="background-image: url('Login_v3/images/odoo.jpg');">
            <div class="wrap-login100">
                <?php $form = ActiveForm::begin(['id' => 'login-form','class' => 'login100-form validate-form']); ?>
                <!--<form class="login100-form validate-form">-->
                    <span class="login100-form-logo">
                        <img src="Login_v3/images/logoFondoTransparente.Png" width="100px">
                        
                    </span>

                    <span class="login100-form-title p-b-34 p-t-27">
                        <?= Html::encode($this->title) ?>
                    </span>

                <div class="wrap-input100 validate-input" data-validate = "Enter username"><br>
                         <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'class' => 'input100', 'placeholder' => 'Usuario'])->label(false) ?>
                        <!--<input class="input100" type="text" name="username" placeholder="Username">-->
                        <span class="focus-input100" data-placeholder="&#xf207;"></span>
                    </div>

                <div class="wrap-input100 validate-input" data-validate="Enter password"><br>
                        <!--<input class="input100" type="password" name="pass" placeholder="Password">-->
                        <?= $form->field($model, 'password')->passwordInput(['class' => 'input100','placeholder' => 'Clave'])->label(false) ?>

                
                        <span class="focus-input100" data-placeholder="&#xf191;"></span>
                    </div>

                    

                    <div class="container-login100-form-btn">
                                                 <?= Html::submitButton('Ingresar', ['class' => 'login100-form-btn', 'name' => 'login-button']) ?>
                    </div>

<!--                    <div class="text-center p-t-90">
                        <a class="txt1" href="#">
                            Forgot Password?
                        </a>
                    </div>-->
                </form>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>



</div
</div>

