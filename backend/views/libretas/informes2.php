<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informes de Aprendizaje y Comportamiento: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Listado de cursos y paralelos', 'url' => ['informes-aprendizaje/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('images/pageLoader.gif') 50% 50% no-repeat rgb(249,249,249);
        opacity: .8;
    }
</style>
<div class="informes-aprendizaje-informes">

    <h4><?php //echo Html::encode($this->title)        ?></h4>


    <div class="table table-responsive" style="padding-left: 40px; padding-right: 40px">
        <table class="table table-hover table-condensed table-striped">
            <tr>
                <td><strong>REPORTE</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>ACCIÓN</strong></td>
            </tr>

<!--            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1']
                            , ['class' => 'btn btn-primary']);
                    ?>

            </tr>-->

            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 1(VERSION 1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1V1']
                            , ['class' => 'btn btn-default']);
                    ?>

            </tr>

            <tr>
                <td>INFORME DE APRENDIZAJE QUIMESTRE 2(VERSION 1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q2', 'reporte' => 'LIBRETAQ1V1']
                            , ['class' => 'btn btn-default']);
                    ?>

            </tr>

            <tr>
                <td>LIBRETA DE CALIFICACIONES V1-AQIA(VERSION ANTERIOR)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'LIBRETAQ1ISM']
                            , ['class' => 'btn btn-warning']);
                    ?>

            </tr>

<!--            <tr>
    <td>SABANA QUIMESTRE 1(Formato Hoja Electrónica)</td>
    <td>Por paralelo</td>
    <td>
            <?=
            Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                'quimestre' => 'q1', 'reporte' => 'SABANAQ1']
                    , ['class' => 'btn btn-info']);
            ?>
    </td>
</tr>-->


            <tr>
                <td>SABANA QUIMESTRE 1 (PDF)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q1', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>
            </tr>

            <tr>
                <td>SABANA QUIMESTRE 2 (PDF)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'q2', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>

            </tr>

            <tr>
                <td>SABANA FINAL (PDF)</td>
                <td>Por paralelo</td>

                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'final_ano_normal', 'reporte' => 'PRUEBA']
                            , ['class' => 'btn btn-danger boton']);
                    ?>
                </td>

            </tr>


        </table>




    </div>

    <hr>

    <h4><strong>Sabanas Parciales</strong></h4>



    <div class="table table-responsive" style="padding-left: 40px; padding-right: 40px">
        <table class="table table-hover table-condensed table-striped">
            <tr>
                <td><strong>REPORTE</strong></td>
                <td><strong>TIPO</strong></td>
                <td><strong>ACCIÓN</strong></td>
            </tr>

            <tr>
                <td>SABANA PARCIAL 1</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'quimestre' => 'p1', 'reporte' => 'SABANAPDFPARCIALES']
                            , ['class' => 'btn btn-primary']);
                    ?>

                </td>
            </tr>
        </table>
    </div>


</div>

<div class="loader" style="display: none"></div>

