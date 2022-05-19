<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modificación de notas';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<!--<div class="rportes-parcial-index">-->

<h1><?= Html::encode($this->title) ?></h1>


<div class="container">


    <?php echo Html::beginForm(['index2', 'post']); ?>

    <?php
    $listData = ArrayHelper::map($modelProfesor, 'id', 'last_name');

    echo '<label class="control-label">Profesor:</label>';
    echo Select2::widget([
        'name' => 'profesor',
        'value' => 0,
        'data' => $listData,
        'size' => Select2::SMALL,
        'id' => 'profesor',
        'options' => [
            'placeholder' => 'Seleccione Profesor',
            'onchange' => 'mostrarCursos(this,"' . Url::to(['/cajas-select/cursosprofesor']) . '");',
        ],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);
    ?>

    <label class="control-label">Curso:</label>
    <div id="curso"></div>


    <label class="control-label">Paralelo:</label>
    <div id="paralelo"></div>
    
    <label class="control-label">Clases:</label>
    <div id="clases"></div> <!--clases-->


    <label class="control-label">Estudiantes:</label>
    <div id="alumno"></div>

    <label class="control-label">Bloque:</label>
    <div id="bloque"></div>
    
    <label class="control-label">Actividad:</label>
    <div id="actividad"></div>


 
    <br>
    <?php
    echo Html::submitButton(
            'Aceptar', ['class' => 'btn btn-primary']
    );
    ?>

    <?php echo Html::endForm(); ?>
</div>


<script>

       

    function mostrarCursos(obj, url) {        
                
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
                $("#curso").html(response);

            }
        });
    }


    function mostrarParalelo(obj, url)
    {

        var profesor = $("#profesor").val();

        var parametros = {
            "curso": $(obj).val(),
            "profesor": profesor
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
    
    
    function mostrarClases(obj, url)
    {

        var profesor = $("#profesor").val();

        var parametros = {
            "paralelo": $(obj).val(),
            "profesor": profesor
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#clases").html(response);

            }
        });
    }
    
    
//    
    function mostrarAlumnos(obj, url)
    {
        //var instituto = $(obj).val();
        var parametros = {
            "clase": $(obj).val()
        };
        

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#alumno").html(response);

            }
        });
    }


    function mostrarBloque(obj, url) {
        var clase = $("#claseId").val();                        
        
        var parametros = {
            "id": $(obj).val(),
            "clase": clase
        };
        
        //console.log(paralelo);

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
    
    function mostrarActividades(obj, url) {
        var clase = $("#claseId").val();
        var alumno = $("#alumnoId").val();                  
        
        var parametros = {
            "bloque": $(obj).val(),
            "clase": clase,
            "alumno": alumno,
        };
        
        //console.log(paralelo);

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#actividad").html(response);
            }
        });
    }

</script>