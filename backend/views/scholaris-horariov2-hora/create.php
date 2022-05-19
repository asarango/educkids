<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Hora */

$this->title = 'Create Scholaris Horariov2 Hora';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Horariov2 Horas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-hora-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
