<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'LISTADO DEL: '.$modelParalelo->course->name.' '.$modelParalelo->name;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listados-index">
    
    <div class="container">
    <p>
    <?= Html::a('Exportar PDF', ['pdf','paralelo' => $modelParalelo->id], ['class' => 'btn btn-danger']) ?>
    <?= Html::a('Exportar Excel', ['excel','paralelo' => $modelParalelo->id], ['class' => 'btn btn-success']) ?>
    </p>
    
    <hr>
    
    <div class="table table-responsive">
        <table class="table table-striped table-condensed table-hover tamano10P">
            <thead>
                <tr>
                    <th>#</th>
                    <th>ESTUDIANTE</th>
                    <th>ESTADO</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i=0;
                foreach ($modelAlumnos as $alumno){
                    $i++;
                    echo '<tr>';
                    echo '<td>'.$i.'</td>';
                    echo '<td>'.$alumno->last_name.' '.$alumno->first_name.' '.$alumno->middle_name.'</td>';
                    if($alumno->insc_estado == 'M'){
                        echo '<td>MATRICULADO</td>';
                    } else {
                        echo '<td>RETIRADO</td>';
                    }
                    
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    
    
    </div>
    
</div>


