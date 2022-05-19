<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\ScholarisActividad;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crear nueva actividad';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividades', ['profesor-inicio/actividades', "id" => $modelClase->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-create">

    <h3>
        <?= Html::encode($this->title) ?>
        <small>
            <?= $modelClase->course->name ?>
            - 
            <?= $modelClase->paralelo->name ?>
            - 
            <?= $modelClase->materia->name ?>
            - 
            <?= $modelClase->profesor->last_name ?> 
            <?= $modelClase->profesor->x_first_name ?>
        </small>
    </h3>

    <?php
    if ($estado == 'abierto') {
        ?>


        <p>Escoger día por favor</p>


        <?php
        foreach ($modelHorarios as $horario) {
            if (isset($horario['semana'])) {
                $semana = $horario['semana'];
            } else {
                $semana = 'NA';
            }
            ?>
            <div class="col-md-2">
                <div class="panel panel-warning">
                    <div class="panel-heading"><?= $horario['dia'] . ' <small>' . $horario['fecha'] . '    </small>' ?></div>
                    
                    <div class="panel-body">
                        <span class="badge"><?= $semana ?></span>
                        <?php
                        $modelActividades = ScholarisActividad::find()
                                ->innerJoin('scholaris_clase', 'scholaris_clase.id = scholaris_actividad.paralelo_id')
                                ->where([
                                    'scholaris_clase.paralelo_id' => $modelClase->paralelo_id,
                                    'scholaris_actividad.inicio' => $horario['fecha'],
                                    'scholaris_actividad.calificado' => 'SI'
                                ])
                                ->all();

                        echo '<p class="text-warning">' . count($modelActividades) . ' Actividades</p>';
                        ?>
                    </div>

                    <div class="panel-footer">
                        <?php
                        if ($modelClase->course->section0->code == 'PAI') {
                            echo Html::a('PAI  ', [
                                'crear1',
                                "clase" => $modelClase->id,
                                'fecha' => $horario['fecha'],
                                'bloqueId' => $bloqueId,
                                'tipo' => 'P',
                                'semana' => $semana,
                                    ], ['class' => 'card-link']);
                        }
                        
                        
                        echo Html::a('NACIONAL', [
                            'crear1',
                            "clase" => $modelClase->id,
                            'fecha' => $horario['fecha'],
                            'bloqueId' => $bloqueId,
                            'tipo' => 'N',
                            'semana' => $semana,
                                ], ['class' => 'card-link']);
                        ?>
                    </div>
                </div>

            </div>
            <?php
        }
        ?>

                <?php
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
            ¡Los sentimos. El bloque seleccionado se encuentra cerrado, por favor comuníquese con el Administrador!
        </div>
        <?php
    }
    ?>
</div>

<script>


</script>