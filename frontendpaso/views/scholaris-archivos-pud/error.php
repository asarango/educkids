<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisArchivosPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Error al cargar archivo';
//$this->params['breadcrumbs'][] = ['label' => 'Detalle de Actividades', 'url' => ['profesor-inicio/actividades','id' => $modelClase->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-archivos-pud-error">


    <div class="container">

        <div class="alert alert-danger">El archivo no es PDF</div>
        
    </div>
</div>
