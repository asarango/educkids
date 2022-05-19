<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisActividadRecursos */

$this->title = 'Create Scholaris Actividad Recursos';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Actividad Recursos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-recursos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
