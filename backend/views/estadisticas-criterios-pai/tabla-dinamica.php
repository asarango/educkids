<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Estadísticas de criterios PAI - Tabla dinámica';

?>

<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/tabla-periodica.png" width="64px" style="" class="img-thumbnail"></h4>
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
                    <?= Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="far fa-file"></i> Cuadro general</span>', ['index'], ['class' => 'link']); ?>
                    |
                </div>
                <!-- fin de primeros botones -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- espacio para menu alterno -->
                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- comienza cuerpo  -->
            <div class="row" style="margin-top: 15px; margin-bottom: 15px; padding: 20px;">

                <div class="card">
                    <iframe width="100%" height="600" src="https://datastudio.google.com/embed/reporting/9a32ae1b-6aaf-4b5d-91db-b2af7bc45ba8/page/VBlhC" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>

            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>