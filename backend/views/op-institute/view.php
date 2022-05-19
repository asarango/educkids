<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OpInstitute */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Op Institutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-institute-view">

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
            'store_id',
            'write_uid',
            'write_date',
            'direccion',
            'codigo_amie',
            'email:email',
            'telefono',
            'rector',
            'secretario',
            'inspector_general',
            'celular',
            'inscription_state',
            'enrollment_deposit_message:ntext',
            'codigo_distrito',
            'enrollment_payment_way_message_year:ntext',
            'enrollment_payment_way_message_month:ntext',
            'name',
        ],
    ]) ?>

</div>
