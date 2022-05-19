<!--pasa variables objetos:
$planUnidad
$pudPep;-->
<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use backend\controllers\PudPepController;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'PUD - ' . $planUnidad->curriculoBloque->last_name . ' - ' . $planUnidad->unit_title;
$this->params['breadcrumbs'][] = $this->title;
//    echo '<pre>';
//    print_r($planUnidad);
//    print_r($seccion);
//    die();
?>
<!--Scripts para que funcionen AJAX'S-->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->


<div class="pud-pep-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        <h6>
                            (
                            Curso: <?= $planUnidad->planCabecera->opCourseTemplate->name ?> |
                            Materia: <?= $planUnidad->planCabecera->scholarisMateria->name ?>
                            )
                        </h6>
                    </small>


                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i>Temas</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $planUnidad->plan_cabecera_id],
                            ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row my-text-medium" style="margin-top: 25px; margin-bottom: 5px">
                
                
                <div class="col-lg-2 col-md-2">
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Datos Informativos</div>
                            </div>
                            <a onclick="ajaxPud('datos_informativos')">
                                <span type="button" class="badge bg-primary rounded-pill">
                                    <i class="fas fa-arrow-right" style="font-size:13px" ></i>
                                </span>
                            </a>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Criterios de Evaluación</div>
                            </div>
                            <a onclick="ajaxPud('criterios_evaluacion')">
                                <span type="button" class="badge bg-primary rounded-pill">
                                <i class="fas fa-arrow-right" style="font-size:13px" ></i>
                            </span>                        
                            </a>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Indicadores</div>
                            </div>
                            <a onclick="ajaxPud('indicadores')">
                                <span type="button" class="badge bg-primary rounded-pill">
                                <i class="fas fa-arrow-right" style="font-size:13px" ></i>
                                </span>                        
                            </a>                            
                        </li>
                    </ol>

                    <div style="text-align: center; margin-top: 5px">
                        <?=
                        Html::a(
                                'Generar Reporte PDF <i class="fas fa-file-pdf"></i>',
                                ['genera-pdf', 'planificacion_unidad_bloque_id' => $planUnidad->id],
                                ['class' => 'link my-text-medium']
                        );
                        ?>
                    </div>

                </div>
                
                
                <div class="col-lg-10 col-md-10 my-text-medium" id="div-datos">
                    <h6 style="text-align: center">
                        Escoja una pestaña en el ícono "  
                        <span class="badge bg-primary rounded-pill">
                            <i class="fas fa-arrow-right" style="font-size:13px" ></i>
                        </span>  
                        "
                    </h6>
                </div>
                
            </div>

        </div>
        <!-- fin cuerpo de card -->
    </div>
</div>

<!--<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>-->


<script>
function ajaxPud(contenedor) {
//        alert(contenedor);
//        alert(id);
        var url = '<?= Url::to(['ajax-pud']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            contenedor: contenedor,
            plan_unidad_id: id
        };
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                if(contenedor == 'datos_informativos'){
                    $("#div-datos").html(response);
                    showObjetivos();
                }else if(contenedor == 'criterios_evaluacion'){
                    $("#div-datos").html(response);
                    showCe();
                }else if(contenedor == 'indicadores'){
                    $("#div-datos").html(response);
                    showIndicadores();
                }
                
            }
        });
    }
    
    function ajaxObjetivosDisponibles() {

        var url = '<?= Url::to(['helper/ajax-objetivos-disponibles']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            planificacion_bloque_unidad_id: id
        };

        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $('#div-objetivos-disponibles').html(response);
            }

        });
    }
    
    function ajaxInsertarCriterio(tipo, codigo, contenido) {
        var url = '<?= Url::to(['ajax-insertar-criterio']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            planificacion_bloque_unidad_id: id,
            tipo: tipo,
            codigo: codigo,
            contenido: contenido
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                if (tipo == 'objetivos_generales') {
                    ajaxObjetivosDisponibles();
                    showObjetivos();
                }else if(tipo == 'criterio'){
                    ajaxCeDisponibles();
                    showCe();
                }else if(tipo == 'indicador'){
                    ajaxIndicadoresDisponibles();
                    showIndicadores();
                }
            }

        });
    }
    
    function ajaxInsertarContenido(tipo, codigo, contenido, perteneceId) {

        var url = '<?= Url::to(['ajax-insertar-contenido']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var seccion = '<?=$seccion?>';
        var params = {
            planificacion_bloque_unidad_id: id,
            tipo: tipo,
            codigo: codigo,
            contenido: contenido,
            pertenece_indicador_id: perteneceId
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                showDetallesDisponibles(seccion,contenido,id,perteneceId);
                ajaxDetalle(perteneceId);
            }

        });
    }
    
    function showObjetivos() {
        var url = '<?= Url::to(['helper/ajax-objetivos-seleccionados']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            planificacion_bloque_unidad_id: id,
        };
        
        $.ajax({
            url:url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-objetivos-seleccionados').html(response);
            }
        });
        
    }
    
    function ajaxDeleteOption(id,pestana){
        var url = '<?=Url::to(['ajax-delete-option']) ?>';
        var params = {
          id: id,
          pestana: pestana
        };
        
        $.ajax({
           url:url,
           data: params,
           type: 'GET',
           beforeSend:function(){},
           success: function(){
               if(pestana == 'objetivos_generales'){
                   showObjetivos();
               }else if(pestana == 'criterios_evaluacion'){
                   showCe();
               }else if(pestana == 'indicadores'){
                   showIndicadores();
               }
           }
        });
    }
    
