<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Creando Notificación';
$this->params['breadcrumbs'][] = $this->title;

// echo '<pre>';
// print_r($detalle);
//die();
?>

<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/basic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="planificacion-aprobacion-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10 col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>

                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ab0a3d"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="fa fa-briefcase" aria-hidden="true"></i> Volver a notificaciones</span>',
                            ['index'],
                            ['class' => 'link']
                    );
                    ?>
                    
                    |
                    

                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                
                </div>
                <!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin:30px">                    

                    <?php $form = ActiveForm::begin(); ?>

                        <div class="">                                
                                <div class="card">
                                    <div class="row">                                        
                                        <div class="col-lg-6 col-md-6 p-3">
                                            <div class="row">
                                                <?php echo $this->render('modalPocos'); ?>
                                            </div>

                                            <div class="form-group">
                                               <select name="personas-seleccionadas[]" id="div-seleccion" multiple class="form-control"></select>
                                            </div>                                                                                            
                                        </div>

                                        <div class="col-lg-6 col-md-6 p-3">
                                            <div class="row">
                                                <?php echo $this->render('modalGrupos'); ?>
                                            </div>

                                            <div class="form-group">
                                               <select name="aquien-seleccionadas[]" id="div-seleccion-quien" multiple class="form-control"></select>
                                            </div>

                                            <div class="form-group">
                                               <select name="grupo-seleccionadas[]" id="div-seleccion-grupos" multiple class="form-control"></select>
                                            </div>                                                                                            
                                        </div>
                                    </div>                                                                        
                                </div>
                            
                        </div>
                        <br>

                        <?= $form->field($model, 'asunto')->textInput(['maxlength' => true])->label('ASUNTO:'); ?>

                        <br>
                        <div class="form-group">
                            <label>NOTIFICACIÓN: </label>
                            <textarea name="texto" id="texto"></textarea>
                        </div>


                        <div class="form-group" style="margin-top: 10px">
                            <?= Html::submitButton('Grabar', ['class' => 'btn btn-outline-success']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
               

            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>





<script>
    CKEDITOR.replace('texto', {
        customConfig: '/ckeditor_settings/config.js'
    });
</script>

<script>
    function seleccionar_persona(obj){
        var nombres = obj.value;
        var url = '<?= Url::to(['helper-ajax-cursos/persona']) ?>';

        var params = {
            nombres: nombres
        }; 

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                $('#div-persona').html(response);
            }
        });

    }

    function seleccionar_grupos(obj){
        var grupo = obj.value;
        var url = '<?= Url::to(['helper-ajax-cursos/paralelos']) ?>';

        var params = {
            paralelo: grupo
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                $('#div-grupos').html(response);
            }
        });
    }

    var arrayPersona = [];
    function elije_persona(usuario){
        arrayPersona.push('<option value="'+usuario+'" selected>'+usuario+'</option>');
        $("#div-seleccion").html(arrayPersona);
        
    }

    var arrayQuien = [];
    function elije_quien(obj){
        var quien = obj.value;
        arrayQuien.push('<option value="'+quien+'" selected>'+quien+'</option>');
        $('#div-seleccion-quien').html(arrayQuien);
    }

    var arrayGrupo = [];
    function elije_grupo(paralelo){
        arrayGrupo.push('<option value="'+paralelo+'" selected>'+paralelo+'</option>');
        $('#div-seleccion-grupos').html(arrayGrupo);
    }
</script>

<script>
    function cursos(){        
        var url = '<?= Url::to(['helper-ajax-cursos/cursos']) ?>';

        $.ajax({
            url: url,
            type: 'POST',
            beforeSend: function(){},
            success: function(response){
                $('#div-cursos').html(response);
                
            }
        });
    }
</script>

<!-- SCRIPT PARA SELECT2 -->
<script>
    buscador();
    function buscador(){
        $('.select2').select2({
    closeOnSelect: true
    });
    }

</script>