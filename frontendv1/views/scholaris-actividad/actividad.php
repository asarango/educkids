<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Administración de Actividades';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividades', ['/profesor-inicio/actividades', "id" => $modelActividad->paralelo_id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-index">

    <!--<div class="container">-->



    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading"><?= $modelActividad->title . ' / ' . $modelActividad->insumo->nombre_nacional . ' / ' . $estado ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3"><strong>Número:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->id ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Materia:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->clase->materia->name ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Profesor:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Calificado:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->calificado ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Tipo:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->tipo_calificacion ?></div>
                    </div>
                    <div class="row">
                        <div class="col-md-3"><strong>Creado:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->create_date ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Presentar el:</strong></div>
                        <div class="col-md-9"><?= $modelActividad->inicio ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Descripción:</strong></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12"><?= $modelActividad->descripcion ?></div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"><strong>Tareas:</strong></div>
                    </div>
                    <div class="row-md-12">
                        <div class="col"><?= $modelActividad->tareas ?></div>
                    </div>
                </div>

                <div class="panel-footer">
                    <?php
                    if ($estado == 'abierto') {
                        echo Html::a('Editar', ['update', "id" => $modelActividad->id], ['class' => 'btn btn-primary']);
                        echo '&nbsp';
                        echo Html::a('Eliminar', ['eliminar', "id" => $modelActividad->id], ['class' => 'btn btn-danger']);
                        echo '&nbsp';
                        if ($modelActividad->actividad_original == 0) {
                            echo Html::a('Duplicar', ['duplicar', "id" => $modelActividad->id], ['class' => 'btn btn-warning']);
                        } else {
                            echo '<p class="text-warning">No se puede duplicar.</p>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>


        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">CALIFICACIONES</div>
                <div class="panel-body">
                    <?= 'Total calificados: ' . count($modelCalificaciones) ?>

                    <?php
                    if ($modelActividad->tipo_calificacion == 'P') {
                        echo '<p><h5>Criterios usados</h5></p>';

                        foreach ($modelCriterios as $criterio) {
                            ?>
                            <div class="row">
                                <div class="col-md-1">
                                    <?= $criterio->criterio->criterio ?>
                                </div>

                                <div class="col">
                                    <?= $criterio->detalle->descricpcion ?>
                                </div>
                            </div>
                            <?php
                        }
                       
                    } else {
//                        
                    }
                     echo '<hr>';
                    if ($modelActividad->tipo_calificacion == 'P') {
                        if (count($modelCriterios) > 0 && $modelActividad->calificado == 'SI') {
                            echo Html::a('Calificar', ['calificar', "id" => $modelActividad->id], ['class' => 'btn btn-primary']);
                            
                            $cantidadCalif = backend\models\ScholarisCalificaciones::find()->where(['idactividad' => $modelActividad->id])->all();
                            
                            if(count($cantidadCalif)>0){
                                
                            }else{
                                echo Html::a('Criterios', ['criterios', "id" => $modelActividad->id], ['class' => 'btn btn-warning']);
                                echo '<p class="text-danger">Si da clic en el boton calificar ya no puede modificar criterios</p>';
                            }
                        }else{
                            echo Html::a('Criterios', ['criterios', "id" => $modelActividad->id], ['class' => 'btn btn-warning']);
                            
                        }
                    } else {
                        echo Html::a('Calificar', ['calificar', "id" => $modelActividad->id], ['class' => 'btn btn-primary']);
                    }
                    
                    ?>


                </div>

                <div class="panel-footer">
                    <?php
                    if ($estado == 'abierto') {
//                        echo Html::a('Editar', ['update', "id" => $modelActividad->id], ['class' => 'btn btn-primary']);
//                        echo '&nbsp';
//                        echo Html::a('Eliminar', ['eliminar', "id" => $modelActividad->id], ['class' => 'btn btn-danger']);
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">MATERIAL DE APOYO</div>
                <div class="panel-body">

                    <ul class="list-group list-group-flush">

                        <?php
                        foreach ($modelArchivos as $archivo) {
                            echo '<li class="list-group-item">';
                            echo '<font size="2px">' . $archivo->nombre_archivo . '</font> ';
                            echo '<span class="badge badge-warning">' . Html::a('Descargar', ['descargar', "ruta" => $archivo->archivo], ['class' => 'card-link']) . '</span>';
                            echo '</li>';
                        }
                        ?>

                    </ul> 


                </div>

                <div class="panel-footer">
                    <?php echo Html::a('Nuevo archivo', ['scholaris-archivosprofesor/create', "id" => $modelActividad->id], ['class' => 'btn btn-primary']); ?>
                </div>
            </div>
        </div>

    </div>
    <!--</div>-->
</div>
