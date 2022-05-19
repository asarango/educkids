<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Dia */

$this->title = 'Create Scholaris Horariov2 Dia';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Horariov2 Dias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-dia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
