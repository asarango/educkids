<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reportes Parciales';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="rportes-parcial-index">-->

<h1><?= Html::encode($this->title) ?></h1>


<div class="container">
    
    
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

    <label class="control-label">Bloque:</label>

    <div id="bloque"></div>


    <?php
    echo '<label class="control-label">Reporte:</label>';
    echo Select2::widget([
        'name' => 'reporte',
        'value' => 0,
        'data' => [
            'scholaris-rep-parcial/index' => 'REPORTE PARCIAL',
            'scholaris-rep-promedios/index' => 'PROMEDIOS DE MAYOR A MENOR',
            'scholaris-rep-insumos/index' => 'CUADRO DE NOTAS POR CURSOS Y MATERIAS',
            'scholaris-rep-notas-curso/index' => 'CUADRO DE NOTAS POR CURSOS',
            'scholaris-rep-cualitativas/index' => 'ASIGNATURAS CUALITATIVAS',
            'scholaris-rep-comportamiento/index' => 'COMPORTAMIENTO',
            'scholaris-rep-notas-curso/recalcula' => 'REVISIÓN DE NOTAS'            
        ],
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
    ?>
    <br>
    <?php
        echo Html::submitButton(
                'Aceptar',
                ['class' => 'btn btn-primary']
        );
    ?>

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


    function mostrarBloque(obj, url) {
        var parametros = {
            "id": $(obj).val()
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#bloque").html(response);
            }
        });
    }

</script>