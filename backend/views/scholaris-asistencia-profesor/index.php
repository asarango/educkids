<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mis actividades de hoy';
?>

<div class="scholaris-asistencia-profesor-index" style="padding-left: 40px; padding-right: 40px">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10col-md-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1"><h4><img src="ISM/main/images/submenu/actividad-fisica.png" width="64px" style="" class="img-thumbnail"></h4></div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div>
            <hr>

            <p>
                |                                
                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                        ['site/index'], ['class' => 'link']);
                ?>                
                |    

                <?=
                Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5">
                                    <i class="fa fa-briefcase" aria-hidden="true"></i> Mis clases KIDS
                                </span>',
                        ['kids-menu/index1'], ['class' => 'link']);
                ?>

<!--                <?ph

if($tienePrescolar){
    echo Html::a('<span class="badge rounded-pill" style="background-color: #9e28b5">
                    <i class="fa fa-briefcase" aria-hidden="true"></i> Mis clases KIDS
                </span>',
                ['kids-menu/index1'], ['class' => 'link']);

    echo ' | ';
}
    
if($tieneOtras){
    echo Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18">
            <i class="fa fa-briefcase" aria-hidden="true"></i> Mis clases
        </span>',
        ['profesor-inicio/index'], ['class' => 'link']);

        echo ' | ';
}                    

?>                                              -->


            </p>

            <!--comienza cuerpo de documento-->

            <div class="table table-responsive">
                <table class="table table-striped table-condensed table-hover" id="table-asistencias">
                    <thead>
                        <tr bgcolor="ff9e18" class="text-center">
                            <th>CLASE</th>
                            <th>ASIGNATURA</th>
                            <th>CURSO</th>
                            <th>PARALELO</th>
                            <th>HORA</th>
                            <th>DESDE</th>
                            <th>HASTA</th>
                            <th>INGRESA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//                                        print_r($model);
//                                        die();
                        foreach ($model as $data) {
                            echo '<tr>';
                            echo '<td class="text-center">' . $data['clase_id'] . '</td>';
                            echo '<td>' . $data['materia'] . '</td>';
                            echo '<td>' . $data['curso'] . '</td>';
                            echo '<td class="text-center">' . $data['paralelo'] . '</td>';
                            echo '<td>' . $data['hora'] . '</td>';
                            echo '<td class="text-center">' . $data['desde'] . '</td>';
                            echo '<td class="text-center">' . $data['hasta'] . '</td>';
                            echo '<td class="text-center">' . $data['hora_ingresa'] . '</td>';

                            if ($data['hora_ingresa']) {
                                echo '<td class="text-center">' . Html::a('<i class="fas fa-person-booth"></i> Ingresar', [
                                    '/comportamiento/index',
                                    "id" => $data['asistencia_id']
                                        ], ['class' => 'link', 'style' => 'color: #898b8d']) .
                                '</td>';
                            } else {
                                echo '<td class="text-center">' . Html::a('<i class="fas fa-user-clock btn_registrar"> Registrar</i>', [
                                    'registrar',
                                    "clase" => $data['clase_id'],
                                    'hora' => $data['hora_id']
                                        ], ['class' => 'link',
                                    'style' => 'color: #0a1f8f',
                                    //'id' => 'btn-registrar'.$data['detalle_id'],
                                    'onclick' => 'bloque_btn_registrar()'
                                ]);
                                echo '<i class="fas fa-spinner fa-spin btn-sppiner" style="display: none"></i>';
                                echo '</td>';
                            }

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
<!--                    <tfoot>
                        <tr bgcolor="ff9e18" class="text-center">
                            <th>CLASE</th>
                            <th>ASIGNATURA</th>
                            <th>CURSO</th>
                            <th>PARALELO</th>
                            <th>HORA</th>
                            <th>DESDE</th>
                            <th>HASTA</th>
                            <th>INGRESA</th>
                            <th>ACCIONES</th>
                        </tr>
                    </tfoot>-->
                </table>
            </div>

            <!--finaliza cuerpo de documento-->


        </div>
    </div>  

</div>


<script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>
<script>
    $("#table-asistencias").DataTable({
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

//    function alerta() {
//        alert('Registrado exitosamente!!!');
//    }

    function bloque_btn_registrar() {

        $('.btn_registrar').hide();
        $('.btn-sppiner').show();

        //alert(btn_id);

    }

</script>
