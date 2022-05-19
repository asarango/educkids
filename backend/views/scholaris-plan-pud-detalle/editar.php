<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalles de Pud: ' . $model->pud->clase->curso->name . ' - ' . $model->pud->clase->paralelo->name
//        . ' / ' . $modelPud->clase->profesor->last_name . ' ' . $modelPud->clase->profesor->x_first_name
//        . ' / ' . $modelPud->clase->materia->name . '(' . $modelPud->clase->id . ')'
;

$this->params['breadcrumbs'][] = ['label' => 'Detalle de Unidades', 'url' => ['index1', 'id' => $model->pud_id]];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-detalle-editar">


    <style>
        #exTab1 .tab-content {
            color : white;
            background-color: #428bca;
            padding : 5px 15px;
        }

        #exTab2 h3 {
            color : white;
            background-color: #428bca;
            padding : 5px 15px;
        }

        /* remove border radius for the tab */

        #exTab1 .nav-pills > li > a {
            border-radius: 0;
        }

        /* change border radius for the tab , apply corners on top*/

        #exTab3 .nav-pills > li > a {
            border-radius: 4px 4px 0 0 ;
        }

        #exTab3 .tab-content {
            /*color : white;*/
            /*background-color: #CCCCCC;*/
            padding : 5px 15px;
        }

    </style>



    <div class="container"><div class="container"><h1>Detalle de destrezas PUD: <?= Html::encode($model->pud->titulo) ?> </h1></div></div>
    <div id="exTab3" class="container">	
        <ul  class="nav nav-pills">
            <li class="active"><a href="#5a" data-toggle="tab">Momentos Didácticos</a></li>
            <li>
                <a  href="#1b" data-toggle="tab">Destreza - Criterio</a>
            </li>
            <li><a href="#2b" data-toggle="tab">Indicadores</a>
            </li>
            <li><a href="#3b" data-toggle="tab">Ejes y Recursos</a>
            </li>
            <li><a href="#4a" data-toggle="tab">Tipos -tecnicas - Instrumentos</a>
                
            <li><a href="#6a" data-toggle="tab">Periodos</a>
            </li>
            
        </ul>
        <hr>

        <div class="tab-content clearfix">
            
             <div class="tab-pane active" id="5a">
                <div class="alert alert-light">
                    <strong>Actividades y momentos didácticos</strong>
                    <br>
                    <?php
