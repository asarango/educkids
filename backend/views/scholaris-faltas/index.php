<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisFaltasYAtrasosParcialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Faltas y Atrasos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-faltas-yatrasos-parcial-index">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="container">
    <div class="row">
        <div class="col-md-1">Curso:</div>
        <div class="col-md-2">
            <?php
            $listData = ArrayHelper::map($modelCursos, 'id', 'name');

//        echo '<label class="control-label">Curso:</label>';
            echo Select2::widget([
                'name' => 'curso',
                'value' => 0,
                'data' => $listData,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Seleccione curso',
                    'onchange' => 'CambiaParalelo(this,"' . Url::to(['reportes-parcial/paralelos']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);

//        echo '<label class="control-label">Paralelo:</label>';
            ?>
        </div>


        <div class="col-md-1">Paralelo:</div>
        <div class="col-md-2" id="paralelo"></div>
        
        <div class="col-md-1">Bloque:</div>
        <div class="col-md-2" id="bloque"></div>


    </div>
    </div>
    <hr>
    
    <div class="row" id="detalle">
        
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
//                detalle();
            }
        });
    }
    
    
    function mostrarDetalle(obj, paralelo){
//        console.log($(obj).val());
//        console.log(paralelo);
        
        var url = "<?= Url::to(["detalle"]) ?>";
        
        var parametros = {
            "id" : $(obj).val(),
            "paralelo" : paralelo
        };
        
        $.ajax({
            data: parametros,
            url : url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                $("#detalle").html(response);
            }
        });
        
        
    }
    
    function cambiaNovedad(obj,bloque,alumno, tipo){
//        console.log(obj);
//        console.log(bloque);
//        console.log(alumno);
//        console.log($(obj).val());
//        console.log(tipo);
        
        var url = "<?= Url::to(["asigna"]) ?>";
        
        var parametros = {
            'alumno' : alumno,
            'bloque' : bloque,
            'valor' : $(obj).val(),
            'tipo' : tipo
        };
        
        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                //$("#detalle").html(response);
            }
        });
        
        
    }

</script>