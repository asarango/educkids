<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;
$modelLibreta = backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'publicalib'])->one();

$this->title = 'Educandi-Portal';
?>


<div class="padre-notas">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav> 

        <div class="">

            <h1>PARTICIPACIÓN - <small>CALIFICACIÓN POR FAMILIA</small></h1>





            <div class="row">
                <?php
                foreach ($tipoCalificacion as $tipo) {
                    ?>


                    <div class="col-lg-6">
                        <div class="jumbotron">
                            <h3 class="display-6"><?= $tipo['nombre'] ?></h3>
                            <hr class="my-4">

                            <?php
                            if (!isset($tipo['valor'])) {

//                                    echo Html::beginForm(['order/update', 'id' => $id], 'post');
                                echo Html::beginForm(['calificacionpadre'], 'post');
                                ?>


                                <p>Seleccionar calificación:</p>

                                
                                <?php
                                    foreach ($modelRubrica as $rubrica){
                                        ?>
                                
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="rubrica_calificacion" id="exampleRadios1" value="<?= $rubrica->id ?>" required="">
                                    <label class="form-check-label" for="exampleRadios1">
                                        <?= '( '.$rubrica->valor.' ) '.$rubrica->descripcion ?>
                                    </label>
                                </div>
                                
                                <input type="hidden" name="id" value="<?= $modelAlumno->id ?>">
                                <input type="hidden" name="paralelo" value="<?= $modelParalelo->id ?>">
                                <input type="hidden" name="quimestre" value="<?= $tipo['tipo_quimestre_id'] ?>">
                                
                                <?php
                                    }
                                ?>
                                <br>
                                <?php
                                echo Html::submitButton('Calificar', ['class' => 'submit btn btn-primary btn-block']);

                                echo Html::endForm();
                            }else{
                                echo '<p>La calificación es de: </p>';
                                echo '<h3>'.$tipo['valor'].'</h3>';
                                echo '<p>'.$tipo['descripcion'].'</p>';
                            }
                            ?>


                            
<!--                            <hr class="my-4">
                            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p>
                            <p class="lead">
                                <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
                            </p>-->
                        </div>
                    </div>


                    <?php
                }
                ?>

            </div>





        </div>

    </div>
</div>

