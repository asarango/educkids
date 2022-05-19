<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanSemanalDestrezas */

$this->title = 'Editar Plan Semanal Destrezas: ' . $model->curso_id;
$this->params['breadcrumbs'][] = ['label' => 'Plan Semanal - Planificación de Destrezas', 'url' => ['plan-semanal/destrezas', 'id' => $observacion, 'facultyId' => $model->faculty_id]];
//$this->params['breadcrumbs'][] = ['label' => $model->curso_id, 'url' => ['view', 'curso_id' => $model->curso_id, 'faculty_id' => $model->faculty_id, 'semana_id' => $model->semana_id, 'comparte_valor' => $model->comparte_valor]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="scholaris-plan-semanal-destrezas-update">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>
