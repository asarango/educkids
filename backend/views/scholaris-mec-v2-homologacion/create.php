<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Homologacion */

$this->title = 'Homologación de materia: '.$modelDisti->materia->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Homologación de materia: ', 'url' => ['index1','materia' => $modelDisti->materia_id, 'curso' => $modelDisti->curso_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-homologacion-create">

 

    <?= $this->render('_form', [
        'model' => $model,
        'modelDisti' => $modelDisti
    ]) ?>

</div>
