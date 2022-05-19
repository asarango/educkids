<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;
$fecha = date('Y-m-d H:i:s');

$this->title = 'Detalle de la Actividad';
?>


<div class="padre-actividaddetalle">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <!--<li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelAlumno->parallel_id]) ?>">Volver</a></li>-->                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item"><a href="<?=
                Url::to(['listaactividades'
                    , 'id' => $modelAlumno->student_id
                    , 'paralelo' => $modelAlumno->parallel_id])
                ?>">Lista de Actividades</a></li>                
            <li class="breadcrumb-item active" aria-current="page">DETALLE DE ACTIVIDAD</li>
            <li class="breadcrumb-item active" aria-current="page"><?=
                $modelAlumno->student->first_name . ' '
                . $modelAlumno->student->middle_name . ' '
                . $modelAlumno->student->last_name
                ?>
            </li>
        </ol>
    </nav> 



    <div style="padding-left: 20px; padding-right: 20px">

        <div class="" style="background-color: white; box-shadow: 0px 20px 20px #CCC; border: solid 1px #eee">

            <div class="row" style="padding-left: 15px">

                <div class="col-lg-4 col-md-4 tamano12 border-right" style="padding: 10px; padding-left: 50px; padding-right: 30px; color: #808080">


                    <p>
                    <h5><strong><?= $modelActividad->clase->materia->name ?></strong></h5>
                    <?= $modelActividad->title ?>
                    </p>

                    <hr>

                    <p>
                    <li><strong>DESCRIPCIÓN:</strong></li>
                    <?= $modelActividad->descripcion ?>
                    </p>


                    <p>
                    <li><strong>CALIFICADO:</strong></li>
                    <?= $modelActividad->calificado ?>
                    </p>


                    <p>
                    <li><strong>INSUMO:</strong></li>
                    <?= $modelActividad->insumo->nombre_nacional ?>
                    </p>

                    <!--                        <hr>
                                            <h5 class="card-title"><strong>FECHA DE CREACIÓN:</strong></h5>
                                            <p class="card-text"><?= $modelActividad->create_date ?></p>-->


                    <p>
                    <li><strong>FECHA DE PRESENTACIÓN:</strong></li>
                    <?= $modelActividad->inicio ?>
                    </p>


                    <p>
                    <li><strong>VIDEO-CONFERENCIA:</strong></li>
                    <a href="<?= $modelActividad->videoconfecia ?>" target="_blank">
                        <?= substr($modelActividad->videoconfecia, 0, 50) ?>
                    </a>
                    </p>

                    <p>
                    <li><strong>RESPALDO VIDEO-CONFERENCIA:</strong></li>
                    <a href="<?= $modelActividad->respaldo_videoconferencia ?>" target="_blank">
                        <?= substr($modelActividad->respaldo_videoconferencia, 0, 50) ?>
                    </a>
                    </p>

                    <p>
                    <li><strong>AULA VIRTUAL:</strong></li>
                        <a href="<?= $modelActividad->link_aula_virtual ?>" target="_blank">
                            <?= substr($modelActividad->link_aula_virtual, 0, 50) ?>
                        </a>
                    </p>


                </div> 
                <!--fin de detalle de actividad-->


                <!--INICIO DE SUBIDA DE ARCHIVOS DE ESTUDIANTE-->
                <div class="col-lg-4 col-md-4 border-right" style="padding: 10px; padding-left: 50px; padding-right: 30px; color: #808080">
                    <p>
                        <h5><strong>SUBIR ARCHIVOS</strong></h5>
                        &nbsp;
                    </p>
                    <hr>
                    <div class="">
                        <div class="card shadow-lg" style="padding: 30px; font-size: 10px">
                        <?php
                        if ($modelActividad->calificado == 'SI') {
//                            echo $modelActividad->id;
//                            echo $modelAlumno->student_id;

                            $total = 0;

                            $modelEntrega = \backend\models\ScholarisActividadDeber::find()->where([
                                        'actividad_id' => $modelActividad->id,
                                        'alumno_id' => $modelAlumno->student_id
                                    ])
                                    ->orderBy('creado_fecha')
                                    ->one();

                            if (!isset($modelEntrega)) {
                                $total = $total;
                            } else {
                                foreach ($modelEntrega as $entrega) {
                                    $total++;
                                }
                            }


                            if ($total < 1) {
//                                $entrega = 'No entregada';
                                echo '<div class="alert alert-danger">Tarea no entregada</div>';
                            } elseif ($modelEntrega->creado_fecha <= $modelActividad->inicio) {
                                $entrega = 'A tiempo';
                            } else {
                                //$entrega = 'Atrasada';
                                echo '<div class="alert alert-danger">Tarea entregada con atraso!</div>';
                            }
                        } else {
                            $entrega = '--';
                        }

