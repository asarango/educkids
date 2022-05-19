<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Usuario */

$this->title = 'Editando Usuario: ';

?>

<div class="usuario-update">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/empleados.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <b><?= $model->usuario ?></b>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Perfiles de usuario</span>',
                        ['rol/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="far fa-file"></i> Usuarios</span>',
                        ['index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Permisos</span>',
                        ['operacion/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- espacio disponible para links a la drecha -->
                </div>
            </div>            

            <!-- inicia formulario de update Usuario -->
            <div class="row p-5">
                <div class="card p-3">
                    <p>Datos de: <b> <?= $model->usuario ?></b></p>
                    <?= $this->render('_form', [
                        'model' => $model,                        
                    ]) ?>
                </div>
            </div>
            <!-- fin formulario de update Usuario  -->

            <!-- inicia fomrulario de subir avatar -->
            <div class="row p-5">
                <div class="card p-3">
                    <p>Firma del usuario: <b> <?= $model->usuario ?></b></p>
                    <?= $this->render('_upload-firma', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
            <!-- fin fomrulario de subir avatar -->

            
        </div>
    </div>
</div>