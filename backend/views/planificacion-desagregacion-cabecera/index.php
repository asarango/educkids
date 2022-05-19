<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Planificación';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
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
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'],
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
            <div class="row" style="margin-top: 25px;">

                <select name="niveles" onchange="showAsignaturas()" id="nivel" class="form-control select2 select2-hidden-accessible" style="width: 99%;" tabindex="-1" aria-hidden="true">
                    <option selected="selected" value="" >Escoja un curso...</option>
                    <?php
                            foreach($cursos as $nivel){
                                echo '<option value="'.$nivel->id.'">'.$nivel->name.'</option>';
                            }
                        ?>
                </select> 
            </div> <!-- /.form-group -->
    
<hr>
<!-- ORIGINAL
                <div class="form-group">
                    <select name="niveles" class="form-control" onchange="showAsignaturas()" id="nivel">
                        <option value="">Seleccione Curso...</option>
                        
                   <"ingresar etiqueta php">     php
                            foreach($cursos as $nivel){
                                echo '<option value="'.$nivel->id.'">'.$nivel->name.'</option>';
                            }
                        ?>

                    </select>
                </div> 
-->
               
                <div class="row">
                    <div class="table table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ASIGNATURA</th>
                                        <th>TOTAL CRITERIOS USADOS</th>
                                        <th>ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody id="table-body">

                                </tbody>
                            </table>
                    </div>
                </div>

            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>

<!-- SCRIPT PARA MOSTRAR MATERIAS POR CURSO ESCOGIDO -->
<script>
    function showAsignaturas(){
        var nivel   = $('#nivel').val();
        var url     = '<?= Url::to(['list-materias']) ?>';
        var params = {
            curso_id    :   nivel 
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function(response){
                $("#table-body").html(response);
                //console.log(response);
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