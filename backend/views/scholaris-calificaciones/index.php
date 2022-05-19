<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use backend\models\PlanCurriculoDistribucion;
use backend\models\OpCourse;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificacion de actividad';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
$pdfHMTLHeader = 'EMPRESA';
$pdfHeader = [
    'L' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'C' => [
        'content' => $pdfTitle,
        'font-size' => 12,
        //'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        'content' => $pdfHMTLHeader,
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];
$pdfFooter = [
    'L' => [
        'content' => '',
        'font-size' => 8,
        'font-style' => '',
        'font-family' => 'arial',
        'color' => '#929292'
    ],
    'C' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        'content' => '{PAGENO}',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];
?>
<div class="scholaris-calificaciones-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">

        <p>
            <?php // Html::a('Crear Planificacion', ['create'], ['class' => 'btn btn-success'])  ?>
        </p>

        <div class="alert alert-success alert-dismissable">
            <!--<button type="button" class="close" data-dismiss="alert">&times;</button>-->
            <strong><?= $modelCalificacion->tipo->nombre_nacional ?> ... </strong> 
            <?= $modelCalificacion->actividad->title ?>
            <br>
            <strong>Observación:</strong> 
            <?= $modelCalificacion->observacion ?>
            <br>
            <center><h1><?= $modelCalificacion->calificacion ?></h1></center>
        </div>

        <hr>

        <?php echo Html::beginForm(['cambia-nota', 'post']); ?>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label">Nota:</label>
                <input type="text" name="nota" id="nota" required="" class="form-control">
            </div>

            
            <div class="col-md-3">
                 <label class="control-label">Motivo de cambio:</label>
                 <select name="motivo" id="motivo" required="" class="form-control">
                <option value="ERROR DE DIGITACIÓN">ERROR DE DIGITACIÓN</option>
                <option value="AUSENCIA JUSTIFICADA ESTUDIANTE">AUSENCIA JUSTIFICADA ESTUDIANTE</option>
                <option value="DESHONESTIDAD ACADÉMICA">DESHONESTIDAD ACADÉMICA</option>
            </select>
            </div>
            
            <div class="col-md-3">
                <label class="control-label">Documento:</label>
                <input type="text" name="documento" id="documento" required="" class="form-control">
            </div>
            
            <div class="col-md-3">
                <label class="control-label">Autorizado por:</label>
                <input type="text" name="autorizado" id="autorizado" required="" class="form-control">
            </div>
        
        </div>
        
        
        
        <br>
        <div class="row">
            <input type="hidden" name="idCalif" value="<?= $modelCalificacion->id ?>">
        <?php
        echo Html::submitButton(
                'Modificar', ['class' => 'btn btn-primary']
        );
        ?>
        
        </div>
        
        <?php echo Html::endForm(); ?>


    </div>
</div>