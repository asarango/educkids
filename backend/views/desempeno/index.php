<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RolSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$modelRutaGraficos = backend\models\ScholarisParametrosOpciones::find()
        ->where(['codigo' => 'graficos'])
        ->one();
$rutaGraficos = $modelRutaGraficos->nombre;

$this->title = 'Desempeños de los cursos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="desempeno-index">



    <div class="row">

        <div class="col-md-8">
            <div class="row">
                <center><p>QUIMESTRE I</p></center>

                <div class="col-md-4">            
                    <center><p class="">PARCIAL 1</p></center>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p1', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    //$graphLink = "http://localhost/graficos/menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">

                </div>

                <div class="col-md-4">
                    <p class="alinearCentro">PARCIAL 2</p>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p2', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">
                </div>
                <div class="col-md-4">
                    <p class="alinearCentro">PARCIAL 3</p>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p3', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">
                </div>


            </div>
            <br>
            <br>
            <br>
            <hr>
            
            <div class="row">
                <center><p>QUIMESTRE II</p></center>

                <div class="col-md-4">            
                    <p class="alinearCentro">PARCIAL 4</p>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p4', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">

                </div>

                <div class="col-md-4">
                    <p class="alinearCentro">PARCIAL 5</p>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p5', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">
                </div>
                <div class="col-md-4">
                    <p class="alinearCentro">PARCIAL 6</p>
                    <?php
                    $modelDatos = new backend\models\OpCourseSearch();
                    $datos = $modelDatos->datosCuadrosParciales('p6', $periodoCodigo);
                    $x = array();
                    $y = array();
                    foreach ($datos as $dato) {

                        array_push($y, substr($dato['curso'],0,3));
                        array_push($x, $dato['total']);
                    }

                    $dat = urldecode(serialize($x));
                    $labels = urlencode(serialize($y));
                    $graphLink = "$rutaGraficos"."menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                    ?>
                    <img src="<?= $graphLink ?>" width="300px" class="img-thumbnail">
                </div>


            </div>
            
        </div>


        <div class="col-md-4">
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
                        'filter' => ArrayHelper::map($modelCursos, 'id', 'name'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => 'Seleccione...'],
                        'format' => 'raw',
                    ],
                    'name',
                    /** INICIO BOTONES DE ACCION * */
                    [
                        'class' => 'kartik\grid\ActionColumn',
                        'dropdown' => false,
                        'width' => '150px',
                        'vAlign' => 'middle',
                        'template' => '{detalles}',
                        'buttons' => [
                            'detalles' => function($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                                            'title' => 'Detalles_paralelo', 'data-toggle' => 'tooltip', 'role' => 'modal-remote', 'data-pjax' => "0", 'class' => 'hand'
                                ]);
                            }
                        ],
                        'urlCreator' => function($action, $model, $key) {
                            if ($action === 'detalles') {
                                return \yii\helpers\Url::to(['detalle', 'id' => $key]);
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
