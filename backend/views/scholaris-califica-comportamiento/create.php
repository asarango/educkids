<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCalificaComportamiento */

$this->title = 'Create Scholaris Califica Comportamiento';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Califica Comportamientos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-califica-comportamiento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
