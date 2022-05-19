<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Educandi-Portal';

//$pathFoto = $modelFotos->nombre . $modelAlumno->id . '&field=photo&unique=20210405173009';
$pathFoto = $modelFotos->nombre . $modelAlumno->id . $modelFotosPath2->nombre;
?>

<style>

</style>


<div class="padre-alumno">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO Y COMPORTAMENTAL</li>
        </ol>
    </nav>


    <div class="" style="padding: 30px">

        <div class="row" style="height: 100%">
            <div class="col-lg-3 col-md-3 text-center">
                <a href="#"><img src="<?= $pathFoto ?>" alt="" width="250"></a>
                <p class="smallFont m-3">
                    <strong><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></strong>
                    <br>
                    <?= $modelAlumno->course->name . ' "' . $modelCurso->name . '" ' ?>
                    <br>
                    <?= $modelCurso->course->section0->name ?>
                    <br>
                    <?= $modelCurso->course->section0->code ?>
                </p>        
            </div>


            <div class="col-lg-6 col-md-6">
                <div class="card shadow tamano12" style="width:100%; height:500px; overflow: scroll; padding: 10px">

                    <?php
                    if (count($novedades) > 0) {

                        foreach ($novedades as $novedad) {
                            ?>

                            <div class="shadow p-3" style="margin-top: 30px">
                                <strong><?= $novedad['fecha'] . ' / ' . $novedad['codigo'] . ' - ', $novedad['nombre'] ?></strong>
                                <br>
                                <?= $novedad['materia'] . ' - ' . $novedad['profesor'] ?>
                                <hr>

                                <?= $novedad['observacion'] ?>

                            </div> 
                            <?php
                        }
                    } else {
                        echo '<p><h3><strong>Sin novedades de comportamiento!!!</strong></h3></p>';
                    }
                    ?>


                </div>
            </div>

            <div class="col-lg-3 col-md-3">

                <?php
                if ($modelCurso->course->section0->code == 'PREPARATORIA' || $modelCurso->course->section0->code == 'INICIAL') {
                    ?>
                    <p>
                        <a href="<?= Url::to(['notasinicial', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                            <i class="fas fa-angle-double-right"></i> Notas
                        </a>

                    </p>

                    <?php
                } else {



                    if ($modelRevisaNotas->valor == 1) {
                        if ($parametroCalifica->valor == 0) {
                            ?>
                            <p>
                                <a href="<?= Url::to(['notas', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                                    <i class="fas fa-angle-double-right"></i> Notas
                                </a>
                            </p>
                            <?php
                        } else {
                            ?>
                            <p>
                                <a href="<?= Url::to(['notas-covid', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                                    <i class="fas fa-angle-double-right"></i> Notas
                                </a>
                            </p>
                            <?php
                        }
                    }
                }
                ?>


                <?php
                if (count($tipoCalificacion) > 0) {
                    ?>
                    <p>
                        <a href="<?= Url::to(['calificacionpadre', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                            <i class="fas fa-angle-double-right"></i> Calificar a mi hijo
                        </a>
                    </p>
                    <?php
                }
                ?>



                <?php
                if ($modelCurso->course->section0->code == 'PREPARATORIA' || $modelCurso->course->section0->code == 'INICIAL') {
                    ?>
                    <p>
                        <a href="<?= Url::to(['listaactividadesinicial', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                            <i class="fas fa-angle-double-right"></i> Actividades
                        </a>
                    </p>
                    <?php
                } else {
                    ?> 
                    <p>
                        <a href="<?= Url::to(['listaactividades', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                            <i class="fas fa-angle-double-right"></i> Actividades
                        </a>
                    </p>
                    <?php
                }
                ?>




<!--                <p>
                    <a href="<?= Url::to(['comportamiento', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">
                        <i class="fas fa-angle-double-right"></i> Comportamiento
                    </a>
                </p>-->
    <!--                    <a href="<?= Url::to(['cambiar', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>" 
                   class="list-group-item list-group-item-action list-group-item-danger">
                    Cambiar Foto
                </a>-->

            </div>
        </div>
    </div>
</div>