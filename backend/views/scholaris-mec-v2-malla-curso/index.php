<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2MallaCursoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cursos de la malla MEC: ' . $modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Malla MEC', 'url' => ['scholaris-mec-v2-malla/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-curso-index">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Asignado Curso a  Malla MEC', ['create','mallaId'=>$modelMalla->id], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'malla_id',
                'malla.nombre',
                'curso_id',
                'curso.name',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>
    </div>
</div>
