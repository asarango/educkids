<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Aprobación de Planificaciones';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($detalle);
//die();
?>
<div class="planificacion-aprobacion-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>

                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">

                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left: 60px">

                <!--<div class="col-12 col-md-12 border">-->

                <div class="col-lg-12 col-md-12" style="text-align: center">
                    <h6>-- Indicadores --</h6>
                    <span class="badge rounded-pill bg-success">Aprobados</span>
                    <span class="badge rounded-pill bg-primary">Devueltos</span>
                    <span class="badge rounded-pill bg-warning text-dark">Por Revisar</span>
                    <span class="badge rounded-pill bg-danger">Faltan</span>
                </div>
                <br>

                <?php
                foreach ($detalle as $curso) {
                    ?>
                    <div class="card mb-3 zoom" style="width: auto; margin: 3px;background-color: #898b8d; color: white">
                        <div class="row g-0">
                            <div class="col-md-4" style="">
                                <img src="ISM/main/images/submenu/papeles.png" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body" >
                                    <h6 class="card-title">
                                        <strong>
                                            <?php 
                                            echo Html::a(
                                                $curso['curso'],
                                                ['asignaturas',
                                                    'template_id' => $curso['x_template_id']
                                                ],
                                                ['class' => 'btn-link','style' => 'color: #ab0a3d']
                                            ); 
                                            ?>
                                        </strong>
                                    </h6>
                                    <strong class="my-text-medium">Total Asignaturas:&nbsp;<span class="badge rounded-pill" style="background-color:#0a1f8f" >
                                            <?php echo $curso['total_materias'] ?>
                                    </span></strong>

                                </div>
                                <div class="card-footer">
                                    <?php
                                    foreach ($curso['totales'] as $total) {
                                        if ($total['estado'] == 'EN_COORDINACION') {
                                            ?>
                                            <span class="badge rounded-pill bg-warning text-dark"><?= $total['total'] ?></span>
                                            <?php
                                        }
                                        if ($total['estado'] == 'INICIANDO') {
                                            ?>
                                            <span class="badge rounded-pill bg-danger"><?= $total['total'] ?></span>
                                            <?php
                                        }
                                        if ($total['estado'] == 'DEVUELTO') {
                                            ?>
                                            <span class="badge rounded-pill bg-primary"><?= $total['total'] ?></span>
                                            <?php
                                        }
                                        if ($total['estado'] == 'APROBADO') {
                                            ?>
                                            <span class="badge rounded-pill bg-success"><?= $total['total'] ?></span>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                }
                ?>


                <!--</div>-->

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

