<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OpCourse */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Op Courses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="op-course-view">

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
            'code',
            'create_date',
            'name',
            'evaluation_type',
            'write_uid',
            'parent_id',
            'write_date',
            'section_moved0',
            'x_template_id',
            'x_capacidad',
            'x_institute',
            'section_moved1',
            'orden',
            'abreviatura',
            'level_id',
            'section_moved2',
            'section_moved3',
            'section_moved4',
            'section_moved5',
            'section_moved6',
            'section_moved7',
            'section_moved8',
            'section',
            'period_id',
        ],
    ]) ?>

</div>
