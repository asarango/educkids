<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisLeccionario */

$this->title = 'Leccionario';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Leccionarios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-leccionario-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'paralelo' => $paralelo
    ]) ?>

</div>
