<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisLeccionarioDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modelHoras = new backend\models\ScholarisLeccionarioDetalleSearch();

$this->title = 'Leccionario Detalles: ' . $modelLeccionario->paralelo->course->name . ' - ' . $modelLeccionario->paralelo->name . ' / ' . $modelLeccionario->fecha;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-leccionario-detalle-index">

    <p>

    </p>

    <hr>

    <div class="container">


        <div class="panel panel-primary">
            <div class="panel-heading">Novedades Profesor</div>
            <div class="panel-body">
                <div class="table table-responsive">
                    <table class="table table-condensed table-hover tamano10">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Materia</th>
                                <th>Profesor</th>
                                <th>Desde</th>
                                <th>Ingresa</th>
                                <th>Diferencia</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($modelDetalle as $detalle) {

                                if ($detalle->estado == 'pendiente') {
                                    $color = '#FD7F21';
                                } else {
                                    $color = '#E8F7CB';
                                }


                                echo '<tr>';
                                echo '<td>' . $detalle->hora->sigla . '</td>';
                                echo '<td>' . $detalle->clase->materia->name . '</td>';
                                echo '<td>' . $detalle->clase->profesor->last_name . ' ' . $detalle->clase->profesor->x_first_name . '</td>';
                                echo '<td>' . $detalle->hora->desde . '</td>';

                                if ($detalle->asistencia_id) {
                                    $modelAsistencia = \backend\models\ScholarisAsistenciaProfesor::find()
                                            ->where(['id' => $detalle->asistencia_id])
                                            ->one();

                                    $horaIngresa = $modelAsistencia->hora_ingresa;
                                    $tiempoAtrasado = $modelHoras->restar_horas($detalle->fecha . $horaIngresa, $detalle->fecha . $detalle->desde);
                                    $modelJustificacion = \backend\models\ScholarisAsistenciaJustificacionProfesor::find()
                                            ->where(['asistencia_id' => $detalle->asistencia_id])
                                            ->all();
                                    $modelNovedades = backend\models\ScholarisAsistenciaAlumnosNovedades::find()
                                            ->where(['asistencia_profesor_id' => $detalle->asistencia_id])
                                            ->all();
                                    $totalNov = count($modelNovedades);
                                } else {
                                    $horaIngresa = 'NA';
                                    $tiempoAtrasado = 'NA';
                                    $modelJustificacion = 'NA';
                                    $totalNov = 0;
                                }

                                echo '<td>' . $horaIngresa . '</td>';
                                echo '<td>' . $tiempoAtrasado . '</td>';
                                echo '<td bgcolor="' . $color . '">' . $detalle->estado . '</td>';
                                echo '<td>';
                                echo Html::a("Editar", ["editar", "asistencia" => $detalle->asistencia_id, "detalle" => $detalle->id], ["class" => "btn btn-link"]);
                                //echo 
                                echo '</td>';

                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="panel panel-info">
            <div class="panel-heading">Novedades Estudiantes</div>
            <div class="panel-body">
                <div class="table table-responsive">
                    <table class="table table-condensed table-hover tamano10">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estudiantes</th>
                                <th>Total Reportado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            foreach ($modelNoveades as $novedad) {
                                $i++;
                                echo '<tr>';
                                echo '<td>'.$i.'</td>';
                                echo '<td>'.$novedad['last_name'].' '.$novedad['first_name'].' '.$novedad['middle_name'].'</td>';
                                echo '<td>'.$novedad['total'].'</td>';
                                echo Html::a("Editar", ["editar", "asistencia" => $detalle->asistencia_id, "detalle" => $detalle->id], ["class" => "btn btn-link"]);
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
</div>
