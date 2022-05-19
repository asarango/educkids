<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArchivosPud */

$this->title = 'Subiendo Archivo Pud: ' . $modelClase->curso->name
        . ' | ' . $modelClase->paralelo->name
        . ' | ' . $modelClase->materia->name
        . ' | ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' | ' . $modelBloque->name;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de Archivos PUD', 'url' => ['index1','claseId'=>$modelClase->id, 'bloqueId'=>$modelBloque->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-archivos-pud-create" style="padding-left: 40px; padding-right: 40px">
    

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelBloque' => $modelBloque,
            'modelClase' => $modelClase
        ])
        ?>


</div>
