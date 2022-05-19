<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourseParalelo */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Op Course Paralelos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="op-course-paralelo-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'create_uid',
            'last_date_invoice',
            'create_date',
            'name',
            'x_capacidad',
            'write_uid',
            'write_date',
            'course_id',
            'period_id',
            'institute_id',
            'capacidad',
            'aula',
        ],
    ]) ?>

</div>
