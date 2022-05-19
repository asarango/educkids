<?php

use backend\models\PlanificacionDesagregacionCriteriosEvaluacion;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '5.- Planificación Curricular Anual';
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($cabecera);
//die();
?>
<!-- Jquery AJAX -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>



<div class="pca-index1">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12 col-sm-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (<?= $cabecera->ismAreaMateria->materia->nombre . ' - ' . $cabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name ?>)
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
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Detalle de temas</span>',
                            ['planificacion-bloques-unidad/index1', 'id' => $cabecera->id],
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
            <div class="row" style="margin-top: 25px; margin-bottom: 5px;padding-left: 15px;padding-right: 15px;">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 my-text-medium" style="padding:0.99px;" >
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Datos Informativos</div>
                            </div>
<!--                            <span class="badge bg-primary rounded-pill" onclick="showForm('datos_informativos')"  >
                                <i class="fas fa-arrow-circle-right" style="color: white" ></i>
                            </span>-->
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Tiempo</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('tiempo')">
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Objetivos Generales</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('objetivos_generales')" >
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>                        
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Ejes Transversales</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('ejes_transversales')">
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Unidades Microcurriculares</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('unidades_microcurriculares')">
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Observaciones</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('observaciones')">
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">Bibliografía</div>
                            </div>
                            <span type="button" class="badge bg-primary rounded-pill" onclick="showForm('bibliografia')">
                                <i class="fas fa-arrow-circle-right" style="color: white; font-size: 13px" ></i>
                            </span>
                        </li>
                    </ol>
                    <hr>
                    <div class="col-lg-12 col-md-12" style="text-align:center">
                        <?= Html::a(
                            '<i class="fas fa-file-pdf"> Generar PDF</i>',
                            ['pca-materia', 'cabecera_id' => $cabecera->id],
                            ['class' => 'link', 'style'=> 'font-size:15px']
                    );  ?>
                    </div>
                    
                </div>

                <div class="col-lg-4 col-md-4 card shadow card-400" id="div-formulario">
                    <div class="text-center">
                        <strong>ESCOJA UNA OPCIÓN EN EL ÍCONO "<i class="fas fa-arrow-circle-right" style="color:blue; font-size: 13px" ></i>"</strong>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6" id="div-reporte" style="padding-left: 20px" id="global" >

                </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>


<script>

    showReporte();

    function showReporte() {
        var cabeceraId = <?= $cabecera->id ?>;
        var url = '<?= Url::to(['ajax-pca-reporte']) ?>';
        var params = {
            cabecera_id: cabeceraId
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#div-reporte").html(response);
            }
        });

    }


//Manda Formularios
    function showForm(menu) {
//        alert(menu);
        var cabeceraId = <?= $cabecera->id ?>;
        var url = '<?= Url::to(['ajax-pca-formulario']) ?>';
        var params = {
            cabecera_id: cabeceraId,
            menu: menu
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#div-formulario").html(response);
            }
        });

    }

//Funcion de formulario para TIEMPO
    function ajaxSaveData(codigo, obj, tipo) {
//        alert(codigo);
//        alert(obj.value);
        var url = '<?= Url::to(['save-form']) ?>';
        var cabeceraId = '<?= $cabecera->id ?>';
        var contenido = obj.value;
        var params = {
            cabecera_id: cabeceraId,
            tipo: tipo,
            contenido: contenido,
            codigo: codigo
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                showReporte();
            }
        });
    }

//Función de formulario para insertar objetivos generales
    function ajaxSaveContent(contenido, codigo, tipo) {
        // alert(tipo);
        // alert(contenido);
        // alert(codigo);
        var url = '<?= Url::to(['save-form']) ?>';
        var cabeceraId = '<?= $cabecera->id ?>';
        var params = {
            cabecera_id: cabeceraId,
            tipo: tipo,
            contenido: contenido,
            codigo: codigo
        };

        $.ajax({
            data: params,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function () {
                showReporte();
            }
        });

    }

//    Funcion para eliminar contenido de PCA
    function ajaxDeletePca(id) {
        var url = '<?= Url::to(['delete-pca']) ?>';
        var params = {
            id: id
        };

        $.ajax({
            data: params,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function () {
                showReporte();
                alert ('¡Se ha eliminado objeto seleccionado!');
            }
        });
    }


</script>

