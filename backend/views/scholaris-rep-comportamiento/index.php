<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\OpStudent;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRepLibretaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CUADRO DE COMPORTAMIENTO - PARCIALES - '.$modelParalelo->course->name.' '.$modelParalelo->name.' - '.$modelBloque->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-notas-curso-index">

    <h4><?= Html::encode($this->title) ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    
    <div class="container">
        
        <p>
        <?= Html::a('Exportar Todos a PDF', ['pdf', 'paralelo' => $modelParalelo->id, 'bloque' => $modelBloque->id], ['class' => 'btn btn-danger']) ?>        
        </p>
        
        <div class="table table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Ord.</th>
                        <th>Estudiante</th>
                        
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    $i=0;
                    foreach ($modelEstudiantes as $alumno){
                        $i++;
                        echo '<tr>';
                        echo '<td>'.$i.'</td>';
                        echo '<td>'.$alumno->last_name.' '.$alumno->first_name.' '.$alumno->middle_name.'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                
            </table>
        </div>
    </div>

    
</div>
