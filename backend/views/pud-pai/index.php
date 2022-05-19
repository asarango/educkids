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
<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>


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
                            Curso: <?= $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?> |
                            Materia: <?= $planUnidad->planCabecera->ismAreaMateria->materia->nombre ?>
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

                <!-- inicio de menu derecha -->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                |

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"> Generar Reporte PDF <i class="fas fa-file-pdf"></i></span>',
                            ['genera-pdf', 'planificacion_unidad_bloque_id' => $planUnidad->id],
                            ['class' => 'link', 'target' => '_blank']
                    );
                    ?>

                    |                

                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row my-text-medium" style="margin-top: 25px; margin-bottom: 5px;">

                <!-- comienza menu de pud-->
                <div class="col-lg-2 col-md-2" style="overflow-y: scroll; height: 500px; border-top: solid 1px #ccc;">
                    <?= $this->render('menu', [
                        'planUnidad' => $planUnidad
                    ]); ?>
                                        


                </div>
                <!-- termina menu de pud -->

                <!-- comienza detalle -->
                <div class="col-lg-10 col-md-10" id="div-detalle" style="border-top: solid 1px #ccc;">
                   

                    
                </div>
                <!-- termina detalle -->

            </div>
            <!-- fin cuerpo de card -->
    </div>
</div>

<!--<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>-->

