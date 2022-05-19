<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
?>


<div class="padre-actividades">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['listaactividades', 'id' => $modelAlumno->id, 'paralelo' => $paralelo]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">ACTIVIDADES DE DESEMPEÑO DEL AÑO LECTIVO</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
        </ol>
    </nav>

    <div style="padding-left: 40px; padding-right: 40px">

        
        
    </div>

    <div class="container">


        <div class="card shadow-lg" style="padding: 30px">            

            <div class="table table-responsive tamano10P">
                <table class="table table-hover table-bordered table-striped smallFont" id="tabla">
                    <thead>
                        <tr>
                            <th>FECHA_CALIFICACION</th>
                            <th>BLOQUE</th>
                            <th>SEMANA</th>
                            <th>ASIGNATURA</th>
                            <th>TIPO ACTIVIDAD</th>
                            <th>ACTIVIDAD</th>
                            <th>ES_CALIFICADO</th>
                            <th>NOTA</th>
                            <th>ENTREGA</th>
                            <th>MENSAJE</th>
                            <th>ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($modelActi as $actividad) {

                            if ($actividad['calificado'] == 'SI') {

                                $modelEntrega = \backend\models\ScholarisActividadDeber::find()->where([
                                            'actividad_id' => $actividad['actividad_id'],
                                            'alumno_id' => $modelAlumno->id
                                        ])
                                        ->orderBy('creado_fecha')
                                        ->one();

                                if (!isset($modelEntrega->creado_fecha)) {
                                    $entrega = 'No entregada';
                                } elseif ($modelEntrega->creado_fecha <= $actividad['inicio']) {
                                    $entrega = 'A tiempo';
                                } else {
                                    $entrega = 'Atrasada';
                                }
                            } else {
                                $entrega = '--';
                            }


                            echo '<tr>';
                            echo '<td>' . $actividad['inicio'] . '</td>';
                            echo '<td>' . $actividad['bloque'] . '</td>';
                            echo '<td>' . $actividad['nombre_semana'] . '</td>';
                            echo '<td>' . $actividad['materia'] . '</td>';
                            echo '<td>' . $actividad['nombre_nacional'] . '</td>';
                            echo '<td class="alinearIzquierda">' . $actividad['title'] . '</td>';

                            echo '<td>' . $actividad['calificado'] . '</td>';
                            echo '<td>' . $actividad['calificacion'] . '</td>';
                            echo '<td>' . $entrega . '</td>';

                            echo '<td>';
                            if ($actividad['observacion']) {
                                echo '<i class="far fa-envelope"></i>';
                            }

                            echo '</td>';
                            echo '<td>';


                            $totalArchivos = totalArchivo($actividad['actividad_id']);

                            if ($actividad['videoconfecia']) {
                                $camara = '<i class="fas fa-video"></i>';
                            } else {
                                $camara = '';
                            }


                            echo Html::a('...' . $totalArchivos . '...' . $camara, ['actividaddetalle',
                                "actividadId" => $actividad['actividad_id'],
                                "alumnoId" => $modelAlumno->id,
                                'paralelo' => $paralelo
                            ]);
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>

            </div>

        </div>


    </div>
</div>
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