<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourse */

$this->title = 'Create Op Course';
$this->params['breadcrumbs'][] = ['label' => 'Op Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
