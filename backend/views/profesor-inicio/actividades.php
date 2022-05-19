<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$seccion = $modelClase->course->section0->code;
$totalAlu = count($modelGrupo);

$sentencia = new \backend\models\SentenciasNotas();

$fecha = date('Y-m-d H:i:s');
$this->title = 'Bloques de actividades ';
?>

<div class="portal-inicio-actividades" style="padding-left: 15px; padding-right: 20px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow">
            <div class=" row align-items-center">
                <div class="col-lg-2"><h4><img src="ISM/main/images/submenu/bloquear.png" width="64px" style=""></h4></div>
                <div class="col-lg-10"><h4>
                        <?= Html::encode($this->title) ?>
                    </h4>

                    <p class="">
                        <?php
                        echo $modelClase->materia->name .
                        ' - ' .
                        'Clase #:' . $modelClase->id .
                        ' - ' .
                        $modelClase->course->name .
                        ' - ' .
                        $modelClase->paralelo->name .
                        ' - ' .
                        $modelClase->profesor->x_first_name .
                        ' ' .
                        $modelClase->profesor->last_name .
                        ' ' .
                        ' / TOTAL ALUMNOS:' .
                        $totalAlu;
                        ?>
                    </p>
                </div>
            </div>
            <hr>

            <p>
                |                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['scholaris-asistencia-profesor/index'], ['class' => 'link']);
                ?>                
                |                                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-print" aria-hidden="true"></i> Mis asignaturas</span>',
                        ['profesor-inicio/index'], ['class' => 'link']);
                ?>                                               
                |
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-print" aria-hidden="true"></i> Sábana</span>',
                        ['reporte-sabana-profesor/index1', 'id' => $modelClase->id], ['class' => 'link']);
                ?>                                               
                |
                
            </p>

            <!--comienza tabla de calificaciones-->
             <div class="row text-center">
            <?php
            foreach ($modelBloques as $bloque) {
                if ($fecha > $bloque->hasta) {
                    $estado = 'Cerrado';
                } else {
                    $estado = 'Abierto';
                }
                ?>

                <div class="col-lg-3" style="margin-top: 10px; margin-bottom: 10px">
                    <div class="text-color-p" style="border: solid 1px; color: #ccc; padding: 10px; border-left-color: #286090; border-left-width: 2px">
                        <?=
                        Html::a('<h4>' . strtoupper($bloque->name) . '</h4>', ['actividades-detalle',
                            'bloque_id' => $bloque->id,
                            'clase_id' => $modelClase->id
                        ], ['class' => 'link'])
                        ?>
                        <strong>
                            <p class="text-color-plomo">DESDE: <?= $bloque->desde ?> HASTA: <?= $bloque->hasta ?></p>

                            <p class="text-color-plomo">ESTADO: <?= $estado ?></p>
                        </strong>
                    </div>
                </div>

                <?php
            }
            ?>    
        </div>
            <!--finaliza tabla de calificaciones-->


        </div>

    </div>

</div>


