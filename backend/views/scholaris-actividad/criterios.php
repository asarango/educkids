<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asignación de criterios a la actividad';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividad', ['actividad', "actividad" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-criterios">
    <div class="container">
        <h3>
            <?= Html::encode($this->title) ?>
            <small>
                <?= $modelActividad->clase->course->name ?>
                - 
                <?= $modelActividad->clase->paralelo->name ?>
                - 
                <?= $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name ?>
            </small>
        </h3>

        <h4>
            <?= $modelActividad->insumo->nombre_nacional ?>
            <small>
                <?= $modelActividad->title ?>
            </small>
        </h4>

        <?php
        $total = count($modelCalificaciones);
        if ($total > 0) {
            echo '<div class="alert alert-danger" role="alert">
  Ya existen calificaciones realidas, usted no puede escoger nuevos criterios para esta actividad
        </div>';
        } else {
            echo '<div class="alert alert-success" role="alert">
  Por favor selecciones los criterios a usar
        </div>';
        }
        ?>


        <div class="row">

            <div class="col-md-6">
                <!--inicio no asignados-->
                <div class="card" >
                    <div class="card-body">
                        <h5 class="card-title">NO ASIGNADOS</h5>
                        <h6 class="card-subtitle mb-2 text-muted">

                        </h6>
                        <hr>
                        <font size='1px'>
                        <?php
                        foreach ($noAsignados as $criterio) {
                            echo '<div class="row">';
                            echo '<div class="col-md-1">' . $criterio['criterio'] . '</div>';
                            if ($total > 0) {
                                echo '<div class="col">' . $criterio['descricpcion'] . '</div>';
                            } else {
                                echo '<div class="col">' . Html::a($criterio['descricpcion'],
                                        [
                                            'asignarcriterio',
                                            "actividad" => $modelActividad->id,
                                            'criterio' => $criterio['criterio_id'],
                                            'detalle' => $criterio['detalle_id'],
                                        ],
                                        ['class' => 'card-link']) .
                                '</div>';
                            }

                            echo '</div>';
                            echo '<hr>';
                        }
                        ?>
                        </font>
                    </div>
                </div>
                <!--fin no asignados-->
            </div>

            <div class="col-md-6">
                <!--inicio asignados-->
                <div class="card" >
                    <div class="card-body">
                        <h5 class="card-title">ASIGNADOS</h5>
                        <h6 class="card-subtitle mb-2 text-muted">

                        </h6>
                        <hr>
                        <font size='1px'>
                        <?php
                        if (isset($asignados)) {
                            foreach ($asignados as $criterio) {
                                echo '<div class="row">';
                                echo '<div class="col-md-1">' . $criterio['criterio'] . '</div>';
                                if ($total > 0) {
                                    echo '<div class="col">' . $criterio['descricpcion'] . '</div>';
                                } else {
                                    echo '<div class="col">' . Html::a($criterio['descricpcion'],
                                            [
                                                'quitarcriterio',
                                                "id" => $criterio['id']
                                            ],
                                            ['class' => 'card-link']) .
                                    '</div>';
                                }

                                echo '</div>';
                                echo '<hr>';
                            }
                        }
                        ?>
                        </font>
                    </div>
                </div>
                <!--fin asignados-->
            </div>
        </div>
    </div>
</div>

<script>


</script>