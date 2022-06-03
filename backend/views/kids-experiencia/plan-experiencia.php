<?php

use yii\helpers\Html;
use yii\helpers\Url;

//echo '<pre>';
// print_r($micro);
// print_r($micro);
// print_r($plan); //dentro de plan tengo arrat de destrezas disponibles y seleccionadas
// die();
?>
<!--INCIA CRITERIOS-->
<div class="plan-experiencia my-text-medium">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <strong>CRITERIOS DE EVALUACION</strong>
            <div id="div-criterios" style="text-align: justify"></div>
        </div>        
    </div>
    <!--FIN CRITERIOS-->
    <hr>

    <!--INICIA DESTREZAS-->
    <div class="row" style="margin-top: 10px">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <strong>
                <!-- Button trigger modal -->
                <a type="button" title="Agregar Destreza" data-bs-toggle="modal" data-bs-target="#exampleModal"
                   onclick="muestra_destrezas_disponibles('plan')">
                    <i class="fas fa-plus-square" style="color:#ff9e18"></i>
                </a> DESTREZAS</strong>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="table table-responsive">
                <table class="table table-striped table-condensed table-hover">
                    <thead style="background-color:#0a1f8f;color:white" class="text-center">
                        <tr>
                            <th>EJES</th>
                            <th>AMBITOS</th>
                            <th>DESTREZAS</th>
                            <th>ACTIVIDADES</th>
                            <th>RECURSOS</th>
                            <th>INDICADORES</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody id="body-destreza-seleccionada"></tbody> <!-- Muestra contenido en ajax -->
                </table>
            </div>
        </div>
    </div>
    <!--FIN DESTREZAS-->


    <!--Modal Agregar destreza-->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Destreza</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table table-responsive">
                        <table class="table table-striped table-condensed table-hover" id="table-destrezas-disponibles">
                            <thead>
                                <tr bgcolor="ff9e18" class="text-center">
                                    <th>CODIGO</th>
                                    <th>DESCRIPCION</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody id="body-destreza-disponible"></tbody> <!-- Muestra contenido en ajax -->
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
$(function () {
    muestra_criterio();
    destreza_seleccionada();
});

//función para mostrar criterios de evaluacion   
function muestra_criterio() {

    var url = "<?= Url::to(['kids-experiencia/plan-micro']) ?>";
    var microId = "<?= $micro['id'] ?>";
    var bandera = 'criterios';
    var params = {
        micro_id: microId,
        bandera: bandera
    };

    $.ajax({
        url: url,
        data: params,
        type: 'GET',
        beforeSend: function () {},
        success: function (response) {
            $("#div-criterios").html(response);
        }
    });
}

//Función que muestra destrezas seleccionadas
function destreza_seleccionada() {
    // alert(bandera);
    var url = "<?= Url::to(['kids-experiencia/plan-micro']) ?>";
    var microId = "<?= $micro['id'] ?>";
    var bandera = "seleccionadas";
    params = {
        bandera: bandera,
        micro_id: microId
    };

    $.ajax({
        url: url,
        data: params,
        type: 'GET',
        beforeSend: function () {},
        success: function (response) {
            $("#body-destreza-seleccionada").html(response);
        }
    });

}

//Funcion para mostar destrezas disponibles
function muestra_destrezas_disponibles(bandera) {
//    console.log(bandera);
    var url = "<?= Url::to(['kids-experiencia/micro']) ?>";
    var id = "<?= $micro['id'] ?>";
    var params = {
        bandera: bandera,
        id: id
    };
    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        beforeSend: function () {},
        success: function (resp) {
            $('#body-destreza-disponible').html(resp);
        }
    });
}

//funcion para insertar destreza disponible
function inserta_destreza(bandera, id) {
    // alert(bandera);
    var url = "<?= Url::to(['kids-experiencia/micro']) ?>";
    var microId = "<?= $micro['id'] ?>";
    params = {
        bandera: bandera,
        destreza_id: id,
        micro_id: microId
    };

    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        beforeSend: function () {},
        success: function (response) {
            if(response == true){
//                alert('si inserto');
                muestra_destrezas_disponibles('plan');
                destreza_seleccionada();
                //Scroll automatico
                $("html, body").animate({
                    scrollTop: "400px"
                });
            }else{
                console.log('¡No se ingresó destreza!');
            }
            
        }
    });

}

function update_destreza(id){
//    alert(id);
    var url = "<?= Url::to(['kids-experiencia/micro']) ?>";
    var params = {
        id : id
    };
    
    $.ajax({
        url: url,
        data: params,
        type: 'POST',
        beforeSend: function () {},
        success: function () {}        
    });
    
}

// Data table destrezas disponibles (modal)
$("#table-destrezas-disponibles").DataTable({
    language: {
        "decimal": "",
        "emptyTable": "No hay información",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
        "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Entradas",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        }
    },

});
</script>