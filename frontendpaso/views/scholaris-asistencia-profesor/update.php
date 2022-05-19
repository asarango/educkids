<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ScholarisAsistenciaProfesor */

$this->title = 'Update Scholaris Asistencia Profesor: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Asistencia Profesors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-asistencia-profesor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
