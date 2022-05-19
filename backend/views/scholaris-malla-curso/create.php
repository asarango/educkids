<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaCurso */

$this->title = 'Asignación de curso a la malla: '.$modelMalla->nombre_malla;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Cursos', 'url' => ['index1','id' => $modelMalla->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-curso-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelMalla' => $modelMalla
    ]) ?>

</div>