//    Funcion para eliminar detalles de los indicadores ADA-Recursos-Tecnicas- y Destrezas
    function ajaxDeleteContenido(id,indicadorId){
       var url = '<?=Url::to(['ajax-delete-option']) ?>';
        var params = {
          id: id
        };
        
        $.ajax({
           url:url,
           data: params,
           type: 'GET',
           beforeSend:function(){},
           success: function(){
               ajaxDetalle(indicadorId);
           }
        });
    }
    
    function ajaxCeDisponibles(){
        var url = '<?=Url::to(['helper/ajax-ce-disponibles']) ?>';
        var id = '<?= $planUnidad->id?>';
        var params = {
            planificacion_bloque_unidad_id : id
        };
        
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-ce-disponibles').html(response);
            }
        });
            
    }
    function showCe() {
        var url = '<?= Url::to(['helper/ajax-ce-seleccionados']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            planificacion_bloque_unidad_id: id,
        };
        
        $.ajax({
            url:url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-ce-seleccionados').html(response);
            }
        });
        
    }
    
    function ajaxIndicadoresDisponibles(){
       var url = '<?=Url::to(['helper/ajax-indicadores-disponibles']) ?>';
        var id = '<?= $planUnidad->id?>';
        var params = {
            planificacion_bloque_unidad_id : id
        };
        
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-indicadores-disponibles').html(response);
            }
        }); 
    }
    
    function showIndicadores() {
        var url = '<?= Url::to(['helper/ajax-indicadores-seleccionados']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var seccion = '<?= $seccion ?>';
        var params = {
            planificacion_bloque_unidad_id: id,
            seccion: seccion,
        };
        
        $.ajax({
            url:url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-indicadores-seleccionados').html(response);
            }
        });
        
    }
    
//Ajax que muestra detalles en la modal de indicador
    function ajaxDetalle(indicadorId){
//        alert(indicadorId);
        var url = '<?=Url::to(['helper/ajax-detalle']) ?>';
        var id = '<?=$planUnidad->id ?>';
        var seccion = '<?=$seccion ?>';
        var params = {
            indicador_id : indicadorId,
            seccion : seccion,
            planificacion_bloque_unidad_id: id
        };
        
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-detalle-indicador'+indicadorId).html(response);
                $('#div-detalle-indicador-p'+indicadorId).html(response);
            }
        });
    }
    
    function showDetallesDisponibles(seccion,word,bloqueId,indicadorId){
        
        if(word != null && word != '' && typeof word === 'object'){
            var palabra = word.value;
        }else{
            var palabra = '';
        }
        
//        console.log(palabra);
        var url= '<?=Url::to(['helper/ajax-show-detalles-disponibles']) ?>';
        var params = {
            seccion: seccion,
            word: palabra,
            planificacion_bloque_unidad_id: bloqueId,
            indicador_id : indicadorId
        };
        
//        console.log(params);
        
        $.ajax({
            url:url,
            data:params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                
                    $('#div-ada-disponibles'+indicadorId).html(response);
                    ajaxDetalle(indicadorId);
                
                
            }
        });
        
    }
    
    function showDetallesDisponiblesAux(seccion,bloqueId,indicadorId){
        
//        console.log(palabra);
        var url= '<?=Url::to(['helper/ajax-show-detalles-disponibles']) ?>';
        var params = {
            seccion: seccion,
            word: '',
            planificacion_bloque_unidad_id: bloqueId,
            indicador_id : indicadorId
        };
        
        $.ajax({
            url:url,
            data:params,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                
                    $('#div-ada-disponibles').html(response);
                    ajaxDetalle(indicadorId);
                
                
            }
        });
        
    }
    
//    Función para mostrar destrezas disponibles de CADA INDICADOR
    function showDestrezasDisponibles(planUnidadId,indicadorId,indicadorCode){
//        alert(planUnidadId);
        
        var url= '<?=Url::to(['helper/ajax-show-destrezas-disponibles']) ?>';
        var params = {
            planificacion_bloque_unidad_id: planUnidadId,
            indicador_id: indicadorId,
            codigo: indicadorCode
        };
//        
        $.ajax({
            url: url,
            data: params,
            type: 'GET',
            beforeSend:function(){},
            success:function(response){
                $('#div-destrezas-disponibles'+indicadorId).html(response);
                ajaxDetalle(indicadorId);
            }
        });
        
    }
    
    function ajaxInsertarDestreza(tipo, codigo, contenido, perteneceId,indicadorCode) {

        var url = '<?= Url::to(['ajax-insertar-destreza']) ?>';
        var id = '<?= $planUnidad->id ?>';
        var params = {
            planificacion_bloque_unidad_id: id,
            tipo: tipo,
            codigo: codigo,
            contenido: contenido,
            pertenece_indicador_id: perteneceId,
        };

        $.ajax({
            url: url,
            data: params,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                showDestrezasDisponibles(id,perteneceId,indicadorCode);
                ajaxDetalle(perteneceId);
            }

        });
    }
    
    function ajaxDeleteIndicador(indicadorId){
        var url = '<?=Url::to(['ajax-delete-indicador'])  ?>';
        var params = {
            indicador_id: indicadorId
        };
        
        result = window.confirm('Usted eliminará toda la configuración relacionada a este indicador.¿Desea Eliminar?');
        
        if(result == true){
            $.ajax({
                url: url,
                data: params,
                type: 'POST',
                beforeSend: function(){},
                success: function(){
                    ajaxPud('indicadores');
                }
            });
        }
        
    }
    
 
    
    
</script>