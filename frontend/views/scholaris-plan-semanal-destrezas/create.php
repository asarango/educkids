<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisPlanSemanalDestrezas */

$this->title = 'Create Scholaris Plan Semanal Destrezas';
$this->params['breadcrumbs'][] = ['label' => 'Plan Semanal - Planificación de Destrezas', 'url' => ['plan-semanal/destrezas','id' => $observacion, 'facultyId'=>$profesor]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-semanal-destrezas-create">

    <div class="container">
        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'curso' => $curso,
            'profesor' => $profesor,
            'semana' => $semana,
            'uso' => $uso,
            'model' => $model
        ])
        ?>

    </div>
</div>
