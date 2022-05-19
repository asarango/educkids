<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourseParalelo */

$this->title = 'Create Op Course Paralelo';
$this->params['breadcrumbs'][] = ['label' => 'Op Course Paralelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-course-paralelo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
