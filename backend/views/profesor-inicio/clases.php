<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis Asignaturas';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['index']); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>

<div class="portal-inicio-index" style="padding-left: 40px; padding-right: 60px;">


    <div class="row" style="overflow-y: scroll; ">

        <?= Html::a('Crear nueva clase', ['create'], ['class' => 'btn btn-success']) ?>

        <hr>
        <?php
        foreach ($model as $clases) {

            $modelAlumnos = \backend\models\ScholarisGrupoAlumnoClase::find()
                    ->where(['clase_id' => $clases->id])
                    ->all();

            if (isset($clases->paralelo_id) && count($modelAlumnos) > 0) {
                $color = 'info';
            } else {
                $color = 'danger';
            }
            ?>
            <div class="col-md-4">
                <div class="panel panel-<?= $color ?>">
                    <div class="panel-heading">
                        <?php
                        if (isset($clases->paralelo->name)) {
                            ?>
                            <?= $clases->course->name . ' "' . $clases->paralelo->name . '"' ?>
                            <?php
                        } else {
                            ?>
                            <?= $clases->course->name ?>
                            <?php
                        }
                        ?>

                        <hr>

                        <?php
                        $modelActividades = \backend\models\ScholarisActividad::find()
                                ->where(['paralelo_id' => $clases->id])
                                ->all();

                        $modelPlanPud = backend\models\ScholarisPlanPud::find()
                                ->where(['clase_id' => $clases->id])
                                ->all();

                        $modelPlanIni = backend\models\ScholarisPlanInicial::find()
                                ->where(['clase_id' => $clases->id])
                                ->all();

//                        if (isset($modelEditaProfesor->valor) == 1) {
//                            echo $modelEditaProfesor->valor;
//                            
//                            echo Html::a('', ['update', 'id' => $clases->id], ['class' => 'glyphicon glyphicon-pencil']);
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo '&nbsp';
//                            echo Html::a('', ['borrar', 'id' => $clases->id], ['class' => 'glyphicon glyphicon-trash']);
//                        } else {


                        if (count($modelActividades) > 0 || count($modelPlanPud) > 0 || count($modelPlanIni) > 0) {
//                                echo '<div class="alert alert-$color">No puede editar ni eliminar su clase</div>';
                            echo '<br>';
                        } else {
                            echo Html::a('', ['update', 'id' => $clases->id], ['class' => 'glyphicon glyphicon-pencil']);
                            echo Html::a('', ['borrar', 'id' => $clases->id], ['class' => 'glyphicon glyphicon-trash']);
                        }
//                        }
                        ?>

                    </div>


                    <div class="panel-body">
                        <?= $clases->materia->name ?>
                        Codigo Clase: <?= $clases->id ?>
                    </div>

                    <div class="panel-footer">
                        <?php
                        if ($clases->curso->section0->code != 'PREPARATORIA') {

                            if ($realiza == 1) {

                                echo '<ul>';
                                echo '<li>' . Html::a('Planificación PUD', ['scholaris-plan-pud/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                echo '<li>' . Html::a('Actividades', ['actividades', "id" => $clases->id], ['class' => 'card-link']) . '</li>';

//  *************************                                para calificacion covid 10
//                                if ($clases->mallaMateria->tipo == 'COMPORTAMIENTO') {
//
//                                    if (count($modelEmergencia) > 0) {
//                                        echo '<li>' . Html::a('Calificación Covid 19', ['calificacionemergencia', "id" => $clases->id, 'emergencia' => 'covid19'], ['class' => 'card-link']) . '</li>';
//                                    }
//                                }
//    ************************                            fin de calificacion covid 19




                                if ($clases->curso->section0->code == 'PAI') {
                                    echo '<li>' . Html::a('Best Fit', ['scholaris-notas-pai/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                }

                                if ($clases->mallaMateria->tipo == 'COMPORTAMIENTO') {
                                    echo '<li>' . Html::a('Califica Comportamiento', ['scholaris-califica-comportamiento/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                }


                                //echo '<li>' . Html::a('Mensajes', ['scholaris-mensaje1/index', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                echo '</ul>';
                            } else {
                                echo '<ul>';
                                echo '<li>Planificación PUD</li>';
                                echo '<li>' . Html::a('Actividades', ['actividades', "id" => $clases->id], ['class' => 'card-link']) . '</li>';

                                //  *************************                                para calificacion covid 10

                                if ($clases->mallaMateria->tipo == 'COMPORTAMIENTO') {

                                    if (count($modelEmergencia) > 0) {
                                        echo '<li>' . Html::a('Calificación Covid 19', ['calificacionemergencia', "id" => $clases->id, 'emergencia' => 'covid19'], ['class' => 'card-link']) . '</li>';
                                    }

                                    echo '<li>' . Html::a('Califica Comportamiento', ['scholaris-califica-comportamiento/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                }

//    ************************                            fin de calificacion covid 19


                                if ($clases->curso->section0->code == 'PAI') {
                                    echo '<li>' . Html::a('Best Fit', ['scholaris-notas-pai/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                }






                                echo '</ul>';
                            }

                            if ($modelEmergencia == 'covidInter') {
                                echo '<ul>';
                                echo '<li>' . Html::a('Sabana Covid 19', ['reporte-sabana-covid19-profesor/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                echo '</ul>';
                            } else {
                                echo '<ul>';
                                echo '<li>' . Html::a('Sabana', ['reporte-sabana-profesor/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                                echo '</ul>';
                            }
                        } else {
                            echo '<ul>';
                            echo '<li>' . Html::a('Planificación EGI', ['scholaris-plan-inicial/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                            echo '<li>' . Html::a('Calificacion', ['scholaris-calificaciones-inicial/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
//                                echo '<li>' . Html::a('CalificacionV2', ['scholaris-calificaciones-inicial/index2', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                            // echo '<li>' . Html::a('Mensajes', ['scholaris-plan-pud/index1', "id" => $clases->id], ['class' => 'card-link']) . '</li>';
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        <?php
        }
        ?>
    </div>
</div>
