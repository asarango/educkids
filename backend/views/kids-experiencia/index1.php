<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Experiencia Microcurricular';
$this->params['breadcrumbs'][] = $this->title;

//  echo '<pre>';
// print_r($micro);
//  print_r($objetivosIntegradores);
// print_r($objetivosDisponibles);
// print_r($objetivo);
// print_r($objetivosSeleccionados);
// print_r($datos);
//  die('aqui die');
?>

<div class="kids-experiencia-index1">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12">

                <!-- comienza encabezado -->
                <div class="row" style="background-color: #ccc; font-size: 12px">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            <?= $this->title ?>
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                        </p>
                    </div>
                </div>

                <div class="row"  style="background-color:#fff; font-size:12px">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="row">

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <strong><i class="fas fa-user"></i> DOCENTE: </strong>
                                <?php
                                foreach ($datos['docentes'] as $docen) {
                                    echo $docen['docente'] . ' ';
                                }
                                ?>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <strong><i class="fas fa-list-ol"></i> SUBNIVEL: </strong><?= $datos['subnivel'] ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <strong><i class="fas fa-lightbulb"></i> EXPERIENCIAS DE APRENDIZAJE: </strong><?= $micro['experiencia'] ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <strong><i class="fas fa-calendar"></i> FECHA INICIO: </strong><?= $micro['fecha_inicia'] ?>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <strong><i class="fas fa-calendar"></i> FECHA FIN: </strong><?= $micro['fecha_termina'] ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
                <div class="row" style="background-color: #fff; margin-top:20px">
                    <div class="col-md-12 col-sm-12" style="padding-left:0px">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">PLAN DE EXPERIENCIA</button>
                                <button class="nav-link" id="objetivo-tab" data-bs-toggle="tab" data-bs-target="#nav-objetivo-tab" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">OBJETIVOS INTEGRADORES</button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">OBSERVACIONES DEL COORDINADOR</button>
                            </div>
                        </nav>
                    </div>
                </div>

                <div class="row" style="margin: 20px">
                    <div class="col-md-12 col-sm-12">

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                <?=
                                $this->render('plan-experiencia', [
                                    'micro' => $micro
                                ]);
                                ?>
                            </div>
                            <div class="tab-pane fade" id="nav-objetivo-tab" role="tabpanel" aria-labelledby="nav-objetivo-tab">
                                <?=$this->render('objetivos-integradores',[
                                    'micro' => $micro,
                                    'objetivosDisponibles' => $objetivosDisponibles,
                                    'objetivosSeleccionados' => $objetivosSeleccionados
                                ]); ?>
                            </div>
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                <?= $this->render('observaciones-coordinador'); ?>
                            </div>
                        </div> 


                    </div>
                </div>
                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>