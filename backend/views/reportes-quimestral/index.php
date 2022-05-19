<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'REPORTES QUIMESTRALES Y FINALES';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="rportes-parcial-index">-->


<div class="reportes-quimestral-index" style="padding-left: 40px; padding-right: 40px">

    <h3><?= $this->title ?></h3>

    <div class="row">
        <div class="col-lg-3"></div>
        <div class="col-lg-6">
            <?php echo Html::beginForm(['reporte', 'post']); ?>

            <?php
            $listData = ArrayHelper::map($modelCursos, 'id', 'name');

            echo '<label class="control-label">Curso:</label>';
            echo Select2::widget([
                'name' => 'curso',
                'value' => 0,
                'data' => $listData,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione curso',
                    'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);

            echo '<label class="control-label">Paralelo:</label>';
            ?>

            <div id="paralelo"></div>

            <label class="control-label">Estudiante:</label>

            <div id="alumno"></div>


            <?php
            echo '<label class="control-label">Reporte:</label>';
            echo Select2::widget([
                'name' => 'reporte',
                'value' => 0,
                'data' => [
                    'scholaris-cer-matricula/index' => 'CERTIFICADO DE MATRÍCULAS',
                    'quito-scholaris-cer-matricula/index' => 'CERTIFICADO DE MATRÍCULAS (opción 2)',
                    'quito-scholaris-cer-matricula3/index' => 'CERTIFICADO DE MATRÍCULAS (opción 3)',
                    'scholaris-cer-ficha-matricula/index' => 'FICHA DE MATRÍCULAS',
                    'scholaris-cer-ficha-matricula-sin-firmas/index' => 'FICHA DE MATRÍCULAS SIN FIRMAS',
                    'scholaris-rep-libreta2/index' => 'LIBRETAS 2',
                    //'scholaris-rep-sabana/index' => 'SABANA',
//            'scholaris-clase-libreta/paralelo' => 'PROMEDIOS TOTALES',
                    'listados/index' => 'LISTADOS',
                    'scholaris-rep-sabana-cualitativas/index' => 'LIBRETAS CUALITATIVOS',
                //'reportes-mec-normal/index' => 'REPORTES MEC NORMALES',
                ],
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione curso',
//            'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
            <br>
            <?php
            echo Html::submitButton(
                    'Aceptar',
                    ['class' => 'btn btn-primary']
            );
            ?>  
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>


<script>
    function CambiaParalelo(obj, url)
    {
        //var instituto = $(obj).val();
        var parametros = {
            "id": $(obj).val(),
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#paralelo").html(response);

            }
        });
    }


    function mostrarAlumnos(obj, url) {
        var parametros = {
            "id": $(obj).val()
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#alumno").html(response);
            }
        });
    }

</script>