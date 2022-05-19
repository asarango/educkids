<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$usuario = Yii::$app->user->identity->usuario;
$periodoId = Yii::$app->user->identity->periodo_id;
$modelPerido = backend\models\ScholarisPeriodo::findOne($periodoId);

$modelBloque = backend\models\ScholarisBloqueActividad::find()
        ->where(['scholaris_periodo_codigo' => $modelPerido->codigo, 'tipo_uso' => $modelClase->tipo_usu_bloque])
        ->orderBy('orden')
        ->all();

$this->title = 'PUD: ' . $modelClase->curso->name . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
;
$this->params['breadcrumbs'][] = ['label' => 'Lista de Planificaciones', 'url' => ['index1','id' => $modelClase->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scholaris-plan-pud-index">

    <p><h3>Copiando planificaciones PUD</h3></p>

<div class="container">
    <div class="table table-responsive">
        <table class="table table-responsive table-hover table-condensed">
            <thead>
                <tr>
                    <th>BLOQUE</th>
                    <th>ASIGNATURA</th>
                    <th>DOCENTE</th>
                    <th>CURSO</th>
                    <th>PARALELO</th>
                    <th>TITULO</th>
                    <th>ESTADO</th>
                    <th colspan="2">ACCIONES</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                foreach ($model as $data){
                    echo '<tr>';
                    echo '<td>'.$data['bloque'].'</td>';
                    echo '<td>'.$data['materia'].'</td>';
                    echo '<td>'.$data['last_name'].' '.$data['x_first_name'].'</td>';
                    echo '<td>'.$data['curso'].'</td>';
                    echo '<td>'.$data['paralelo'].'</td>';
                    echo '<td>'.$data['titulo'].'</td>';
                    echo '<td>'.$data['estado'].'</td>';
                    echo '<td>';
                    echo Html::a('Copiar', ['copiaejecutar', 
                                            'pudId' => $data['pud_id'],
                                            'clase' => $modelClase->id
                                           ], ['class' => 'btn btn-primary']);
                    echo Html::a('Ver PDF', ['reporte-pud/index1', 'pudId' => $data['pud_id']], ['class' => 'btn btn-danger']);
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
            
        </table>
    </div>
</div>
       
</div>
