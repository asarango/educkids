<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
?>


<div class="padre-actividades">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">ACTIVIDADES DE DESEMPEÑO DEL AÑO LECTIVO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav> 

        <div class="row">            

            <div class="table table-responsive tamano10P">
                <table class="table table-hover table-bordered table-striped" id="tabla">
                    <thead>
                        <tr>
                            <th>MATERIA</th>
                            <th>PROFESOR</th>
                            <th>TÍTULO</th>
                            <th>QUIMESTRE</th>
                            <th>CREADO</th>
                            <th>DESDE</th>
                            <th>HASTA</th>
                            <th>DESCARGAR</th>
                            <th>OBSERVACIÓN</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($modelActividades as $actividad) {
                            echo '<tr>';
                            echo '<td>' . $actividad['materia'] . '</td>';
                            echo '<td>' . $actividad['last_name'] . ' ' . $actividad['x_first_name'] . '</td>';
                            echo '<td>' . $actividad['titulo'] . '</td>';
                            echo '<td>' . $actividad['quimestre_codigo'] . '</td>';
                            echo '<td>' . $actividad['creado_fecha'] . '</td>';
                            echo '<td>' . $actividad['fecha_inicio'] . '</td>';
                            echo '<td>' . $actividad['fecha_entrega'] . '</td>';

                            echo '<td>';

                            if ($actividad['tipo_material'] == 'ARCHIVO') {
                                echo Html::a('<i class="fas fa-cloud-download-alt"></i> ', ['descargainicial',
                                    'actividadId' => $actividad['id']
                                ]);
                            } else {

                                if ($actividad['respaldo_videoconferencia']) {
                                    echo '<a href="'.$actividad['respaldo_videoconferencia'].'" '
                                          .'target="_blank"><i class="fab fa-youtube"></i></a>';                                                                      
                                } else {
                                    
                                    echo '<a href="'.$actividad['link_videoconferencia'].'" '
                                          .'target="_blank"><i class="fas fa-video"></i></a>';
                                }
                            }



                            echo '</td>';

                            if ($hoy >= $actividad['fecha_inicio'] && $hoy <= $actividad['fecha_entrega'] && $actividad['total_archivos'] < 1) {
                                echo '<td>Habilitada</td>';
                                echo '<td>';
                                echo Html::a('Subir archivo', ['formulariosubir',
                                    "actividadId" => $actividad['id'],
                                    "alumnoId" => $modelAlumno->id
                                ]);
                                echo '</td>';
                            } else if ($hoy > $actividad['fecha_entrega'] && $actividad['total_archivos'] == 0) {
                                echo '<td bgcolor="#FFF0000">Tarea no entregada</td>';
                                echo '<td></td>';
                            } else {
                                echo '<td bgcolor="#65f595">Tarea entregada<br>';
                                if ($actividad['observacion_profesor']) {
                                    echo '<a href="#" data-toggle="modal" '
                                    . 'class="text-info" data-target="#exampleModal" '
                                    . 'onclick="muestraObservacion(' . $actividad['id'] . ',' . $modelAlumno->id . ')">'
                                    . '<i class="far fa-envelope"></i></a>';
                                }

                                echo '</td>';
                                echo '<td></td>';
                            }

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>

        </div>


    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Observaciones de tarea</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="detalle"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>
<!--fin modal-->


<br>
<br>
<br>
<br>
<script src="jquery/jquery18.js"></script>
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->
<script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
<script>

//    $(document).ready( function () {
//    $('#table_id').DataTable();
//} );

    hola();

    function hola() {
//        console.log('ola k ase');
        $("#tabla").DataTable({
            "order": [[1, "desc"]]
        });
//        $('#tabla').DataTable();
    }



    function muestraObservacion(actividad, alumno) {

        var url = "<?= Url::to(['observacionesprf']) ?>";
        var parametros = {
            "actividad": actividad,
            "alumno": alumno
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'GET',
            beforeSend: function () {},
            success: function (response) {
                $("#detalle").html(response);
            }
        });

    }



</script>

<?php

function totalArchivo($actividad) {
    $con = Yii::$app->db;
    $query = "select 	count(id) as total
                    from	scholaris_archivosprofesor
                    where	idactividad = $actividad;";
    $res = $con->createCommand($query)->queryOne();
    return $res['total'];
}
?>