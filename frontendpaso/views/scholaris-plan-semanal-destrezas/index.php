<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanSemanalDestrezasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Scholaris Plan Semanal Destrezas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-semanal-destrezas-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Scholaris Plan Semanal Destrezas', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'curso_id',
            'faculty_id',
            'semana_id',
            'comparte_valor',
            'concepto:ntext',
            //'contexto:ntext',
            //'pregunta_indagacion:ntext',
            //'enfoque:ntext',
            //'creado_por',
            //'creado_fecha',
            //'actualizado_por',
            //'actualizado_fecha',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