<script>
    function ingresar_pregunta(obj, tipo, seccion){
        var pregunta = obj.value;        
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/ajax-crear-pregunta']) ?>';
        
        var params = {
            pregunta : pregunta,
            tipo: tipo,
            seccion: seccion,
            planificacion_bloque_unidad_id : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(){
                muestra_preguntas();
            }
        });

    }

    function muestra_preguntas(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/ajax-muestra-preguntas']) ?>';

        params = {
            planificacion_bloque_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-preguntas").html(response);
            }
        });
    }


    function showEdit(id, contenido){        
        $("#input-edit").val(contenido);
        $("#input-edit-id").val(id);        
    }


    function update(){
        //var contenido = obj.value;
        var id = $("#input-edit-id").val();
        var contenido = $("#input-edit").val();

        var url = '<?= Url::to(['helper-pud-pai/ajax-update']) ?>';

        params  = {
            contenido: contenido,
            id: id
        }

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                muestra_preguntas();
            }
        });

    }

    function delete_pud(){
        var id = $("#input-edit-id").val();
        var url = '<?= Url::to(['helper-pud-pai/ajax-delete']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
               muestra_preguntas();
            }
        });
    }


    function show_sumativas_evaluaciones(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-sumativas']) ?>';

        params = {
            planificacion_bloque_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-evaluacion-sumativa").html(response);
            }
        });
    }

    function show_sumativas_evaluaciones2(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-sumativas2']) ?>';

        params = {
            planificacion_bloque_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $("#div-evaluacion-sumativa2").html(response);
            }
        });
    }

    function update_sumativa1(id){
        var titulo      = $("#input-titulo-sumativa"+id).val();
        var contenido   = CKEDITOR.instances['editor-sumativa'+id].getData();
        var url = '<?= Url::to(['helper-pud-pai/update-sumativas1']) ?>';
        params = {
            id: id,
            titulo: titulo,
            contenido: contenido
        }

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
               show_sumativas_evaluaciones();
            }
        });

    }

    function update_sumativa2(id){
        var titulo      = 'no aplica';
        var contenido   = CKEDITOR.instances['editor-sumativa2'+id].getData();
        var url = '<?= Url::to(['helper-pud-pai/update-sumativas1']) ?>';
        params = {
            id: id,
            titulo: titulo,
            contenido: contenido
        }

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
               show_sumativas_evaluaciones();
               show_sumativas_evaluaciones2();
            }
        });

    }

    function show_ensenara(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-ensenara']) ?>';
        var params = {
            planUnidadId    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#div-como-ensenara').html(response);
            }
        });
    }


    function update_ensenara(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-ensenara']) ?>';
        var comunicacion = CKEDITOR.instances['editor-comunicacion'].getData();
        var sociales = CKEDITOR.instances['editor-sociales'].getData();
        var autogestion = CKEDITOR.instances['editor-autogestion'].getData();
        var investigacion = CKEDITOR.instances['editor-investigacion'].getData();
        var pensamiento = CKEDITOR.instances['editor-pensamiento'].getData();

        var params = {
            comunicacion    :   comunicacion,
            sociales        :   sociales,
            autogestion    :   autogestion,
            investigacion    :   investigacion,
            pensamiento    :   pensamiento,
            planUnidadId    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(){
                show_ensenara();
            }
        });

    }

    function show_recursos(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/muestra-recursos']) ?>';
        var params = {
            plan_unidad_id    : planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-recursos').html(response);
            }
        });
    }

    function update_recurso(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/update-recurso']) ?>';
        var bibliografico = CKEDITOR.instances['editor-bibliografico'].getData();
        var tecnologico = CKEDITOR.instances['editor-tecnologico'].getData();
        var otros = CKEDITOR.instances['editor-otros'].getData();

        var params = {
            plan_unidad_id: planUnidadId,
            bibliografico : bibliografico,
            tecnologico: tecnologico,
            otros : otros
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_recursos();
            }
        });
    }


    ////para reflexion
    function show_reflexion_seleccionados(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/show-reflexion-seleccionados']) ?>';

        var params = {
            plan_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-reflexion-seleccionadas').html(response);
            }
        });
    }


    function show_reflexion_disponibles(){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/show-reflexion-disponibles']) ?>';

        var params = {
            plan_unidad_id: planUnidadId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                $('#table-reflexion-disponibles').html(response);
            }
        });
    }


    function inster_reflexion(id, categoria){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/insert-reflexion']) ?>';

        var params = {
            plan_unidad_id : planUnidadId,
            id: id,
            url: url,
            tipo: categoria
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_reflexion_disponibles();
                show_reflexion_seleccionados();
            }
        });
    }

    function update_reflexion(id){
        var respuesta = $('#textarea-respuesta-'+id).val();
        var url = '<?= Url::to(['helper-pud-pai/update-reflexion']) ?>';
        
        params = {
            id: id,
            respuesta: respuesta
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function(){},
            success: function(response){
                show_reflexion_seleccionados();
            }

        });
    }

    function eliminar_reflexion(id){
        var url = '<?= Url::to(['helper-pud-pai/delete-reflexion']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_reflexion_seleccionados();
            }
        });
    }


    //// INICIO PARA PERFILES BI
        function show_perfiles_disponibles(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-perfiles-disponibles']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#table-perfiles-disponibles').html(response);
                }
            });

        }

        function insert_perfil(perfil, categoria){

            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/insert-perfil']) ?>';
            var params = {
                plan_unidad_id : planUnidadId,
                perfil : perfil,
                categoria : categoria
            };

            $.ajax({
                data: params,
                url: url,
                type: 'POST',
                beforeSend: function(){},
                success: function(response){
                    show_perfiles_disponibles();
                    show_perfiles_seleccionados();
                }
            });


        }

        function show_perfiles_seleccionados(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-perfiles-seleccionados']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#table-perfiles-seleccionadas').html(response);
                }
            });
        }


        function eliminar_perfil(id){
        var url = '<?= Url::to(['helper-pud-pai/delete-reflexion']) ?>';
        params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                show_perfiles_seleccionados();
            }
        });
    }
    //// FIN PARA PERFILES BI

    ////INICIO PARA SERVICIOS DE ACCION
    function show_servicios_accion_seleccionadas(){
        var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-servicio-accion-seleccionadas']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#body-como-accion').html(response);
                }
            });
    }
    
    function show_servicios_accion_disponibles(){
            var planUnidadId = '<?= $planUnidad->id ?>';
            var url = '<?= Url::to(['helper-pud-pai/show-servicio-accion-disponibles']) ?>';

            var params = {
                plan_unidad_id : planUnidadId
            };

            $.ajax({
                data:   params,
                url:    url,
                type:   'GET',
                beforeSend: function(){},
                success: function(response){
                    $('#acciones-disponibles').html(response);
                }
            });
    }
    
    function insert_accion(opcionId){
        var planUnidadId = '<?= $planUnidad->id ?>';
        var url = '<?= Url::to(['helper-pud-pai/inserta-accion']) ?>'; 
        
        var params = {
            opcion_id       : opcionId,
            plan_unidad_id  : planUnidadId
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                        show_servicios_accion_disponibles();
                        show_servicios_accion_seleccionadas();
                    }
        });
    }
    
    function inserta_situacion(planUnidadId, categoria, opcion){
        var url = '<?= Url::to(['helper-pud-pai/inserta-situacion']) ?>';
        
        var params = {
            plan_unidad_id : planUnidadId,
            opcion : opcion,
            categoria : categoria
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                        show_servicios_accion_seleccionadas();
                    }
        });
    }
    
    function elimina_situacion(id){
        var url = '<?= Url::to(['helper-pud-pai/elimina-situacion']) ?>';
        
        var params = {
            id: id
        };
        
        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                show_servicios_accion_seleccionadas();
            }
        });
    }
        
        
    ////FIN PARA SERVICIOS DE ACCION

    
</script>