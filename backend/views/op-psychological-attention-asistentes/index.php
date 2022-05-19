<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OpPsychologicalAttentionAsistentesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asisten a la reunión';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-psychological-attention-asistentes-index">

    <h4><strong><?= Html::encode($this->title) ?></strong></h4>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Agregar asistente', ['/op-psychological-attention-asistentes/create','attentionId' => $attentionId], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
//            'create_uid',
//            'create_date',
            'name',
//            'write_uid',
            //'psychological_attention_id',
            //'write_date',

             /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
//                    'template' => '{informes}{reporte}{comportamiento} {informes2}',
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'informes' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-folder-open"></span>2019-2020', $url, [
                                        'title' => 'INFORMES 2019-2020', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
                                'libretas' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-book"></span>', $url, [
                                        'title' => 'INFORMES 2020-2021', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'update') {
                            return \yii\helpers\Url::to(['/op-psychological-attention-asistentes/update', 'id' => $key]);                        
                        }elseif($action === 'delete'){
                            return \yii\helpers\Url::to(['/op-psychological-attention-asistentes/delete', 'id' => $key]);
                        }
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
