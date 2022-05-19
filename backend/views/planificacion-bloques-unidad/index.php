<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Planificación de temas para desagregación de destrezas';
$this->params['breadcrumbs'][] = $this->title;

//echo($seccion);
//echo($perfil);
?>
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>

                    <h5>
                        
                        <?= 'CURSO: ' . $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?>
                    </h5>

                    <small>
                        (
                        <?=
                        
                        $cabecera->ismAreaMateria->materia->nombre . ' -  MEC'
                        ?>
                        )
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Selección de Niveles</span>',
                            ['planificacion-desagregacion-cabecera/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                    |
                    <?php
                    if ($seccion == 'PAI') {
                        echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: #f9b900 "><i class="far fa-calendar" aria-hidden="true"></i> 5.-PCA</span>',
                                ['pca/index1', 'cabecera_id' => $cabecera->id],
                                ['class' => 'link']
                        );
                    }

                    if ($seccion == 'BAS') {
                        echo Html::a(
                                '<span class="badge rounded-pill" style="background-color: #f9b900 "><i class="far fa-calendar" aria-hidden="true"></i> 4.-PCA</span>',
                                ['pca/index1', 'cabecera_id' => $cabecera->id],
                                ['class' => 'link']
                        );
                    }

                    if($seccion == 'DIPL'){
                        echo Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18 "><i class="far fa-file-pdf" aria-hidden="true"></i> PDF Plan Vertical</span>',
                            ['pdf-pv', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link', 'target' => '_blank']
                        );
                        echo ' | ';                        
                    }
                    ?>

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 25px;">
                <div class="table table-responsive">
                    <table class="table table-condensed table-hover table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">BLOQUE</th>
                                <th class="text-center">TÍTULO</th>
                                <th class="text-center">PUD</th>
                                <th class="text-center">ESTADO CONFIGURACIÓN</th>
                                <th class="text-center">ES ABIERTO</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $contador = 0;
                            foreach ($unidades as $unidad) {
                                ?>
                                <tr>
                                    <td class="text-center"><?= $unidad->curriculoBloque->last_name ?></td>
                                    <td class="text-center"><?= $unidad->unit_title ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($seccion == 'BAS') {
                                            if ($unidad->pud_status == 1) {
                                                echo Html::a(
                                                        '<i class="fas fa-check" title="PUD CREADO"></i>',
                                                        ['pud-pep/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #0a1f8f']
                                                );
                                            } else {
                                                echo Html::a(
                                                        '<i class="fas fa-times" title="PLANIFICAR PUD"></i>',
                                                        ['pud-pep/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #ab0a3d']
                                                );
                                            }
                                        } elseif ($seccion == 'PAI') {
                                            if ($unidad->pud_status == 1) {
                                                echo Html::a(
                                                        '<i class="fas fa-check" title="PUD CREADO"></i>',
                                                        ['pud-pai/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #0a1f8f']
                                                );
                                            } else {
                                                echo Html::a(
                                                        '<i class="fas fa-times" title="PLANIFICAR PUD"></i>',
                                                        ['pud-pai/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #ab0a3d']
                                                );
                                            }
                                        } elseif ($seccion == 'DIPL') {
                                            if ($unidad->pud_status == 1) {
                                                echo Html::a(
                                                        '<i class="fas fa-check" title="PUD CREADO"></i>',
                                                        ['pud-dip/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #0a1f8f']
                                                );
                                            } else {
                                                echo Html::a(
                                                        '<i class="fas fa-times" title="PLANIFICAR PUD"></i>',
                                                        ['pud-dip/index1', 'plan_bloque_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #ab0a3d']
                                                );
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $unidad->settings_status ?></td>
                                    <td class="text-center">
                                        <!--Si usuario es administrador puede mover "Es Abierto"-->
                                        <?php
                                        if ($perfil == 'Administrador') {
                                            if ($unidad->is_open == 1) {
                                                ?>
                                                <i class="fas fa-lock-open" style="color: green"></i>
            <!--                                                    <? Html::a(
                                                                    '<i class="fas fa-lock-open"></i>',
                                                                    ['abrir-bloque','plan_unidad_id' => $unidad->id],
                                                                    ['style' => 'color: green']
                                                            ) ?>-->
                                                <?php
                                            } else {
                                                ?>
                                                <?=
                                                Html::a(
                                                        '<i class="fas fa-lock"></i>',
                                                        ['abrir-bloque', 'plan_unidad_id' => $unidad->id],
                                                        ['style' => 'color: #ab0a3d']
                                                )
                                                ?>
                                                <?php
                                            }
                                        } else {
                                            if ($unidad->is_open == 1) {
                                                ?>
                                                <i class="fas fa-lock-open" style="color: green"></i>
                                                <?php
                                            } else {
                                                ?>
                                                <i class="fas fa-lock" style="color: #ab0a3d"></i>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">

                                        <!-- Boton DropDownList -->
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown" 
                                                    aria-expanded="false" style="font-size: 10px; border-radius: 0px">
                                                Acciones
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <?=
                                                    Html::a('<i class="fas fa-pencil-alt my-text-medium" style="color: #0a1f8f"> 1.-TITULO BLOQ.</i>', ['update',
                                                        'unidad_id' => $unidad->id
                                                    ]);
                                                    ?>
                                                </li>
                                                <li>
                                                    <?=
                                                    Html::a('<i class="fas fa-list-ol my-text-medium" style="color: #65b2e8"> 2.-TEMARIO</i>', ['contenido',
                                                        'unidad_id' => $unidad->id
                                                    ]);
                                                    ?>
                                                </li>
                                                <li>
                                                    <?php
                                                    $total = calcula_total_criterios($unidad->id);
                                                    echo Html::a('<i class="fas fa-cogs my-text-medium" style="color: #ff9e18"> 3.-MEC (' . count($total) . ')</i>',
                                                            ['planificacion-desagregacion-cabecera/desagregacion',
                                                                'unidad_id' => $unidad->id
                                                    ]);
                                                    ?>
                                                </li>
                                                <li>
                                                    <!-- Aqui muestro botones dependiendo de cada sección -->
                                                    <?php
                                                    if ($seccion == 'PAI') {
                                                        echo Html::a('<i class="far fa-copy my-text-medium" style="color: #ab0a3d"> 4.-PH-PV</i>',
                                                                ['planificacion-vertical-pai-descriptores/index1',
                                                                    'unidad_id' => $unidad->id
                                                        ]);
                                                    }elseif($seccion == 'DIPL'){
                                                        echo Html::a('<i class="far fa-copy my-text-medium" style="color: #ab0a3d"> 4.-PH-PV</i>',
                                                                ['planificacion-vertical-diploma/index1',
                                                                 'unidad_id' => $unidad->id
                                                        ]);
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                                <?php
                                // Aqui aumento contador si los estados de bloques están "configurado"
                                if ($unidad['settings_status'] == 'configurado') {
                                    $contador = $contador + 1;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!--<div class="col-lg-6 col-md-6" style="text-align:center" >-->
<!--<?
Html::a(
        '<i class="fas fa-file-pdf"> Generar PDF - Vertical - Horizontal</i>',
        ['pca-materia', 'cabecera_id' => $cabecera->id],
        ['class' => 'link', 'style' => 'font-size:15px']
);
?>-->
                <!--</div>-->
                <div class="col-lg-12 col-md-12" style="text-align:end; margin-bottom: 5px">
                    <?php
//                    echo $contador ;
//                    echo '<br>';
//                    echo count($unidades);
                    if ($contador == count($unidades) && $cabecera->estado == 'INICIANDO' && $cabecera->estado != 'EN_COORDINACION' && $cabecera->estado != 'DEVUELTO' && $cabecera->estado != 'APROBADO'
                    ) {
                        echo Html::a(
                                '<i class="fas fa-hand-point-right">Enviar Planificaciones - Coordinador</i>',
                                ['envia-coordinador', 'cabecera_id' => $cabecera->id],
                                ['class' => 'btn btn-primary my-text-medium']
                        );
                    } else {
                        if ($contador < count($unidades)) {
                            ?>
                            <div class="alert alert-dark" role="alert" style="text-align: center" >
                                <strong>Usted debe planificar todos los BLOQUES para enviar al Coordinador</strong>
                                <br>
                                <strong>ESTADO CONFIGURACIÓN - 'configurado'</strong>
                            </div>
                            <?php
                        }
                    }

                    if ($cabecera->estado == 'EN_COORDINACION') {
                        ?>
                        <div class="alert alert-primary" role="alert" style="text-align: center" >
                            <strong>Se ha enviado Planificación al Coordinador</strong>
                        </div>
                        <?php
                    }
                    ?>

                    <?php
                    if ($cabecera->estado == 'DEVUELTO' && $contador == count($unidades)) {
                        echo Html::a(
                                '<i class="fas fa-hand-point-right">Enviar Planificaciones - Coordinador</i>',
                                ['envia-coordinador', 'cabecera_id' => $cabecera->id],
                                ['class' => 'btn btn-primary my-text-medium']
                        );
                        ?>
                        <div class="alert alert-danger" role="alert" style="text-align: start" >
                            <b><h5>SE HA SOLICITADO REALIZAR LOS SIGUIENTES CAMBIOS:</h5></b>
                            <hr>
                            <?= $cabecera->revision_coordinacion_observaciones ?>
                        </div>
                        <?php
                    } else {
                        if ($cabecera->estado == 'DEVUELTO') {
                            ?>
                            <div class="alert alert-danger" role="alert" style="text-align: start" >
                                <?= $cabecera->revision_coordinacion_observaciones ?>
                            </div>
                            <?php
                        }
                    }

                    if ($cabecera->estado == 'APROBADO') {
                        ?>
                        <div class="alert alert-success" role="alert" style="text-align: center" >
                            <strong>
                                <u>
                                    <i class="far fa-thumbs-up" style="font-size: 20px"></i>
                                    Sus planificaciones han sido aprobadas por el coordinador.
                                </u>
                            </strong>
                        </div>
                        <?php
                    }
                    ?>
                </div>

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

<!-- funciones php -->
<?php

function calcula_total_criterios($unidadId) {
    $model = PlanificacionDesagregacionCriteriosEvaluacion::find()
            ->where(['bloque_unidad_id' => $unidadId])
            ->all();
    return $model;
}
?>
