<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mapa de Enfoques';
$this->params['breadcrumbs'][] = $this->title;

//  echo '<pre>';
//   print_r($habilidades);
// echo '<hr>';
// echo '***********************************************************************************************************************************************';
//  echo '<pre>';
//  print_r($materia);
?>

<!-- Jquery AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>



<div class="materias-pai-ajax-mapa-enfoque">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/libros.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <h6>
                        
                        <?= 'Sección: PAI  |  Materia: '.$materia->name ?>
                    </h6>
                    <small>
                        (Escoja los enfoques que desee habilitar en su planificación vertical - horizontal)
                    </small>
                    <br>
                    
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
                        '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Materias PAI</span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>
                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->

                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin-top: 25px; margin-bottom:10px">

            <!-- Habilidades -->
                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12" style="">
                        <h6 style="text-align:center">Habilidades</h6>
                    <?php
                        foreach ($habilidades as $habilidad) {
                    ?>
                    <div class="d-grid gap-2" style="">
                        <button class="btn btn-small my-text-medium" type="button" 
                                style="background-color:#0a1f8f;color:white; border-radius:0px" 
                                onclick="showHabilidades('<?=$habilidad['orden_titulo2'] ?>')">
                            <?= $habilidad['orden_titulo2'].'.-'.$habilidad['es_titulo2']  ?>
                        </button>
                    </div>
                    <?php
                        }
                    ?>
                </div>

                <!-- Muestra exploración y cursos PAI -->
                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"> 
                    <div id="view-habilidades" >
                        <h6 >Escoja una habilidad</h6>    
                    </div>
                </div>
                  
            </div>
            <!-- fin cuerpo de card -->



        </div>
    </div>

</div>

<script>
    

    function showHabilidades(ordenTitulo){
        // alert(ordenTitulo);
        var url= '<?=  Url::to(['ajax-mapa-enfoque']) ?>';
        var materiaId = <?= $materia->id ?>;
        var params = {
            materia_id: materiaId,
            habilidad_orden: ordenTitulo
        };

        $.ajax({
            data:params,
            url:url,
            type:'GET',
            beforeSend: function(){},
            success: function(response){
                $("#view-habilidades").html(response);
                // console.log(response);
            }
        });

        // alert(materiaId);
    }

    // Esta función
    function cambiaEstado(id,ordenTitulo2){
        // alert(ordenTitulo2);

         var url= '<?=  Url::to(['activa-inactiva']) ?>';
         var params = {
             id: id
         };

         $.ajax({
             data:params,
             url:url,
             type:'GET',
             beforeSend: function(){},
             success: function(){
                showHabilidades(ordenTitulo2);
             }
         });


    }

</script>

