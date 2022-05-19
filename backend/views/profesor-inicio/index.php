<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis asignaturas';
//$this->params['breadcrumbs'][] = $this->title;
?>


<div class="portal-inicio-index animate__animated animate__fadeIn">

    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/aula.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11"><h4><?= Html::encode($this->title) ?></h4></div>
            </div>
            <hr>

            <p>
                |                                
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="far fa-file"></i> Inicio</span>', ['site/index'], ['class' => 'link']); ?>
                |                                
                <?= Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fas fa-clock"></i> Leccionario</span>', ['scholaris-asistencia-profesor/index'], ['class' => 'link']); ?>                
                |
            </p>
            
            <div class="row p-3">
                
                <div class="card">
                    <div class="row">
                        <div class="col-lg-3">
                            <?= $this->render('menu', [
                                'clases' => $clases
                            ]) ?>
                        </div>
                        
                        <div class="col-lg-9 col-md-9">
                            <div class="card" id="div-detalle" style="display: none; margin-top: 5px;"></div>

                            <div class="card" id="div-info-bloque" style="display: none; margin-top: 5px;"></div>

                            <div class="card p-3" id="div-actividades" style="display: none; margin-top: 5px;"></div>                            
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function muestra_detalle(claseId, accion){
        $("#div-detalle").show();
        
        var url = '<?= Url::to(['docente-clases/detalle-clase']) ?>';
        var params = {
            clase_id : claseId,
            accion: accion
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function (){},
            success: function (response) {                                
                $("#div-detalle").html(response);
                $("#div-info-bloque").hide();
                $("#div-actividades").hide();
            }
        });
        
    }
    
    
    function muestra_informacion_bloque(claseId, bloqueId, accion){
        
        var url = '<?= Url::to(['docente-clases/detalle-bloque']) ?>';       
        
        var params = {
            clase_id: claseId,
            accion: accion,
            bloque_id: bloqueId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function (response) {
                if(accion == 'informacion'){
                    $("#div-info-bloque").show();
                    $("#div-actividades").hide();                    
                    $('#div-info-bloque').html(response);
                }else if(accion == 'calificadas'){
                    $("#div-actividades").show();                    
                    $("#div-actividades").html(response);
                }else if(accion == 'nocalificadas'){
                    $("#div-actividades").show();
                    $("#div-actividades").html(response);
                }
            }
        });
        
        
    }
</script>