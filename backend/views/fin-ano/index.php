<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'Cierre de Fin de Año';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <div class="row">
        <div class="col-md-6">

            <div class="row">
                         
                
                <div class="col-lg-6">
                    
                    <p class="text-warning" align="center">Fecha maxima de cierre:</p>
                   
                   <h1><p class="text-warning" align="center"><?= $fechaMaxima ?></p></h1>
                    
                </div>

                <div class="col-lg-6">

                    <?=
                    Html::beginForm(['activaboton'], 'post', [
                        'class' => "",
                        'role' => 'form'
                    ])
                    ?>

                    <div class="form-group">
                        <label class="" for="ejemplo_email_2">Activado desde:</label>
                        <input type="type" class="form-control" id="inicia" name="inicia"
                               placeholder="2020-06-01 00:00:00">
                        <?php
//                        echo DatePicker::widget([
//                            'name' => 'inicia',
////                       'value' => date('Y-m-d', strtotime('+2 days')),
//                            'value' => '',
//                            'options' => ['placeholder' => 'Activiado desde ...'],
//                            'pluginOptions' => [
//                                'format' => 'yyyy-mm-dd',
//                                'todayHighlight' => true
//                            ]
//                        ]);
                        ?>

                    </div>
                

                <div class="form-group">
                    <label class="" for="ejemplo_email_2">Acivado hasta:</label>
                    <input type="type" class="form-control" id="finaliza" name="finaliza"
                               placeholder="2020-06-05 23:59:59">
                    <?php
//                    echo DatePicker::widget([
//                        'name' => 'finaliza',
////                       'value' => date('Y-m-d', strtotime('+2 days')),
//                        'value' => '',
//                        'options' => ['placeholder' => 'Activiado hasta ...'],
//                        'pluginOptions' => [
//                            'format' => 'yyyy-mm-dd',
//                            'todayHighlight' => true
//                        ]
//                    ]);
                    ?>
                </div>
                    <input type="hidden" name="tipo" value="todos">
                <?= Html::submitButton('Aceptar', ['class' => 'btn btn-primary btn-block']) ?>

                <?= Html::endForm() ?>


            </div>
                
            </div>
            
            <hr>
            <div class="row">
                
            </div>

        </div>
        <div class="col-md-6">
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
//            'id',
//            'course_id',
                    [
                        'attribute' => 'course_id',
                        'vAlign' => 'top',
                        'value' => function($model, $key, $index, $widget) {
                            return $model->course->name;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map($listaCursos, 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                        'format' => 'raw',
                    ],
                    'name',
                    [
//                'attribute' => 'course_id',
                        'label' => 'Sin ejecutar',
                        'vAlign' => 'top',
                        'value' => function($model, $key, $index, $widget) {

                            $modelTotal = \backend\models\ScholarisClaseLibreta::find()
                                    ->innerJoin("scholaris_grupo_alumno_clase g", "g.id = scholaris_clase_libreta.grupo_id")
                                    ->innerJoin("scholaris_clase c", "c.id = g.clase_id")
                                    ->where(['c.paralelo_id' => $model->id])
                                    ->andWhere(['scholaris_clase_libreta.estado' => null])
                                    ->count();

                            return $modelTotal;
                        },
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => ArrayHelper::map($listaCursos, 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                        'format' => 'raw',
                    ],
//            'nombre',
//            'orden',
                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        'width' => '150px',
                        'vAlign' => 'middle',
                        'template' => '{cerrar}',
                        'buttons' => [
                            'cerrar' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-road"></span>', $url, [
                                            'title' => 'CERRAR_AÑO', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function($action, $model, $key) {
                            if ($action === 'cerrar') {
                                return \yii\helpers\Url::to(['detallecerrar', 'id' => $key]);
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