//                        echo $entrega;
//                        if ($fecha < $modelActividad->inicio) {

                        $form = ActiveForm::begin([
                                    'action' => ['subirpadrenormal'],
                                    'options' => ['enctype' => 'multipart/form-data']
                        ]);
                        ?>

                        <?= $form->field($modelFormDeber, 'archivo')->fileInput()->label(false) ?>

                        <?= $form->field($modelFormDeber, 'actividad_id')->hiddenInput(['value' => $modelActividad->id])->label(false) ?>

                        <?= $form->field($modelFormDeber, 'alumno_id')->hiddenInput(['value' => $modelAlumno->student_id])->label(false) ?>

                        <?= $form->field($modelFormDeber, 'creado_por')->hiddenInput(['value' => $usuario])->label(false) ?>
                        <?= $form->field($modelFormDeber, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>
                        <?= $form->field($modelFormDeber, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(false) ?>
                        <?= $form->field($modelFormDeber, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(false) ?>

                        <?php if (!Yii::$app->request->isAjax) { ?>
                            <div class="form-group">
                                <?= Html::submitButton($modelFormDeber->isNewRecord ? 'Subir archivo' : 
                                        'Update', ['class' => $modelFormDeber->isNewRecord ? 
                                        'btn btn-outline-success btn-block' : 
                                        'btn btn-primary']) 
                                ?>
                            </div>
                        <?php } ?>

                        <?php ActiveForm::end(); ?>

                        <?php
//                        } else {
//                            echo '<p class="text-warning"><h2>Activida fuera de tiempo</h2></p>';
//                        }
                        ?>
                        
                    </div>


                        <hr>

                        <div class="table table-responsive">
                            <table class="table table-hover table-condensed table-striped" style="font-size: 10px;">
                                <thead>
                                    <tr>
                                        <th>Archivo</th>
                                        <th>Entregado</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($modelArchivosAl as $deb) {
                                        echo '<tr>';
//                                        echo '<td>'.$deb->archivo.'</td>';
                                        echo '<td>' . Html::a('<p style="font-size: 10px;">' . $deb->archivo . '</p>', ['descargar', "ruta" => $deb->archivo], ['class' => 'btn btn-link']) . '</td>';
                                        echo '<td>' . $deb->creado_fecha . '</td>';
                                        echo '<td>';

                                        if ($fecha <= $modelActividad->inicio) {

                                            echo '<span class="badge">'
                                            . Html::a('Eliminar', ['eliminardeber', "id" => $deb->id],
                                                    ['class' => 'text-danger']) . '</span>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>


                </div>
                <!--FIN DE SUBIDA DE ARCHIVOS DE ESTUDIANTE-->


                <!--INICIO DE MATERIAL DE APOYO -->
                <div class="col-lg-4 col-md-4" style="padding: 10px; padding-left: 50px; padding-right: 30px; color: #808080">
                    
                    <p>
                    <h5><strong>MATERIAL DE APOYO</strong></h5>
                    &nbsp;
                    </p>

                    <hr>
                    
                   
                    <?php
                    foreach ($modelArchivos as $archivo) {
//                        echo '<li class="list-group-item">';
                        echo '<div class="card shadow-lg" style="padding: 10px">';
                        echo '<font size="2px"><strong>' . $archivo->nombre_archivo . '</strong></font><br>';
                        echo '<span class="badge">'
                        . Html::a('Descargar', ['descargar', "ruta" => $archivo->archivo], ['class' => 'btn btn-link']) . '</span>';
//                        echo '</li>';
                    }
                    ?>
                </div>
                <!--FIN DE SUBIDA DE ARCHIVOS DE ESTUDIANTE-->
            </div>
        </div>
    </div>
</div>


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
        $("#tabla").DataTable();
        //        $('#tabla').DataTable();
    }




</script>