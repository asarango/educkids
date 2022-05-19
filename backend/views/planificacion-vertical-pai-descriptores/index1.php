<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCriteriosEvaluacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '4.- Planificación Vertical Pai - Criterios';
$this->params['breadcrumbs'][] = $this->title;

$conceptoClaveActive = '';
$showConceptoClaveActive = '';

$conceptosRelacionadosActive = '';
$showConceptosRelacionadosActive = '';

$contextoGlobalActive = '';
$showContextoGlobalActive = '';

$enunciadoActive = '';
$showEnunciadoActive = '';

$objetivosActive = '';
$showObjetivosActive = '';

$habilidadesActive = '';
$showHabilidadesActive = '';

$contenidosActive = '';
$showContenidosActive = '';

if ($pestana == 'concepto_clave') {
    $conceptoClaveActive = 'active';
    $showConceptoClaveActive = 'show active';
}

if ($pestana == 'conceptos_relacionados') {
    $conceptosRelacionadosActive = 'active';
    $showConceptosRelacionadosActive = 'show active';
}

if ($pestana == 'contexto_global') {
    $contextoGlobalActive = 'active';
    $showContextoGlobalActive = 'show active';
}

if ($pestana == 'enunciado_indagacion') {
    $enunciadoActive = 'active';
    $showEnunciadoActive = 'show active';
}

if ($pestana == 'objetivos_especificos') {
    $objetivosActive = 'active';
    $showObjetivosActive = 'show active';
}

if ($pestana == 'habilidad_enfoque') {
    $habilidadesActive = 'active';
    $showHabilidadesActive = 'show active';
}

if ($pestana == 'contenidos') {
    $contenidosActive = 'active';
    $showContenidosActive = 'show active';
}
$condicionClass = new backend\models\helpers\Condiciones;


$condicionClass = new backend\models\helpers\Condiciones;
$estado = $bloqueUnidad->planCabecera->estado;
$isOpen = $bloqueUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$bloqueUnidad->settings_status);


