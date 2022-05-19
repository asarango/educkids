<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2AsignaturaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Áreas y Asignaturas MEC';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-asignatura-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <div class="container">
        <p>
            <?= Html::a('Crear Asignatura MEC', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'codigo',
                'nombre',
                'tipo',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]);
        ?>
    </div>

</div>
