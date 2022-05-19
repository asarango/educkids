<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estadísticas de criterios PAI ';

?>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    |
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                    |
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Empezar</span>', ['por-curso'], ['class' => 'link']); ?>
                    |
                </div>
                <!-- fin de primeros botones -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- espacio para menu alterno -->
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- comienza cuerpo  -->
            <div class="row" style="margin-top: 15px; margin-bottom: 15px">

                <!-- inicia submenu de opciones de cuadros -->
                <div class="col-lg-3 col-md-3">
                    <div class="row" style="text-align: right;">
                        <i class="fas fa-square" style="color: #ff9e18;"> FORMATIVAS</i>
                    </div>
                    <div class="row" style="margin-top: 10px; text-align: right;">
                        <i class="fas fa-square" style="color: #9e28b5;"> SUMATIVAS&nbsp;&nbsp;&nbsp; </i>
                    </div>
                </div>
                <!-- fin submenu de opciones de cuadros -->

                <!-- ####################   inicia reporte ######################## -->
                <div class="col-lg-9 col-md-9" style="border-left: solid 1px #ccc;" id="div_detalle">
                    <div class="card p-2">
                        <?php
                        echo $this->render('total', [
                            'serializado' => $serializado,
                            'data' => $data
                        ]);
                        ?>
                    </div>
                </div>
                <!-- #################### fin de reporte ####################### -->

            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>