?>
<div class="planificacion-vertical-pai-criterios-index">
    <!-- CABECERA -->
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px"  class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                          <?= 
                                '<h6>' . $bloqueUnidad->curriculoBloque->last_name . 
                                ' - ' . $bloqueUnidad->unit_title . 
                                ' | CURSO: ' . $bloqueUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name . 
                                '</h6>' ?>
                    </small>
                </div>
            </div>
            <!-- FIN DE CABECERA -->


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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fas fa-info-circle"></i> Detalle de temas</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $bloqueUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->


                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <hr>

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 10px; margin-left:1px;margin-right:1px; margin-bottom:5px">

                <ul class="nav nav-tabs my-text-small" id="myTab" role="tablist" style="background-color:#ccc;">
                    <!-- Concepto clave -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $conceptoClaveActive ?> " id="home-tab" data-bs-toggle="tab" data-bs-target="#home" 
                                type="button" role="tab" aria-controls="home" style="border-radius: 50% 0% 0 0" 
                                aria-selected="true">Concepto Clave 
                                    <?php
                                    if (count($conceptosClaveSeleccionados) == 0) {
                                        echo '<span class="badge rounded-pill bg-danger">
                                                <i class="fas fa-exclamation-triangle" style="color:white"></i>
                                              </span>';
                                    }
                                    ?>
                        </button>
                    </li>

                    <!-- Conceptos Relacionados -->
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $conceptosRelacionadosActive ?>" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" 
                                type="button" role="tab" aria-controls="profile" style="border-radius: 50% 0% 0 0" 
                                aria-selected="false">Conceptos Relacionados
                                    <?php
                                    if (count($conceptosRelacionadosSeleccionados) == 0) {
                                        echo '<span class="badge rounded-pill bg-danger">
                        <i class="fas fa-exclamation-triangle" style="color:white"></i>
                        </span>';
                                    }
                                    ?>

                        </button>
                    </li>

                    <!-- Contexto Global -->

                     <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $contextoGlobalActive ?> " id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" 
                                type="button" role="tab" aria-controls="contact" style="border-radius: 50% 0% 0 0"  
                                aria-selected="false">Contexto Global

                            <?php
                            if (count($contextoGlobalSeleccionados) == 0) {
                                echo '<span class="badge rounded-pill bg-danger">
                        <i class="fas fa-exclamation-triangle" style="color:white"></i>
                        </span>';
                            }
                            ?>
                        </button>
                    </li>

                    <!-- Enunciado de la indagación -->

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $enunciadoActive ?> " id="otro-tab" data-bs-toggle="tab" data-bs-target="#otro" 
                                type="button" role="tab" aria-controls="contact"  style="border-radius: 50% 0% 0 0"  
                                aria-selected="false">Enunciado de la indagación
                                    <?php
                                    if ($bloqueUnidad->enunciado_indagacion == null) {
                                        echo '<span class="badge rounded-pill bg-danger">
                                                <i class="fas fa-exclamation-triangle" style="color:white"></i>
                                                </span>';
                                    }
                                    ?>
                        </button>
                    </li>

                    <!-- Objetivos Específicos -->

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $objetivosActive ?>" id="objetivo-tab" data-bs-toggle="tab" data-bs-target="#objetivo" 
                                type="button" role="tab" aria-controls="objetivo"  style="border-radius: 50% 0% 0 0" 
                                aria-selected="false">Objetivos Específicos
                                    <?php
                                    if (count($criteriosSeleccionados) == 0) {
                                        echo '<span class="badge rounded-pill bg-danger">
                        <i class="fas fa-exclamation-triangle" style="color:white"></i>
                        </span>';
                                    }
                                    ?>
                        </button>
                    </li>

                    <!-- Habilidades de enfoques del aprendizaje -->

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $habilidadesActive ?> " id="habilidades-tab" data-bs-toggle="tab" data-bs-target="#habilidades" 
                                type="button" role="tab" aria-controls="habilidades"  style="border-radius: 50% 0% 0 0" 
                                aria-selected="false">Habilidades de enfoques del aprendizaje
                                    <?php
                                    if (count($habilidadesSeleccionadas) == 0) {
                                        echo '<span class="badge rounded-pill bg-danger">
                                                <i class="fas fa-exclamation-triangle" style="color:white"></i>
                                                </span>';
                                    }
                                    ?>
                        </button>
                    </li>

                    <!-- Temarios -->

                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?= $contenidosActive ?> " id="contenidos-tab" data-bs-toggle="tab" data-bs-target="#contenidos" 
                                type="button" role="tab" aria-controls="contenidos" style="border-radius: 50% 0% 0 0"   
                                aria-selected="false">Temarios
                        </button>
                    </li>
                </ul>


                <div class="tab-content" id="myTabContent">
                    <!-- Manda a la view: planifificacion-vertical-pai/concepto-clave.php -->
                    <div class="tab-pane fade <?= $showConceptoClaveActive ?>" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <?php
                        echo $this->render('concepto-clave',
                                [
                                    'bloqueUnidad' => $bloqueUnidad,
                                    'conceptosClaveDisponibles' => $conceptosClaveDisponibles,
                                    'conceptosClaveSeleccionados' => $conceptosClaveSeleccionados
                                ]
                        );
                        ?>
                    </div>


                    <div class="tab-pane fade <?= $showConceptosRelacionadosActive ?> " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <!-- Manda a la view: planifificacion-vertical-pai/concepto-relacionado.php -->
                        <?php
                        echo $this->render('concepto-relacionado',
                                [
                                    'bloqueUnidad' => $bloqueUnidad,
                                    'conceptosRelacionadosDisponibles' => $conceptosRelacionadosDisponibles,
                                    'conceptosRelacionadosSeleccionados' => $conceptosRelacionadosSeleccionados
                                ]
                        );
                        ?>
                    </div>


                    <div class="tab-pane fade <?= $showContextoGlobalActive ?> " id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <!-- Manda a la view: planifificacion-vertical-pai/contexto-global.php -->
                        <?php
                        echo $this->render('contexto-global',
                                [
                                    'bloqueUnidad' => $bloqueUnidad,
                                    'contextoGlobalDisponibles' => $contextoGlobalDisponibles,
                                    'contextoGlobalSeleccionados' => $contextoGlobalSeleccionados
                        ]);
                        ?>
                    </div>

                    <div class="tab-pane fade <?= $showEnunciadoActive ?> " id="otro" role="tabpanel" aria-labelledby="otro-tab">
                        <!-- Muestra enunciado de la indagación de la vista: http://192.168.20.25/educandi/backend/web/index.php?r=planificacion-bloques-unidad%2Fupdate&unidad_id=1  -->
                        <div class="row card shadow" style="margin-top: 15px; margin-bottom: 15px">
                            <div class="col-lg-12 col-md-12 col-sm-12" style="padding:50px;" >
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">

                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                        <h4 style="text-align:center" >ENUNCIADO DE LA INDAGACIÓN</h4>
                                        <?php
                                        if($estado == 'APROBADO' || $estado == 'EN_COORDINACION'){
                                            ?>
                                        <?php
                                        }else{
                                            ?>
                                        <div style="text-align: end">
                                            <?php
                                            echo Html::a(
                                                    'Actualizar',
                                                    ['planificacion-bloques-unidad/update', 'unidad_id' => $bloqueUnidad->id],
                                                    ['class' => 'btn btn-primary']
                                            );
                                            ?>
                                        </div>
                                        <?php    
                                        }
                                        ?>
                                        <hr>
                                        <?= $bloqueUnidad->enunciado_indagacion ?>
                                    </div>
                                    <div class="col-lg-2 col-md-2">

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Manda a la view: planifificacion-vertical-pai/objetivos-especificos.php -->
                    <div class="tab-pane fade <?= $showObjetivosActive ?> " id="objetivo" role="tabpanel" aria-labelledby="objetivo-tab">
                        <?php
                        echo $this->render('objetivos-especificos',
                                [
                                    'bloqueUnidad' => $bloqueUnidad,
                                    'criteriosDisponibles' => $criteriosDisponibles,
                                    'criteriosSeleccionados' => $criteriosSeleccionados
                                ]
                        );
                        ?>
                    </div>

                    <div class="tab-pane fade <?= $showHabilidadesActive ?> " id="habilidades" role="tabpanel" aria-labelledby="habilidades-tab">
                        <!-- Manda a la view: planifificacion-vertical-pai/habilidad_enfoque.php -->
                        <?php
                        echo $this->render('habilidad-enfoque',
                                [
                                    'bloqueUnidad' => $bloqueUnidad,
                                    'habilidadesDisponibles' => $habilidadesDisponibles,
                                    'habilidadesSeleccionadas' => $habilidadesSeleccionadas
                                ]
                        );
                        ?>
                    </div>


                    <div class="tab-pane fade <?= $showContenidosActive ?> " 
                         id="contenidos" role="tabpanel" aria-labelledby="contenidos-tab"
                         >

                        <div class="row card shadow" style="margin-top: 15px; margin-bottom: 15px; align-items: center">

                            <div class="col-lg-12 col-md-12" style="align-items: center">
                                <h4 style="text-align:center" >TEMARIOS</h4>


                                <hr>
                                <div class="row">
                                    <div class="col-lg-2 col-md-2">

                                    </div>
                                    <div class="col-lg-8 col-md-8">
                                        <div class="table table-responsive">
                                            <table class="table table-hover table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>TÍTULO</th>
                                                        <th style="text-align:center">CONTENIDO</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        foreach ($temario as $temario) {
                                                    ?>
                                                        <tr>
                                                            <td><?= $temario['subtitulo'] ?></td>
                                                            <td>
                                                                <?php
                                                                foreach ($temario['subtitulos'] as $subtitulos) {
                                                                    ?>
                                                            <li>
                                                                <?= $subtitulos['contenido'] ?>
                                                            </li>
                                                            <?php
                                                            }
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
                                    <div class="col-lg-2 col-md-2">

                                    </div>
                                </div>


                            </div>


                        </div>
                    </div>

                </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>


</div>