//                    print_r($modelBloque);
                    $fechaActual = date('Y-m-d');

                    if ($fechaActual >= $modelBloque->desde && $fechaActual <= $modelBloque->hasta) {
                        echo Html::a('Crear Momento - Actividad', ['createactividad', 'id' => $model->id], ['class' => 'btn btn-success']);
                    } else {
                        echo '<div class="alert alert-danger">';
                        echo '<strong><h3>BLOQUE CERRADO</h3></strong>';
                        echo '</div>';
//                        echo Html::a('Crear Momento - Actividad', ['createactividad', 'id' => $model->id], ['class' => 'btn btn-success']);
                    }
                    ?>

                    <hr>

                    



                    <?php
                    $listaTiposAct = backend\models\ScholarisTipoActividad::find()->all();
                    $listaMomentos = \backend\models\ScholarisMomentosAcademicos::find()->all();
                    $listaFormativ = [
                        ['id' => 'F', 'nombre' => 'FORMATIVA'],
                        ['id' => 'S', 'nombre' => 'SUMATIVA'],
                    ];
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            'id',
                            [
                                'attribute' => 'tipo_actividad_id',
                                'vAlign' => 'top',
                                'value' => function($model, $key, $index, $widget) {
                                    return $model->insumo->nombre_nacional;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map($listaTiposAct, 'id', 'nombre_nacional'),
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'formativa_sumativa',
                                'vAlign' => 'top',
                                'value' => function($model, $key, $index, $widget) {
                                    return $model->formativa_sumativa;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map($listaFormativ, 'id', 'nombre'),
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'momento_id',
                                'vAlign' => 'top',
                                'value' => function($model, $key, $index, $widget) {
                                    return $model->momento->nombre;
                                },
                                'filterType' => GridView::FILTER_SELECT2,
                                'filter' => ArrayHelper::map($listaMomentos, 'id', 'nombre'),
                                'filterWidgetOptions' => [
                                    'pluginOptions' => ['allowClear' => true],
                                ],
                                'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                                'format' => 'raw',
                            ],
                            'title',
                            'con_nee',
                            /** INICIO BOTONES DE ACCION * */
                            [
                                'class' => 'kartik\grid\ActionColumn',
                                'dropdown' => false,
                                'width' => '150px',
                                'vAlign' => 'middle',
                                'template' => '{actualizar}{recursos}{borrar}',
                                'buttons' => [
                                    'actualizar' => function($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
                                                    'title' => 'Actualizar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                        ]);
                                    },
                                    'borrar' => function($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                                    'title' => 'Eliminar', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                        ]);
                                    },
                                    'recursos' => function($url, $model) {
                                        return Html::a('<span class="glyphicon glyphicon-tree-deciduous"></span>', $url, [
                                                    'title' => 'Recursos', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                        ]);
                                    },
                                            
                                ],
                                'urlCreator' => function($action, $model, $key) {
                                    if ($action === 'actualizar') {
                                        return \yii\helpers\Url::to(['actualizar', 'id' => $key]);
                                    } else if ($action === 'borrar') {
                                        return \yii\helpers\Url::to(['borrar', 'id' => $key]);
                                    }else if ($action === 'recursos') {
                                        return \yii\helpers\Url::to(['scholaris-actividad-recursos/index1', 'id' => $key]);
                                    }
                                }
                            ],
                        /** FIN BOTONES DE ACCION * */
                        ],
                    ]);
                    ?>


                </div>
            </div>
            
            
            
            <div class="tab-pane" id="1b">
                <div class="row">
                    <div class="alert alert-info">
                        <h5><strong>DESTREZA CON CRITERIO DE DESEMPEÑO</strong></h5>
                        <h5><?= $model->codigo . ': ' . $model->contenido ?></h5>
                    </div>


                    <div class="alert alert-success">
                        <h5><strong>CRITERIO DE EVALUACIÓN</strong></h5>
                        <h5><?= criterio_evaluacion($model, 'evaluacion') ?></h5>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="2b">

                <div class="alert alert-warning">
                    <p><u>INDICADORES PARA LA EVALUACION DE CRITERIO</u></p>

                    <?php // echo Html::beginForm(['ingresa-destreza', 'post']); ?>
                    <?php
                    $modelCurrDes = \backend\models\CurCurriculo::find()->where(['codigo' => $model->codigo])->one();
                    $modelCureInd = \backend\models\CurCurriculo::find()
                            ->where(['pertence_a' => $modelCurrDes->pertence_a, 'tipo_referencia' => 'indicador'])
                            ->all();

                    $data = ArrayHelper::map($modelCureInd, 'codigo', 'detalle');

                    // echo '<label class="control-label">Seleccione la destreza:</label>';
                    echo Select2::widget([
                        'name' => 'selecciones',
                        'value' => '',
                        'data' => $data,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione indicador para evaluación de criterio...',
                            'onchange' => 'selecciones(this,"' . Url::to(['registraselecciones']) . '",\'' . $model->codigo . '\',' . $model->pud_id . ',\'indicador\');',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    ?>

                    <hr>

                    <div id="indicadores"></div>

                </div>         

            </div>


            <div class="tab-pane" id="3b">
                <div class="alert alert-warning">
                    <p><u>EJE TRANSVERSAL</u></p>

                    <?php // echo Html::beginForm(['ingresa-destreza', 'post']);  ?>
                    <?php
                    $modelEjes = backend\models\CurOpciones::find()->where(['codigo' => 'ejes'])->all();
                    $data = ArrayHelper::map($modelEjes, 'detalle', 'detalle');

                    //echo '<label class="control-label">Seleccione la destreza:</label>';
                    echo Select2::widget([
                        'name' => 'selecciones',
                        'value' => '',
                        'data' => $data,
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione destreza...',
                            'onchange' => 'selecciones(this,"' . Url::to(['registraselecciones']) . '",\'' . $model->codigo . '\',' . $model->pud_id . ',\'eje\');',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    ?>

                    <hr>

                    <div id="ejes"></div>

                </div>

                <div class="alert alert-warning">
                    <p><u>RECURSOS</u></p>
                    <?php $url1 = Url::to(['registraselecciones']); ?>                    
                    <input type="text" class="form-control" onchange="selecciones(this, '<?= $url1 ?>', '<?= $model->codigo ?>',<?= $model->pud_id ?>, 'recurso')">

                    <hr>

                    <div id="recursosx"></div>

                </div> 




            </div>
            <div class="tab-pane" id="4a">

                <div class="row">
                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <p><u>TIPOS</u></p>
                            <?php $url1 = Url::to(['registraselecciones']); ?>                    
                            <input type="text" class="form-control" onchange="selecciones(this, '<?= $url1 ?>', '<?= $model->codigo ?>',<?= $model->pud_id ?>, 'tipos')">

                            <hr>

                            <div id="tipos"></div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <p><u>TECNICAS</u></p>
                            <?php $url1 = Url::to(['registraselecciones']); ?>                    
                            <input type="text" class="form-control" onchange="selecciones(this, '<?= $url1 ?>', '<?= $model->codigo ?>',<?= $model->pud_id ?>, 'tecnicas')">

                            <hr>

                            <div id="tecnicas"></div>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <p><u>INSTRUMENTOS</u></p>
                            <?php $url1 = Url::to(['registraselecciones']); ?>                    
                            <input type="text" class="form-control" onchange="selecciones(this, '<?= $url1 ?>', '<?= $model->codigo ?>',<?= $model->pud_id ?>, 'instrumentos')">

                            <hr>

                            <div id="instrumentos"></div>

                        </div>
                    </div>

                </div>




            </div>
            
            
            <div class="tab-pane" id="6a">

                <div class="row">
                    

                    <div class="col-md-4">
                        <div class="alert alert-warning">
                            <p><u>PERIODOS:</u></p>
                            <?php $url1 = Url::to(['actualizaperiodos']); ?>                    
                            <input type="text" class="form-control" 
                                   value="<?= $model->cantidad_periodos ?>"
                                   onchange="selecciones(this, '<?= $url1 ?>', '<?= $model->codigo ?>',<?= $model->id ?>, 'tecnicas')">

                            <hr>

                            <div id="tecnicas"></div>

                        </div>
                    </div>
                        
                </div>




            </div>


           
        </div>
    </div>


</div>

<script>

    muestra_ejes();
    muestra_indicadores();
    muestra_tecnicas('tecnicas');
    muestra_tecnicas('tipos');
    muestra_tecnicas('instrumentos');
    muestra_recursos();

    function ingresar(obj, pud, codigo, tipo) {
        var contenido = $(obj).val();

        var url = "<?= Url::to(['ingresardetalles']) ?>";
        var parametros = {
            "pud": pud,
            "contenido": contenido,
            "codigo": codigo,
            "tipo": tipo
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                //$("#destrezasDiv").html(response);

            }
        });

    }



    function modificar(obj, id) {
        var contenido = $(obj).val();
        var url = "<?= Url::to(['modificardetalles']) ?>";
        var parametros = {
            "id": id,
            "contenido": contenido
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                location.reload();

            }
        });

    }


    function selecciones(obj, url, codigo, pudid, opcion) {

        var contenido = $(obj).val();
        var parametros = {
            "contenido": contenido,
            "codigo": codigo,
            "id": pudid,
            'opcion': opcion
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'post',
            beforeSend: function () {

            },
            success:  function (response) {

                muestra_ejes();
                muestra_indicadores();
                muestra_tecnicas('tecnicas');
                muestra_tecnicas('tipos');
                muestra_tecnicas('instrumentos');
                muestra_recursos();

            }
        });


    }


    function muestra_ejes() {
        var id = <?= $model->id ?>;
        var url = "<?= Url::to(['mustraejes']) ?>";
        var parametros = {
            "id": id
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#ejes").html(response);
            }
        });


    }

    function muestra_indicadores() {
        var id = <?= $model->id ?>;
        var url = "<?= Url::to(['muestraindicadores']) ?>";
        var parametros = {
            "id": id
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#indicadores").html(response);
            }
        });
    }


    function muestra_tecnicas(opcion) {
        var id = <?= $model->id ?>;
        var url = "<?= Url::to(['muestratecnicas']) ?>";
        var parametros = {
            "id": id,
            "opcion": opcion
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#" + opcion).html(response);
            }
        });
    }

    function muestra_recursos() {
        var id = <?= $model->id ?>;
        var url = "<?= Url::to(['muestrarecursos']) ?>";
        var parametros = {
            "id": id
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#recursosx").html(response);
            }
        });
    }


</script>


<?php

function opciones($model, $opcion) {
    $modelDet = \backend\models\ScholarisPlanPudDetalle::find()
            ->where([
                'tipo' => $opcion,
                'pud_id' => $model->pud_id,
                'pertenece_a_codigo' => $model->codigo
            ])
            ->one();

    if ($modelDet) {
        echo '<textarea class="form-control" rows="5" onchange="modificar(this,' . $modelDet->id . ')">' . $modelDet->contenido . '</textarea>';
    } else {
        echo '<textarea class="form-control" rows="5" onchange="ingresar(this,' . $model->pud_id . ',\'' . $model->codigo . '\',\'' . $opcion . '\')"></textarea>';
    }
}

function criterio_evaluacion($model, $opcion) {
    $modelDet = \backend\models\ScholarisPlanPudDetalle::find()
            ->where([
                'tipo' => $opcion,
                'pud_id' => $model->pud_id,
                'pertenece_a_codigo' => $model->codigo
            ])
            ->one();

    if ($modelDet) {
        echo '<p>' . $modelDet->contenido . '</p>';
    } else {
        echo '<p>No existe criterio de evaluación configurado correctamente!!!</p>';
    }
}
?>