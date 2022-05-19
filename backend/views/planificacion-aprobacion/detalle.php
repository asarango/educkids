<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificaciones - ' . $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->opCourses[0]->name 
        . ' - ' . $cabecera->ismAreaMateria->materia->nombre;
$this->params['breadcrumbs'][] = $this->title;

//  echo '<pre>';
//  print_r($cabecera);
//  print_r($desagregacion);
//  die();
//    print_r($asignaturas[0]->opCourseTemplate->opCourses[0]->name);
?>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>



<div class="planificacion-aprobacion-asignaturas">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Asignaturas</span>',
                            ['asignaturas', 'template_id' => '7'],
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
            <div class="row" style="margin-top: 10px;padding-left: 25px; margin-bottom: 10px">

                <div class="col-lg-7 col-md-7">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">PCA</button>
                            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">MEC</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">PH-PV</button>
                        </div>
                    </nav>

                    <!--Muestra PCA-->
                    <div class="tab-content" id="nav-tabContent" style="margin-top: 5px">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <?php
                            if ($cabecera->estado == 'INICIANDO' || $cabecera->estado == 'DEVUELTO') {
                                echo '<div class="alert alert-primary" role="alert">';
                                echo '<h6>EN ESPERA QUE EL DOCENTE ENVIE PLANIFICACIÓN</h6>';
                                echo '</div>';
                            } else {
                                echo$pca->html;
                            }
                            ?>
                        </div>


                        <!--Muestra MEC-->
                        <div class="tab-pane fade scroll-400" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="accordion accordion-flush my-text-medium" id="accordionFlushExample">
                                <?php
                                if ($cabecera->estado == 'INICIANDO' || $cabecera->estado == 'DEVUELTO') {
                                    echo '<div class="alert alert-primary" role="alert">';
                                    echo '<h6>EN ESPERA QUE EL DOCENTE ENVIE PLANIFICACIÓN</h6>';
                                    echo '</div>';
                                } else {
                                    foreach ($desagregacion as $bloque) {
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $bloque['id'] ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                                                    <?= $bloque['last_name'] . ' - ' . $bloque['unit_title'] ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapse<?= $bloque['id'] ?>" class="accordion-collapse collapse"
                                                 style="color: black"
                                                 aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">

                                                    <?php
                                                    foreach ($bloque['criterios'] as $criterios) {
                                                        ?>
                                                        <div class="card shadow" style="padding:20px; margin-bottom: 5px">
                                                            <h6 style="background-color: #65b2e8">CRITERIOS EVALUACIÓN</h6>
                                                            <?= '<strong>' . $criterios['code'] . '</strong>' . $criterios['description'] ?>
                                                            <h6 style="background-color: #cccc">DESTREZAS</h6>
                                                            <ul>
                                                                <?php
                                                                foreach ($criterios['destrezas'] as $destrezas) {
                                                                    ?>
                                                                    <li>
                                                                        <?php
                                                                        if ($destrezas->opcion_desagregacion <> 'ORIGINAL') {
                                                                            ?>
                                                                            <?= $destrezas->content ?>&nbsp; <strong>Ref.(<?= $destrezas->curriculoDestreza->code ?>)</strong>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                            <strong><?= $destrezas->curriculoDestreza->code ?></strong><?= $destrezas->content ?>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                    </li>
                                                                    <br>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </ul>
                                                            <hr>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>



                        <!--Muestra PH-PV-->
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="accordion accordion-flush my-text-medium scroll-400 " id="accordionFlushExample">
                                <?php
                                if ($cabecera->estado == 'INICIANDO' || $cabecera->estado == 'DEVUELTO') {
                                    echo '<div class="alert alert-primary" role="alert">';
                                    echo '<h6>EN ESPERA QUE EL DOCENTE ENVIE PLANIFICACIÓN</h6>';
                                    echo '</div>';
                                } else {
                                    foreach ($desagregacion as $pv) {
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-headingOne">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#flush-collapsePv<?= $pv['id'] ?>" aria-expanded="false" aria-controls="flush-collapseOne">
                                                            <?= $pv['last_name'] . ' - ' . $pv['unit_title'] ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapsePv<?= $pv['id'] ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                                <div class="accordion-body">

                                                    <!--Row donde muestran CONCEPTOS CLAVE - CONCEPTOS RELACIONADOS - CONTEXTO GLOBAL - ENUNCIADOS INDAGACION  -->
                                                    <div class="row ">
                                                        <div class="col-lg-3 col-md-3 border my-text-small">
                                                            <u>
                                                                <strong style="color: black">CONCEPTOS CLAVE</strong>
                                                            </u>
                                                            <?php
                                                            foreach ($pv['ph_pv'] as $pai) {
                                                                if ($pai['tipo'] == 'concepto_clave') {
                                                                    foreach ($pai['contenidos'] as $conClave) {
                                                                        ?>
                                                                        <li><?= $conClave->contenido ?></li>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 border my-text-small">
                                                            <u>
                                                                <strong style="color: black">CONCEPTOS RELACIONADOS</strong>
                                                            </u>
                                                            <?php
                                                            foreach ($pv['ph_pv'] as $pai) {
                                                                if ($pai['tipo'] == 'concepto_relacionado') {
                                                                    foreach ($pai['contenidos'] as $conRelac) {
                                                                        ?>
                                                                        <li><?= $conRelac->contenido ?></li>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 border my-text-small">
                                                            <u>
                                                                <strong style="color: black">CONTEXTO GLOBAL</strong>
                                                            </u>
                                                            <?php
                                                            foreach ($pv['ph_pv'] as $pai) {
                                                                if ($pai['tipo'] == 'contexto_global') {
                                                                    foreach ($pai['contenidos'] as $conGlob) {
                                                                        ?>
                                                                        <li><?= $conGlob->contenido ?></li>
                                                                        <?php
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 border my-text-small">
                                                            <u>
                                                                <strong style="color: black">ENUNCIADOS INDAGACIÓN</strong>
                                                            </u>
                                                            <br>
                                                            <p>
                                                                <?= $pv['enunciado_indagacion'] ?>
                                                            </p>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!--  Row donde muestran criterios PAI-->
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 border my-text-small">
                                                        <u>
                                                            <strong style="color: black">HABILIDADES DE ENFOQ. APRENDIZAJES</strong>
                                                        </u>
                                                        <br>
                                                        <div class="table table-responsive">
                                                            <table class="table table-striped my-text-small">
                                                                <thead>
                                                                    <tr>
                                                                        <th>DESCRIPCIÓN</th>
                                                                        <!--<th>CRITERIO</th>-->
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($pv['ph_pv'] as $pai) {
                                                                        if ($pai['tipo'] == 'habilidad_enfoque') {
                                                                            foreach ($pai['contenidos'] as $habil) {
                                                                                ?>
                                                                                <tr>
                                                                                    <td><?= $habil->contenido ?> </td>

                                                                                </tr>
                                                                                <?php
                                                                            }
                                                                        }
                                                                    }
                                                                    ?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 border my-text-small">
                                                        <u>
                                                            <strong style="color: black">OBJETIVOS ESPECÍFICOS</strong>
                                                        </u>
                                                        <br>
                                                        <div class="table table-responsive">
                                                            <table class="table table-striped my-text-small">
                                                                <thead>
                                                                    <tr>
                                                                        <th>DESCRIPCIÓN</th>
                                                                        <th>TIPO</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    foreach ($pv['criterios_pai'] as $obje) {
                                                                        ?>
                                                                        <tr>
                                                                            <td><?= $obje['descricpcion'] ?></td>
                                                                            <td><?= $obje['criterio'] ?></td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-5 col-md-5" style="padding-top: 40px">

                    <?php
                    $form = ActiveForm::begin([
                                'action' => Url::to(['detalle', 'cabecera_id' => $cabecera->id]),
                                'method' => 'post'
                    ]);
                    ?>

                    <!--CKEDITOR-->
                    <!--EDITOR DE TEXTO KARTIK-->
                    <textarea name="revision_coordinacion_observaciones" id="editor">
                        <?= $cabecera->revision_coordinacion_observaciones ?>
                    </textarea>
                    <script>
                        CKEDITOR.replace('editor', {
                            customConfig: '/ckeditor_settings/config.js'
                        })
                    </script>


                    <?php
                    if ($cabecera->estado == 'APROBADO') {
                        ?>
                        <div class="alert alert-success" role="alert" style="text-align:center" >
                            ¡Usted aprobó Planificaciones <i class="fas fa-thumbs-up"></i>! 
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'EN_COORDINACION') {
                        ?>
                        <br>
                        <div class="row" style="text-align: center; padding-left: 30px;padding-right: 30px;">
                            <?=
                            Html::submitButton('Devolver Planificación',
                                    [
                                        'class' => 'btn btn-danger my-text-medium'
                                    ])
                            ?>
                            <hr>
                            <i class="far fa-hand-point-down" style="font-size: 20px;color: #0a1f8f"></i> 
                            <?=
                            Html::a(
                                    '<i class="fas fa-check-circle"> Aprobar Planificación</i>',
                                    ['aprobacion', 'cabecera_id' => $cabecera->id],
                                    ['class' => 'btn btn-success my-text-medium']
                            );
                            ?> 
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'DEVUELTO') {
                        ?>
                        <div class="alert alert-warning" role="alert" style="text-align:center" >
                            ¡Se ha enviado a modificar al profesor!
                        </div>
                        <?php
                    } elseif ($cabecera->estado == 'INICIANDO') {
                        ?>
                        <div class="alert alert-info" role="alert" style="text-align:center" >
                            ¡El profesor está iniciando su planificación!
                        </div>
                        <?php
                    }
                    ?>

                </div>

                <?php ActiveForm::end(); ?>

            </div>


        </div>
        <!-- fin cuerpo de card -->

    </div>
</div>

</div>

