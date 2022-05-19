<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Novedades de comportamiento ' . ' (' . $modelGrupo->alumno->last_name . ' ' . $modelGrupo->alumno->first_name . ')';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Listado', ['index', 'id' => $modelAsistencia->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="comportamiento-detalle">

    <div class="container">
        <h5><?=
            $modelAsistencia->clase->materia->name . ' / ' .
            $modelAsistencia->clase->course->name . ' "' .
            $modelAsistencia->clase->paralelo->name . '" / ' .
            $modelAsistencia->clase->profesor->last_name . " " . $modelAsistencia->clase->profesor->x_first_name . ' / ' .
            $modelAsistencia->fecha . " / " .
            $modelAsistencia->hora->sigla . " HORA"
            ?>
        </h5>
        <hr>



        <div class="row">
            <div class="col">
                <div class="container">
                    <div class="table table-responsive">
                        <table class="table table-condensed table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Detalle</th>
                                    <th>Observación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($modelNovedades as $novedad) {

                                    echo '<tr>';
                                    echo '<td>' . $novedad->comportamientoDetalle->codigo . '</td>';
                                    echo '<td>' . $novedad->comportamientoDetalle->nombre . '</td>';
                                    echo '<td>' . $novedad->observacion . '</td>';

                                    echo '<td>';
                                    echo Html::a('Quitar', ['quitar', "novedadId" => $novedad->id], ['class' => 'card-link']);
                                    echo '</td>';

                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">

            <?php
            foreach ($modelComportamientos as $comp) {
                ?>

                <div class="col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading"><?= $comp->nombre ?>:</div>
                        <div class="panel-body">

                            <?php
                            foreach ($modelCompDetalle as $det) {

//                                                foreach ($modelNovedades as $nov){
                                if ($comp->id == $det->comportamiento_id) {
                                    echo '<p>' . $det->codigo . ' ';
                                    echo Html::a($det->nombre, [
                                        'asignar',
                                        "asistenciaId" => $modelAsistencia->id,
                                        "detalleId" => $det->id,
                                        "grupoId" => $modelGrupo->id,
                                            ], ['class' => 'card-link']);
                                    echo '</p>';
                                }
//                                                }
                            }
                            ?>

                        </div>

                        <div class="panel-footer">
                            <?php
                            //echo Html::a('Nuevo tema', ['nuevotema', "asistenciaId" => $modelAsistencia->id], ['class' => 'btn btn-link']);
                            ?>
                        </div>
                    </div>
                </div>

                <?php
            }
            ?>

        </div>
    </div>
</div>
