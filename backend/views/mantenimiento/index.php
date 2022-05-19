<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mantenimiento';
$this->params['breadcrumbs'][] = $this->title;



?>
<div class="mantenimiento-index" style="padding-left: 40px; padding-right: 40px">

    
    <strong><h3>MANTENIMIENTO Y JOBS</h3></strong>
    
    <div class="table table-responsive">
        <table class="table table-striped table-hover">
            <tr>
                <td align="center"><strong>Proceso</strong></td>
                <td align="center"><strong>Tipo</strong></td>
                <td align="center"><strong>Descripción</strong></td>
                <td align="center"><strong>Acción</strong></td>
            </tr>
            <tr>
                <td><i class="fa fa-rocket fa-fw"></i> Liberar tablas de libretas</td>
                <td>JOB</td>
                <td>Realiza la limpieza de las tablas utilizadas para generar libretas y 
                    sabanas, este procesa ayuda a que la base de datos no se bloque 
                    por la cantidad de transacciones al servidor de base de datos</td>
                <td>
                    <a href="#" onclick="ejecutar('<?= \yii\helpers\Url::to(['jobs/libera-tabla-notas-libreta']) ?>')">Ejecutar</a>
                    
                </td>
            </tr>
        </table>
        
        
    </div>

</div>


<script>
    function ejecutar(url){
        
        $.ajax({
            //data:  parametros,
            url:   url,
            type:  'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                alert('Proceso ejecutado correctamente!!!');
            }
        });
    }
</script>


