<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\ScholarisArchivosprofesor */

$this->title = 'Archivos para la actividad: '.$modelActividad->title;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Archivosprofesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-archivosprofesor-create">

    
    <?= $this->render('_form', [
        'model' => $model,
        'modelActividad' => $modelActividad
    ]) ?>

</div>
