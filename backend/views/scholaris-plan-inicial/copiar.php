<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'PUD: ' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
;
$this->params['breadcrumbs'][] = ['label' => 'Lista de Planificaciones', 'url' => ['index1','id'=>$modelClase->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-index">
    
    <div class="container">
        <h3>Copiando Planificacion</h3>
        
        <div class="table table-responsive">
            <table class="table table-condensed table-bordered table-striped table-bordered">
                <tr>
                    <td>CURSO</td>
                    <td align="center">PARALELO</td>
                    <td colspan="2" align="center">ACCIONES</td>
                </tr>
                
                <?php
                foreach ($modelCursos as $curso){
                    echo '<tr>';
                    echo '<td>'.$curso['curso'].'</td>';
                    echo '<td align="center">'.$curso['paralelo'].'</td>';
                    echo '<td align="center">';
                    echo Html::a('', ['reporte', 
                                                'clase' => $curso['clase_id'],
                                                'quimestre' => $quimestre
                                               ], 
                                               ['class' => 'glyphicon glyphicon-book']);
//                                               ['class' => 'btn btn-primary']);
                    echo '</td>';
                    echo '<td align="center">';
                    echo Html::a('', ['copiarejecuta', 
                                                'clase_planificada' => $curso['clase_id'],
                                                'clase' => $modelClase->id,
                                                'quimestre' => $quimestre
                                               ], 
                                               ['class' => 'glyphicon glyphicon-camera']);
//                                               ['class' => 'btn btn-primary']);
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                
            </table>
        </div>
        
    </div>
    
</div>
