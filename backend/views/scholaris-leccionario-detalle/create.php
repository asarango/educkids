<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionarioDetalle */

$this->title = 'Create Scholaris Leccionario Detalle';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Leccionario Detalles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-leccionario-detalle-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
