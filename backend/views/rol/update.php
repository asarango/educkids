<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Rol */


$this->title = 'Actualizando perfil';

?>


<div class="rol-update">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">

            <!-- cabecera de pantalla -->
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <b><?= $model->rol ?></b>
                </div>
            </div>
            <hr>


            <div class="row">
                <div class="col-lg-10 col-md-10">
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>',
                        ['site/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                    <?= Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Volver a perfiles</span>',
                        ['index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    <!-- aqui van iconos de acciones del controlador -->
                </div>
            </div>
            <!-- fin de cabecera de pantalla -->

            <!-- inicia cuerpo de pantalla -->

            <div class="row p-3">
                <?= $this->render('_form', [
                    'model' => $model,
                    'listaOperaciones' => $listaOperaciones,
                ]) ?>
            </div>

            <!-- finaliza cuerpo de pantalla -->
        </div>
    </div>

</div>

<div class="rol-update">

    <h1><?= Html::encode($this->title) ?></h1>




</div>