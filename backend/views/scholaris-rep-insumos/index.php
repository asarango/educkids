<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\OpStudent;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRepLibretaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'CUADRO DE NOTAS POR CURSO Y MATERIA';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-insumos-index">

    <h4><?= Html::encode($this->title).' - '.$modelParalelo->course->name.' - '.$modelParalelo->name ?></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    
    <div class="container">
        
        <p>
        <?= Html::a('Exportar Todos a PDF', ['pdf','clase' => '', 'paralelo' => $paralelo, 'bloque' => $bloque], ['class' => 'btn btn-danger']) ?>        
        </p>
        
        <div class="table table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Materia</th>
                        <th>Profesor</th>
                        <th>Códgigo clase</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php
                    foreach ($clases as $data){
                        
                        $clase = $data['clase_id'];
                        
                        echo '<tr>';
                        echo '<td>'.$data['materia'].'</td>';
                        echo '<td>'.$data['last_name'].' '.$data['x_first_name'].'</td>';
                        echo '<td>'.$data['clase_id'].'</td>';
                        echo '<td>'.Html::a('Exportar PDF', ['pdf','clase' => $clase, 'paralelo' => $paralelo, 'bloque' => $bloque], ['class' => 'btn btn-danger']).'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
                
            </table>
        </div>
    </div>

    
</div>
