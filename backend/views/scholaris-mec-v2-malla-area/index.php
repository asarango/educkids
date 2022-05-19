<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2MallaAreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Malla MEC: ' . $modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Mallas MEC', 'url' => ['scholaris-mec-v2-malla/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-area-index" style="margin-left: 40px; margin-right: 40px">


   
        
        <p>
            <?= Html::a('Ingresar Area', ['create', 'mallaId' => $modelMalla->id], ['class' => 'btn btn-success glyphicon glyphicon-plus']) ?>
        </p>
        
        <hr>
        <div class="table table-responsive">
<!--            <table class="table table-hover table-condensed table-striped table-bordered">
                <tr>
                    <td align="center"><strong>ÁREA</strong></td>
                    <td align="center"><strong>ASIGNATURA</strong></td>
                    <td align="center"><strong>ASIGNATURAS O AREA QUE SE HOMOLOGAN</strong></td>
                </tr>-->
                
                <?php
                foreach ($model as $area){
                    
                    if($area->promedia == true){
                        $promediaArea = '';
                    }else{
                        $promediaArea = '*';
                    }
                    
                    echo '<div class="table table-responsive">';
                    echo '<table class="table table-hover table-condensed table-striped table-bordered">';
                    echo '<tr>';
                    echo '<td align="center" colspan="4"><strong>';
                    echo $area->orden.'.- '.$area->asignatura->nombre.' '.$promediaArea;
                    echo Html::a('Editar', ['view', 'id' => $area->id], ['class' => 'btn btn-link']);
                    echo '</strong></td>';
                    echo '</tr>';
                    $modelMateria = backend\models\ScholarisMecV2MallaMateria::find()
                            ->where(['area_id' => $area->id])
                            ->all();
                    
                    echo '<tr>';
                    echo '<td colspan="2" class="well">';
                    echo Html::a('Crear_Asignatura', ['scholaris-mec-v2-malla-materia/create', 'id' => $area->id], ['class' => 'btn btn-warning glyphicon glyphicon-plus']);
                    echo '</td>';
                    
                    echo '<td colspan="2">';
                    echo '</td>';
                    echo '</tr>';
                    
                    foreach ($modelMateria as $mat){
                        
                        if($mat->promedia == true){
                            $promediaMateria = '';
                        }else{
                            $promediaMateria = '*';
                        }
                        
                        echo '<tr>';
                        echo '<td width="20%">'.$mat->orden.'.- '.$promediaMateria.' '.$mat->asignatura->nombre.'('.$mat->id.')'.'</td>';
                        echo '<td width="5%" align="center">'.Html::a('', ['scholaris-mec-v2-malla-materia/view', 'id' => $mat->id], ['class' => 'btn btn-primary glyphicon glyphicon-pencil']).'</td>';
                        echo '<td width="5%" align="center">'.Html::a('', ['scholaris-mec-v2-malla-disribucion/create', 'id' => $mat->id], ['class' => 'btn btn-success glyphicon glyphicon-plus']).'</td>';
                        
                        $modelDistribucion = \backend\models\ScholarisMecV2MallaDisribucion::find()
                                             ->where(['materia_id' => $mat->id])
                                             ->all();
                        
                        echo '<td>';
                        
                        
                        foreach ($modelDistribucion as $dist){
                            
                            $source = toma_recurso($dist->tipo_homologacion, $dist->codigo_materia_source);
                            
                            echo Html::a(' | '.$source->name.'('.$dist->tipo_homologacion.') | '.$dist->materia->tipo, 
                                                                   ['scholaris-mec-v2-malla-disribucion/delete1', 
                                                                   'id' => $dist->id], 
//                                                                   ['class' => 'btn btn-ling glyphicon glyphicon-plus']).'</td>';
                                                                   ['class' => 'btn btn-link']);
                        }
                        
                        echo '</td>';
                        
                        
                        echo '</tr>';
                    }
                    
                    
                    echo '</table>';
                    echo '</div>';
                    
                }
                ?>
                
            <!--</table>-->
        </div>



</div>

<?php
    function toma_recurso($tipo, $source){
        
        
        
        if($tipo == 'AREA'){
            $modelSource = \backend\models\ScholarisArea::findOne($source);
        }else{
            $modelSource = \backend\models\ScholarisMateria::findOne($source);
        }
        
        return $modelSource;
    }
?>
