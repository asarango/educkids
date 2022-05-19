<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Educandi-Portal';


if ($tipoCalificacion == 0) {
    $sentenciasNotasAlumnos = new \backend\models\AlumnoNotasNormales();
} elseif ($tipoCalificacion == 2) {
    $sentenciasNotasAlumnos = new backend\models\AlumnoNotasDisciplinar();
} elseif ($tipoCalificacion == 3) {
    $sentenciasNotasAlumnos = new backend\models\AlumnoNotasInterdisciplinar();
} else {
    echo 'No tiene creado un tipo de calificación para esta institutción!!!';
    die();
}
?>

<style>
    .reportes{
        color: #000000;
        align-items: center;
    }
</style>


<div class="padre-notas">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
        </ol>
    </nav> 


    <div style="padding-left: 40px; padding-right: 40px">
         <div class="table table-responsive shadow-lg" style="padding: 30px;">
        <table class="table table-hover table-condensed table-bordered table-striped" style="font-size: 10px">
            <tr>
                <td><strong>ASIGNATURAS</strong></td>
                <?php
                foreach ($modelBloques as $bloque) {
                    echo '<td align="center"><strong>' . $bloque->abreviatura . '</strong></td>';
                }
                ?>

            </tr>
            <tr>
                <?php
                foreach ($modelClases as $clase) {
                    echo '<tr>';
                    echo '<td>' . $clase['materia'] . '</td>';

                    $notasM = $sentenciasNotasAlumnos->get_nota_materia($clase['grupo_id']);
                    echo '<td align="center">' . $notasM['p1'] . '</td>';
                    echo '<td align="center">' . $notasM['p2'] . '</td>';
                    echo '<td align="center">' . $notasM['ex1'] . '</td>';
                    echo '<td align="center">' . $notasM['p4'] . '</td>';
                    echo '<td align="center">' . $notasM['p5'] . '</td>';
                    echo '<td align="center">' . $notasM['ex2'] . '</td>';

//                        foreach ($modelBloques as $bloque) {
//                            echo '<td>' . consulta_nota($bloque->id, $modelAlumno->id, 
//                                                        $modelPeriodo->id, $clase['id'],
//                                                        $automatico). '</td>';
//                        }


                    echo '</tr>';
                }
                ?>
            </tr>
        </table>
    </div>
</div>



<div class="container">

<!--<img src="imagenes/educandi/hombrestrabajando.jpeg">-->


    <div class="table table-responsive">
        <!--<table class="table" style="font-size: 10px" background="imagenes/educandi/hombrestrabajando.jpeg">-->

    </div>


    <!--<div class="alert alert-dark">-->
    <div class="row">
        <div class="col-lg-4 mx-auto">

            <?php
            echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
            echo Html::a('Informe Aprendizaje Q1',
                    ['libretas',
                        'alumno' => $modelAlumno->id,
                        'paralelo' => $modelCurso->id,
                        'quimestre' => 'q1',
                        'reporte' => 'LIBRETAQ1V1',
                    ],
                    ["class" => 'reportes']);
            ?>



            <?php
//                    if($modelRepoLib->valor == 2){
//                        echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
//                        echo Html::a('Reporte Final',
//                                ['reportetotal', 
//                                    'alumno' => $modelAlumno->id,
//                                    'paralelo' => $modelCurso->id
//                                ], 
//                                ["class" => 'reportes']);
//                    }elseif($modelRepoLib->valor == 3){
//                        echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
//                        echo Html::a('Reporte Final',
//                                ['reportetotal2', 
//                                    'alumno' => $modelAlumno->id,
//                                    'paralelo' => $modelCurso->id
//                                ], 
//                                ["class" => 'reportes']);
//                    }
            ?>
        </div>
        <div class="col-lg-4 mx-auto">
            <?php
            echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
            echo Html::a('Informe Aprendizaje Q2',
                    ['libretas',
                        'alumno' => $modelAlumno->id,
                        'paralelo' => $modelCurso->id,
                        'quimestre' => 'q2',
                        'reporte' => 'LIBRETAQ1V1',
                    ],
                    ["class" => 'reportes']);
            ?>
        </div>


        <div class="col-lg-4 mx-auto">
            <?php
            echo '<img src="imagenes/educandi/reporte.png" style="align="center">';
            echo Html::a('Informe Resumen Final',
                    ['reporteresumen',
                        'alumno' => $modelAlumno->id,
                        'paralelo' => $modelCurso->id,
                        'quimestre' => 'q2',
                        'reporte' => 'LIBRETAQ1V1',
                    ],
                    ["class" => 'reportes']);
            ?>
        </div>
    </div>
    <!--</div>-->


</div>





</div>

<?php
//function consulta_nota($bloqueId, $alumnoId, $periodoId, $clase, $automatico) {
//
//    $modelBloque = backend\models\ScholarisBloqueActividad::findOne($bloqueId);
//    $codigoCovid = $modelBloque->codigo_tipo_calificacion;
//
//    $totalActividades = \backend\models\ScholarisActividad::find()->where([
//                'paralelo_id' => $clase,
//                'bloque_actividad_id' => $bloqueId,
//                'calificado' => 'SI'
//            ])->all();
//
//
//
//    $calificacionCovid = new \backend\models\CalificacionCovidParcial($codigoCovid, $alumnoId, $periodoId);
//    $modelTotalDeberes = $calificacionCovid->get_total_actividades($clase, $bloqueId);
//
//    $calificacionCovid->get_notas_por_clase($clase, $bloqueId, $automatico, count($totalActividades), count($modelTotalDeberes));
//
//
//    return $calificacionCovid->notaTotal;
//}
?>