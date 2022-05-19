<?php

use yii\helpers\Html;
use yii\grid\GridView;

use backend\models\ScholarisMallaMateria;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMallaAreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalle de la Malla: ' . $modelMalla->nombre_malla;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-area-index">

    <div class="container">
        <p>
            <?= Html::a('Crear Area', ['create','id' => $modelMalla->id], ['class' => 'btn btn-success']) ?>
        </p>
        
        <?php
        foreach ($modelArea as $area){
        ?>    
            <div class="panel-group">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        
                        <div class="row">
                            <div class="col-md-3">
                                <a data-toggle="collapse" href="#area<?= $area->id ?>"><?= $area->area->name ?></a>
                            </div>    
                            
                            <div class="col-md-3">
                                <?= Html::a('Editar', ['update','id' => $area->id], ['class' => 'btn-link']) ?>
                            </div>
                        </div>
                        <br>
                        
                        <div class="row tamano10">
                            <div class="col-md-2"><strong>Orden:</strong><?= $area->orden ?></div>
                            <div class="col-md-2"><strong>Tipo:</strong><?= $area->tipo ?></div>
                            <div class="col-md-2"><strong>Imprime:</strong><?= $area->se_imprime ?></div>
                            <div class="col-md-2"><strong>Promedia:</strong><?= $area->promedia ?></div>
                            <div class="col-md-2"><strong>Peso total:</strong><?= $area->total_porcentaje ?></div>
                            <div class="col-md-2"><strong>Cuantitativa:</strong><?= $area->es_cuantitativa ?></div>
                        </div>
                        
                        
                    </h4>
                </div>
                <div id="area<?= $area->id ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <?= Html::a('Nueva Asignatura', ['scholaris-malla-materia/create','id' => $area->id], ['class' => 'btn btn-primary']) ?>
                        <hr>
                        
                        <div class="table table-responsive tamano10">
                            <table class="table table-condensed table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Orden</th>
                                        <th>Asignatura</th>
                                        <th>Tipo</th>
                                        <th>Total Peso</th>
                                        <th>Imprime</th>
                                        <th>Promedia</th>
                                        <th>Cuantitativa</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php
                                    
                                    $modelAsignaturas = ScholarisMallaMateria::find()
                                            ->where(['malla_area_id' => $area->id])
                                            ->all();
                                    
                                    foreach ($modelAsignaturas as $materia){
                                        echo '<tr>';
                                        echo '<td>'.$materia->orden.'</td>';
                                        echo '<td>'.$materia->materia->name.'</td>';
                                        echo '<td>'.$materia->tipo.'</td>';
                                        echo '<td>'.$materia->total_porcentaje.'</td>';
                                        echo '<td>'.$materia->se_imprime.'</td>';
                                        echo '<td>'.$materia->promedia.'</td>';
                                        echo '<td>'.$materia->es_cuantitativa.'</td>';
                                        echo '<td>';
                                        echo Html::a('Editar', ['scholaris-malla-materia/update','id' => $materia->id], ['class' => 'btn-link']);
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                        
                                    
                                    ?>
                                </tbody>
                                
                            </table>
                        </div>
                        
                    </div>
                    <div class="panel-footer">Panel Footer</div>
                </div>
            </div>
        </div>
          
        <?php
        }
        ?>
        
        
        
    </div>
</div>
