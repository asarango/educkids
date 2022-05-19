<?php

use yii\helpers\Html;
use backend\models\PlanificacionOpciones;
use backend\models\ScholarisArchivosprofesor;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Actividad #: ' . $modelActividad->id . ' | ' . $modelActividad->title;

/** Extrae path donde se almacena los archivos */
$path_archivo_profesor = PlanificacionOpciones::find()
    ->where([
        'tipo' => 'SUBIDA_ARCHIVO',
        'categoria' => 'PATH_PROFE'
    ])->one();
$path_archivo_profesor_ver = PlanificacionOpciones::find()
    ->where([
        'tipo' => 'VER_ARCHIVO',
        'categoria' => 'PATH_PROFE'
    ])->one();

/** Extraer listado de archivos subidos por actividad */
$modelArchivoProfesor = ScholarisArchivosprofesor::find()
    ->where([
        'idactividad' => $modelActividad->id
    ])
    ->orderBy(['orden' => SORT_ASC])
    ->all();

$modelActividadConsulta = new ScholarisArchivosprofesor();

?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="scholaris-actividad-index">
    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-10">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/retroalimentacion.png" width="64px" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <p>(
                        <?=
                        ' <small>' . $modelActividad->clase->materia->name .
                            ' - ' .
                            'Clase #:' . $modelActividad->clase->id .
                            ' - ' .
                            $modelActividad->clase->curso->name . ' - ' . $modelActividad->clase->paralelo->name . ' / ' .
                            $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' / ' .
                            'Es calificado: ' . $modelActividad->calificado . ' / ' .
                            'Tipo de actividad: ' . $modelActividad->tipo_calificacion .
                            '</small>';
                        ?>
                        )
                    </p>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 col-md-6"> |
                    <?php echo Html::a(
                        '<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fa fa-plus-circle" aria-hidden="true"></i> Material apoyo</span>',
                        ['scholaris-archivosprofesor/create', "id" => $modelActividad->id],
                        ['class' => '', 'title' => 'AGREGAR MATERIAL DE APOYO']
                    ); ?>
                    |
                </div>
                <!-- fin de primeros botones -->

                <!--botones derecha-->
                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    |
                    <?php
                    if ($modelActividad->calificado == 'SI') {

                        if ($modelActividad->tipo_calificacion == 'P') {
                            if (count($modelCriterios) > 0 && $modelActividad->calificado == 'SI') {
                                echo Html::a(
                                    '<span class="badge rounded-pill bg-cuarto"><i class="fas fa-highlighter"></i> Calificar</span>',
                                    ['calificar', "id" => $modelActividad->id],
                                    ['class' => 'link']
                                );
                                //echo '|';
                                $cantidadCalif = backend\models\ScholarisCalificaciones::find()->where(['idactividad' => $modelActividad->id])->all();

                                if (count($cantidadCalif) > 0) {
                                } else {
                                    echo Html::a(
                                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fas fa-highlighter"></i> Criterios</span>',
                                        ['criterios', "id" => $modelActividad->id],
                                        ['class' => '']
                                    );
                                    echo '<p class="text-danger">Si da clic en el boton calificar ya no puede modificar criterios</p>';
                                }
                            } else {
                                echo Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fas fa-highlighter"></i> Criterios</span>',
                                    ['criterios', "id" => $modelActividad->id],
                                    ['class' => 'link']
                                );
                            }
                        } else {
                            echo Html::a(
                                '<span class="badge rounded-pill bg-cuarto"><i class="fas fa-highlighter"></i> Calificar</span>',
                                ['calificar', "id" => $modelActividad->id],
                                ['class' => 'link']
                            );
                        }
                    }
                    ?>
                    |
                    <?php
                    if ($estado == 'abierto') {
                        echo Html::a('<span class="badge rounded-pill" style="background-color: #898b8d"><i class="fas fa-highlighter"></i> Editar</span>', [
                            'update',
                            "id" => $modelActividad->id
                        ], ['class' => 'link', 'target' => 'blank']);

                        echo '|';

                        echo Html::a('<span class="badge rounded-pill" style="background-color: red"><i class="far fa-trash-alt"></i> Eliminar</span>', [
                            'eliminar',
                            "id" => $modelActividad->id
                        ], ['class' => 'link', 'target' => 'blank']);

                        echo '|';
                        if ($modelActividad->actividad_original == 0) {
                            echo Html::a('<span class="badge rounded-pill" style="background-color: #ff9e18"><i class="far fa-clone"></i> Duplicar</span>', [
                                'duplicar',
                                "id" => $modelActividad->id
                            ], ['class' => 'link', 'target' => 'blank']);
                        } else {
                            echo '¡No de puede duplicar la actividad!';
                        }

                        echo '|';
                    }
                    ?>

                </div> <!-- FIN DE BOTONES DE ACCION Y NAVEGACIÓN -->
            </div>


            <!-- /****************************************************************************************************/  -->
            <!-- comienza cuerpo  -->
            <div class="row" style="margin-bottom: 50px; margin-top: 30px">
                <!--
                <div class="col-lg-4 col-md-4">
                    <div class="card shadow p-2" style="background-color: #898b8d; color: white">
                        <div class="row">
                            <div class="col-md-4"><strong>Número:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->id ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Tipo:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->insumo->nombre_nacional ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Estado:</strong></div>
                            <div class="col-md-8"><?= $estado ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Materia:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->clase->materia->name ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Curso:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->clase->curso->name . ' - ' . $modelActividad->clase->paralelo->name ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Profesor:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Calificado:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->calificado ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Tipo:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->tipo_calificacion ?></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4"><strong>Creado:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->create_date ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Presentar el:</strong></div>
                            <div class="col-md-8"><?= $modelActividad->inicio ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Descripción:</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12"><?= $modelActividad->descripcion ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Tareas:</strong></div>
                        </div>
                        <div class="row-md-12">
                            <div class="col"><?= $modelActividad->tareas ?></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Enlace videoconferencia:</strong></div>
                        </div>
                        <div class="row-md-12">
                            <div class="col"><a href="<?= $modelActividad->videoconfecia ?>" target="_blank"><?= $modelActividad->videoconfecia ?></a></div>
                        </div>

                        <div class="row">
                            <div class="col-md-4"><strong>Respaldo videoconferencia:</strong></div>
                        </div>
                        <div class="row-md-12">
                            <div class="col"><a href="<?= $modelActividad->respaldo_videoconferencia ?>" target="_blank"><?= $modelActividad->respaldo_videoconferencia ?></a></div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12"><strong>Link aula virtual:</strong></div>
                        </div>
                        <div class="row-md-12">
                            <div class="col"><a href="<?= $modelActividad->link_aula_virtual ?>" target="_blank"><?= $modelActividad->link_aula_virtual ?></a></div>
                        </div>
                    </div>
                </div>
                -->
                <!-- FIN DE PANEL IZQUIERDO -->

                <!-- inicia condicion de si la actividad es pai-->
                <?php if ($modelActividad->tipo_calificacion == 'P') { ?>
                    <div id="accordion" >
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        DATOS P.A.I
                                    </button>
                                </h5>
                            </div>
                            <div id="collapseOne" class="row collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                    <div class="col-lg-3 col-md-3 col-sm-3 card-body">
                                        <!-- INICIA CUADRO DE TABLA DE ESTADISTICAS -->
                                            <div class="card p-3">
                                                <p class="text-color-s"><b>ESTADÍSTICAS DEL BLOQUE</b></p>
                                                <?php
                                                echo $this->render('estadisticas-pai', [
                                                    'estadisticas' => $estadisticas,
                                                    'modelActividad' => $modelActividad
                                                ]);
                                                ?>
                                            </div>
                                    </div>
                                <!-- FINALIZA CUADRO DE TABLA DE ESTADISTICAS -->
                                <div class="col-lg-9 col-md-9 col-sm-9">
                                    <div class="row">

                                        <!-- <div class="col-lg-12 col-md-12"> -->
                                        <!--    <div class="card p-3" style="margin-top: 10px;"> -->
                                        <!--INICIA CUADRO DE CRITERIOS PAI-->
                                        <!--<p class="text-color-s"><b>CRITERIOS SELECCIONADOS PARA LA ACTIVIDAD </b></p>  -->
                                        <?php // foreach ($modelCriterios as $criterio) { 
                                        ?>
                                        <!--<div class="row"> -->
                                        <!--<div class="col-md-1"> -->
                                        <?php //$criterio->criterio->criterio 
                                        ?>
                                        <!--</div> -->

                                        <!-- <div class="col"> -->
                                        <?php //$criterio->detalle->descricpcion 
                                        ?>
                                        <!--</div> -->
                                        <!--</div> -->
                                        <?php
                                        //} 
                                        ?>
                                        <!--</div> -->
                                        <!-- FINALIZA CUADRO DE CRITERIOS PAI-->
                                        <!-- <?php //} else { 
                                                ?> -->
                                        <!--<div class="card p-2 text-color-s"><b>No existe informaciòn para actividades nacionales</b></div> -->
                                        <!-- <?php //} 
                                                ?> -->
                                        <!-- cierra condicion de si la actividad es pai-->
                                        <!--</div>  -->

                                        <div class="card p-3" style="margin-top: 10px;">
                                            <?php
                                            $total = count($modelCalificaciones2);
                                            if ($total > 0) {
                                                echo '<div class="alert alert-danger" role="alert">
                                            Ya existen calificaciones realizadas, usted no puede escoger nuevos criterios para esta actividad
                                            </div>';
                                            } else {
                                                echo '<div class="alert alert-success" role="alert">
                                                Por favor selecciones los criterios a usar
                                                </div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col container-fluid" style="overflow-y: scroll; height:500px;">
                                            <!--inicio asignados-->
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="color:#ab0a3d">ASIGNADOS</h5>
                                                    <hr>
                                                    <font size='2px'>
                                                        <?php
                                                        if (isset($asignados)) {
                                                            foreach ($asignados as $criterio) {
                                                                echo '<div class="row">';
                                                                echo '<div class="col-md-1">' . $criterio['criterio'] . '</div>';
                                                                if ($total > 0) {
                                                                    echo '<div class="col">' . $criterio['descricpcion'] . '</div>';
                                                                } else {
                                                                    echo '<div class="col">' . Html::a(
                                                                        $criterio['descricpcion'],
                                                                        [
                                                                            'quitarcriterio',
                                                                            "id" => $criterio['id']
                                                                        ],
                                                                        ['class' => 'card-link']
                                                                    ) .
                                                                        '</div>';
                                                                }

                                                                echo '</div>';
                                                                echo '<hr>';
                                                            } //cierra foreach
                                                        } // cierra if
                                                        ?>
                                                    </font>
                                                </div>
                                            </div>
                                            <!--fin asignados-->
                                        </div>
                                        <div class="col container-fluid" style="overflow-y: scroll; height:500px;">
                                            <!--inicio no asignados-->
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="color:#ab0a3d">NO ASIGNADOS</h5>
                                                    <hr>
                                                    <font size='3px'>
                                                        <?php
                                                        foreach ($noAsignados as $criterio) {
                                                            echo '<div class="row">';
                                                            echo '<div class="col-md-1">' . $criterio['criterio'] . '</div>';
                                                            if ($total > 0) {
                                                                echo '<div class="col" >' . $criterio['descricpcion'] . '</div>';
                                                            } else {
                                                                echo '<div class="col" >' . Html::a(
                                                                    $criterio['descricpcion'],
                                                                    [
                                                                        'asignarcriterio',
                                                                        'actividad' => $modelActividad->id,
                                                                        'criterio' => $criterio['criterio_id'],
                                                                        'detalle' => $criterio['detalle_id']
                                                                    ],
                                                                    ['class' => 'card-link']
                                                                ) .
                                                                    '</div>';
                                                            }
                                                            echo '</div>';
                                                            echo '<hr>';
                                                        } //cierra foreach
                                                        ?>
                                                    </font>
                                                </div>
                                            </div>
                                            <!--fin no asignados-->
                                        </div>
                                    </div>
                                </div> <!-- fin de panel derecho -->
                                
                            </div>
                            </div>
                        </div> <!-- fin div acordeon -->
                    <?php } ?>
                    
                    <!-- cierra IF-->
                    <!-- fin div container-pep ESTADISTICO -->


                    <!-- ***************************************************************************************************************************************** -->
                    <!-- /***** MENU SUBIDA DE ARCHVIOS  ***/ -->
                    <div id="deberes" class="conteiner-lg-8">
                        <p></p>
                        <div class="row">
                            <div class="card-title" style="text-align: center; background-color: #ff9e18;">
                                <label style="color:#ab0a3d"><b>MATERIAL DE APOYO</b></label>
                            </div>

                            <!-- PANEL DERECHA -->
                            <div class="col-4">
                                <!-- PANEL NUEVO / VISTA PREVIA -->
                                <div class="card">
                                    <div class="card-header">
                                        Panel
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <button type="button" class="btn btn-primary">
                                                <a href="<?= Url::to(['scholaris-actividad/actividad', 'actividad' => $modelActividad->id]); ?>"><img src="imagenes/iconos/nuevo32px.png" title="Nueva Actividad" /></a>
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVistaPrevia">
                                                <img src="imagenes/iconos/preview32px.png" title="Vista Previa" />
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <!-- div Vista Previa TODOS -->
                                <div class="row container-fluid">
                                    <div class="col">
                                        <!-- Modal: Vista Previa TODOS -->
                                        <div class="modal fade" id="modalVistaPrevia" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                                            <div class="modal-dialog">
                                                <div class="modal-content" style="width: 800px; ">
                                                    <div class="modal-header" style="background-color: #ff9e18;">
                                                        <h6 class="modal-title" id="exampleModalLabel" style="color: #ab0a3d;">Vista Previa Todas las Tareas</h6>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body" >
                                                        <?php foreach ($modelArchivoProfesor as $modelArchivo) {
                                                            $modelArchivo->nombre_archivo;
                                                            $nombreArchivo =  $modelArchivo->archivo;
                                                            $arrayNombre = explode("##", $nombreArchivo);

                                                            if ($modelArchivo->publicar) {
                                                        ?>
                                                                <div class="card" style="color:black ;background-color: #0a1f8f;">
                                                                <h6 style="color:white;"><b>Titulo: <?= $modelArchivo->nombre_archivo; ?></b></h6>
                                                                </div>
                                                                
                                                                <hr>
                                                                <span style="color: black;">
                                                                    <?= $modelArchivo->texto; ?>
                                                                </span>
                                                                <hr>
                                                                <?php if ($modelArchivo->archivo <> "") { ?>
                                                                    <div class="container">
                                                                        <h6 style="color:#ab0a3d">Archivos Asociados</h6><br>
                                                                        <embed width="200" height="100" src="<?= $path_archivo_profesor_ver->opcion .$modelArchivo->idactividad. '/' . $arrayNombre[1]; ?>">
                                                                        </embed>
                                                                        <br>
                                                                        <a href="<?= $path_archivo_profesor_ver->opcion .$modelArchivo->idactividad. '/' . $arrayNombre[1]; ?>" target="_blank">
                                                                            <?= $arrayNombre[1]; ?>
                                                                        </a>
                                                                    </div>
                                                                    <hr>
                                                        <?php
                                                                } //FIN IF archivo 
                                                            } //FIN IF publicar
                                                        } //FIN FOR 
                                                        ?>

                                                    </div>
                                                    <div class="modal-footer">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- ****************************************************************************************** -->
                                        <!-- fin foreach -->
                                    </div>
                                </div> <!-- fin div Vista Previa TODOS -->


                                <!-- /***** panel izquierda: VISUALIZACION DE ARCHVIOS  ***/ -->
                                <div class="col card">

                                    <label style="text-align: center;font-size: 14px;">Tareas: <?= count($modelArchivoProfesor) ?></label>

                                    <!-- div archivo -->
                                    <div class="row">
                                        <div class="col container-fluid">
                                            <table class="table table-responsive table-striped table-hover">
                                                <tr style="font-size: 11px;">
                                                    <td><b>Orden</b></td>
                                                    <td><b>Borrar</b></td>
                                                    <td><b>Editar</b></td>
                                                    <td><b>Publicar</b></td>
                                                    <td>
                                                        <b>Título</b>
                                                    </td>
                                                    <td><b>Ver</b></td>
                                                </tr>
                                                <?php foreach ($modelArchivoProfesor as $modelArchivo) {
                                                    $modelArchivo->nombre_archivo;
                                                    $nombreArchivo =  $modelArchivo->archivo;
                                                    $arrayNombre = explode("##", $nombreArchivo);

                                                ?>
                                                    <tr style="font-size: 10px;">
                                                        <!-- orden -->
                                                        <td>
                                                            <span style="align-items: center; font-size: 12px;"><?php echo "$modelArchivo->orden"; ?></span>
                                                        </td>
                                                        <!-- eliminar -->
                                                        <td>
                                                            <a href="<?= Url::to(['actividad', 'actividad' => $modelArchivo->idactividad, 'idMatApoyo' => $modelArchivo->id, 'bandera' => 2]);
                                                                        /**el valor 2 es delete */ ?>">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </td>
                                                        <!-- Editar -->
                                                        <td>
                                                            <a href="<?= Url::to(['actividad', 'actividad' => $modelArchivo->idactividad, 'idMatApoyo' => $modelArchivo->id, 'bandera' => 1]);
                                                                        /**el valor 1 es update */ ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        </td>
                                                        <!-- Publicar -->
                                                        <td>
                                                            <?php if ($modelArchivo->publicar) { ?>
                                                                <span style="color: green;"><i class="fas fa-check-circle"></i></span>
                                                            <?php } else { ?>
                                                                <span style="color: red;"><i class="fas fa-times-circle"></i></span>
                                                            <?php } ?>
                                                        </td>
                                                        <!-- Titutlo -->
                                                        <td>
                                                            <?php echo "$modelArchivo->nombre_archivo"; ?>
                                                        </td>
                                                        <!-- Ver -->
                                                        <td>
                                                            <button type="button" class="" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $modelArchivo->id ?>">
                                                                <img src="imagenes/iconos/eye16px.png" title="Vista Previa" />
                                                            </button>
                                                        </td>

                                                    </tr>
                                                    <!-- ****************************************************************************************** -->
                                                    <!-- Modal: Vista Previa Independiente -->
                                                    <div class="modal fade" id="exampleModal<?= $modelArchivo->id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="false">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content" style="width: 800px; ">
                                                                <div class="modal-header" style="background-color: #ff9e18;">
                                                                    <h6 class="modal-title" id="exampleModalLabel" style="color: #ab0a3d;">Vista Previa</h6>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <h6 style="color:black ;"><b>Titulo: <?= $modelArchivo->nombre_archivo; ?></b></h6>
                                                                    <hr>
                                                                    <p style="color:black ;"><b>Pubicar: </b>
                                                                        <?php if ($modelArchivo->publicar) { ?>
                                                                            <span style="color: blue;">SI</span>
                                                                        <?php } else { ?>
                                                                            <span style="color: red;">NO</span>
                                                                        <?php } ?>
                                                                    </p>
                                                                    <hr>

                                                                    <span style="color: black;">
                                                                        <?= $modelArchivo->texto; ?>
                                                                    </span>
                                                                </div>
                                                                <hr>
                                                                <?php if ($modelArchivo->archivo <> "") { ?>

                                                                    <div class="container">
                                                                        <h6 style="color:#ab0a3d">Archivos Asociados</h6><br>
                                                                        <embed width="200" height="100" src="<?= $path_archivo_profesor_ver->opcion.$modelArchivo->idactividad. '/' . $arrayNombre[1]; ?>">
                                                                        </embed>
                                                                        <br>
                                                                        <a href="<?= $path_archivo_profesor_ver->opcion.$modelArchivo->idactividad.'/' . $arrayNombre[1]; ?>" target="_blank">
                                                                            <?= $arrayNombre[1]; ?>
                                                                        </a>
                                                                    </div>

                                                                <?php } ?>
                                                                <div class="modal-footer">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- FIN MODAL:Vista Previa Independiente -->
                                                    <!-- ****************************************************************************************** -->

                                                <?php } ?>
                                                <!-- fin foreach -->
                                            </table>
                                        </div>
                                    </div> <!-- fin div archivo -->

                                </div><!-- fin div visualiza archivos-->
                            </div><!-- FIN Modal -->
                            <!-- ******************************************************************************************************** -->
                            <!-- Inicio div Visualizacion de Archivos -->
                            <div class=" card col-8">
                                <div class="row">
                                    <div>
                                        <!-- Table de mostrar archivos-->
                                        <table>
                                            <?php
                                            if (!empty($model->archivo)) {
                                                $arrayArchivos = explode("##", $model->archivo);
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="container">
                                                            <embed width="200" height="100" src="<?= $path_archivo_profesor_ver->opcion .$arrayArchivos[0].'/' . $arrayArchivos[1]; ?>">
                                                            </embed>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <label><?= $arrayArchivos[1]; ?></label>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td>
                                                        <p><b>NO TIENE ARCHIVOS</p></b>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                        <br>
                                        <!-- FIN Table de mostrar archivos-->

                                    </div>
                                    <div class="modal-body card">
                                        <!-- Llamada formulario subida de archivos -->
                                        <?= $this->render('/scholaris-archivosprofesor/_form', [
                                            'model' => $model,
                                            'modelActividad' => $modelActividad
                                        ]) ?>
                                    </div>
                                </div>
                            </div>
                            <!-- fin tipo archivos -->
                        </div><!--  fin row  -->
                    </div>



            </div>
            <!-- finaliza cuerpo -->
        </div>
    </div>
</div>