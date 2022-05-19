<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Informe de faltas por estudiante';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="reporte-flatas-estudiante-index" style="padding-left: 50px; padding-right: 50px">



    <h3><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <div class="row">
        <div class="col-lg-6 col-md-6">
            <?php
            $listData = ArrayHelper::map($modelAlumnos, 'id', 'student');

            echo '<label class="control-label">Curso:</label>';
            echo Select2::widget([
                'name' => 'curso',
                'value' => 0,
                'data' => $listData,
                'size' => Select2::SMALL,
                'options' => [
                    'placeholder' => 'Buscar estudiante',
                    'onchange' => 'buscaParcial(this,"' . Url::to(['parciales']) . '");',
                ],
                'pluginLoading' => false,
                'pluginOptions' => [
                    'allowClear' => false
                ],
            ]);
            ?>
        </div>

        
        <div class="col-lg-6 col-md-6" id="parciales"></div>
    </div>
    
    <hr>
    
    <div class="row" id="detalle"></div>
    
    
</div>

<script>
    function buscaParcial(obj, url)
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
                $("#parciales").html(response);

            }
        });
    }



    function mostrarDetalle(obj, url, alumnoId) {
         //var instituto = $(obj).val();
        var parametros = {
            "id"     : $(obj).val(),
            "alumno" : alumnoId
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#detalle").html(response);

            }
        });
    }
    
    
    function mostrarTodos(alumnoId){
        var url = '<?= Url::to(['detalle']) ?>';
        var parametros = {
            "id"     : "todos",
            "alumno" : alumnoId
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#detalle").html(response);

            }
        });
    }
    
    
    
    
    
</script>
