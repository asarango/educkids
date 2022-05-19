<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informes de Aprendizaje y Comportamiento: ' . $modelParalelo->course->name . ' - ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = ['label' => 'Listado de cursos y paralelos', 'url' => ['mec/index']];
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


            <tr>
                <td>REPORTE DE RESUMEN FINAL DE CALIFICACIONES</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'RESUMENFINAL'], 
                        ['class' => 'btn btn-danger']);
                    ?>

            </tr>
            
            <tr>
                <td>NÓMINA MATRICULADOS (1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'MATRICULADOS'], 
                        ['class' => 'btn btn-warning']);
                    ?>

            </tr>
            
            <tr>
                <td>NÓMINA MATRICULADOS (2)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'MATRICULADOS2'], 
                        ['class' => 'btn btn-warning']);
                    ?>

            </tr>
            
            <tr>
                <td>NÓMINA MATRICULADOS EXCEL (2)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'MATRICULADOS2EXCEL'], 
                        ['class' => 'btn btn-warning']);
                    ?>

            </tr>
            
            <tr>
                <td>QUIMESTRALES</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'QUIMESTRAL'], 
                        ['class' => 'btn btn-info']);
                    ?>

            </tr>
            
            <tr>
                <td>FINALES CON SUPLETORIO</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'FINAL'], 
                        ['class' => 'btn btn-primary']);
                    ?>

            </tr>
            
            <tr>
                <td>CUADRO REMEDIALES</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'REMEDIAL'], 
                        ['class' => 'btn btn-default']);
                    ?>

            </tr>
            
            <tr>
                <td>PROMOCIONES (1)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'PROMOCION'], 
                        ['class' => 'btn btn-success']);
                    ?>

            </tr>
            
            <tr>
                <td>PROMOCIONES (2)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'PROMOCION2'], 
                        ['class' => 'btn btn-warning']);
                    ?>

            </tr>
            
            <tr>
                <td>PROMOCIONES (3)</td>
                <td>Por paralelo</td>
                <td>
                    <?=
                    Html::a('GENERAR', ['informedireccion2', 'paralelo' => $modelParalelo->id,
                        'reporte' => 'PROMOCION3'], 
                        ['class' => 'btn btn-success']);
                    ?>

            </tr>

        </table>
    </div>


</div>

<div class="loader" style="display: none"></div>

