<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMallaMateria */

$this->title = 'Asignatura del área: '.$modelArea->area->name;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Malla Materias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-malla-materia-create">


    <?= $this->render('_form', [
        'model' => $model,
        'modelArea' => $modelArea
    ]) ?>

</div>
