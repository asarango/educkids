<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mi hoja de vida';
?>

<div class="mi-perfil-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
        <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/curriculum-vitae-y-cv.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
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
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="far fa-file"></i> Perfiles de usuario</span>',
                        ['operacion/index'],
                        ['class' => 'link']
                    ); ?>
                    |
                </div>

                <div class="col-lg-2 col-md-2" style="text-align: right;">
                    
                </div>
            </div>

            <!-- inicia embebida de usuario -->
            <iframe src="https://104.128.64.217/cv-ism/" style="height: 65vh;"></iframe>
            <!-- finaliza embebida de usuario -->


        </div>
    </div>
</div>