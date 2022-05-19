<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourseParalelo */

$this->title = 'Update Op Course Paralelo: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Op Course Paralelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="op-course-paralelo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
