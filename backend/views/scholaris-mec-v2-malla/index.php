<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2MallaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mallas MEC';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-index" style="padding-left: 10px; padding-right: 10px">
    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <p>
            <?= Html::a('Crear Malla MEC', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <div class="table table-responsive">
            <?=
            GridView::widget([
                'dataProvider'  => $dataProvider,
                'filterModel'   => $searchModel,
                'options'       => ['class' => 'table table-consensed' ],
                'columns'       => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'codigo',
                    'nombre',
                    'periodo.nombre',
//            'periodo_id',

                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        'width' => '150px',
                        'vAlign' => 'middle',
                        'template' => '{view}  {update}  {detalle}  {cursos}',
                        'buttons' => [
                            'detalle' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-indent-left"></span>', $url, [
                                            'title' => 'DETALLE_MALLA', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            },
                            'cursos' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-subtitles"></span>', $url, [
                                            'title' => 'CURSOS', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function($action, $model, $key) {
                            if ($action === 'view') {
                                return \yii\helpers\Url::to(['view', 'id' => $key]);
                            } else if ($action === 'update') {
                                return \yii\helpers\Url::to(['update', 'id' => $key]);
                            } else if ($action === 'detalle') {
                                return \yii\helpers\Url::to(['scholaris-mec-v2-malla-area/index1', 'id' => $key]);
                            } else if ($action === 'cursos') {
                                return \yii\helpers\Url::to(['scholaris-mec-v2-malla-curso/index1', 'id' => $key]);
                            }
                        }
                    ],
                /** FIN BOTONES DE ACCION * */
                ],
            ]);
            ?>
        </div>
    </div>
</div>
