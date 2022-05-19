<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = 'Asignaturas - ' . $asignaturas[0]->opCourseTemplate->opCourses[0]->name;
$this->title = 'Asignaturas - ' . $asignaturas[0]['name'];
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($asignaturas);
//    print_r($asignaturas[0]->opCourseTemplate->opCourses[0]->name);
//die();
?>
<div class="planificacion-aprobacion-asignaturas">

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
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Aprobación Planificaciónes</span>',
                            ['index'],
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
            <div class="row" style="margin-top: 10px;padding-left: 25px">

                <div class="col-lg-12 col-md-12" style="text-align: center">
                    <h6>-- Indicadores --</h6>
                    <span class="badge rounded-pill bg-success">Aprobados</span>
                    <span class="badge rounded-pill bg-primary">Devueltos</span>
                    <span class="badge rounded-pill bg-warning text-dark">Por Revisar</span>
                    <span class="badge rounded-pill bg-danger">Faltan</span>
                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="table table-responsive">
                        <table class="table table-hover table-striped my-text-medium">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ASIGNATURA</th>
                                    <th>ESTADO</th>
                                    <th>FECHA ENVIO</th>
                                    <th>FECHA APROBADO</th>
                                    <th>FECHA DEVUELTO</th>
                                    <th>ACCIÓN</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($asignaturas as $asignatura) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php
                                            $notify = '';
                                            if ($asignatura['estado'] == 'EN_COORDINACION') {
                                                $notify = 'warning';
                                            }
                                            if ($asignatura['estado'] == 'DEVUELTO') {
                                                $notify = 'primary';
                                            }
                                            if ($asignatura['estado'] == 'INICIANDO') {
                                                $notify = 'danger';
                                            }
                                            if ($asignatura['estado'] == 'APROBADO') {
                                                $notify = 'success';
                                            }
                                            ?>
                                            <span class="badge rounded-pill bg-<?= $notify ?>"> </span>
                                        </td>
                                        <td><strong><?= $asignatura['materia'] ?></strong></td>
                                        <td><?= $asignatura['estado'] ?></td>
                                        <td><?= $asignatura['fecha_envio_coordinador'] ?></td>
                                        <td><?= $asignatura['fecha_aprobacion_coordinacion'] ?></td>
                                        <td><?= $asignatura['fecha_de_cambios'] ?></td>
                                        <td>
                                            <?=
                                            Html::a(
                                                    '<i class="fas fa-eye" style="color:#0a1f8f; font-size:18px"></i>',
                                                    ['detalle', 'cabecera_id' => $asignatura['id']]
                                            );
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

