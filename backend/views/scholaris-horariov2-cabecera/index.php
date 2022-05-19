<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisHorariov2CabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'HORARIOS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-cabecera-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Horario', ['create','periodo' => $periodoId], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'id',
            'descripcion',
            'periodo_id',

            /** INICIO BOTONES DE ACCION * */
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'dropdown' => false,
                    'width' => '150px',
                    'vAlign' => 'middle',
                    'template' => '{view}{update}{construir}',
                    'buttons' => [
                        'construir' => function($url, $model) {
                            return Html::a('<span class="glyphicon glyphicon-wrench"></span>', $url, [
                                        'title' => 'Construir_horario', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                            ]);
                        },
//                        'destreza' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-tasks"></span>', $url, [
//                                        'title' => 'Destrezas', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        },'evaluacion' => function($url, $model) {
//                            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', $url, [
//                                        'title' => 'Evaluaciones', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
//                            ]);
//                        }
                    ],
                    'urlCreator' => function($action, $model, $key) {
                        if ($action === 'view') {
                            return \yii\helpers\Url::to(['plan-curriculo-objetivos/index1', 'id' => $key]);                        
                        } else if ($action === 'update') {
                            return \yii\helpers\Url::to(['scholaris-clase-aux/update', 'id' => $key]);
                        }else if ($action === 'construir') {
                            return \yii\helpers\Url::to(['scholaris-horariov2-detalle/index1', 'id' => $key]);
                        }    
                    }
                ],
            /** FIN BOTONES DE ACCION * */
        ],
    ]); ?>
</div>
