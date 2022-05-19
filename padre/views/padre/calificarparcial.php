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
                <li class="breadcrumb-item"><a href="<?= Url::to(['calificacionpadre', 'id' => $modelAlumno->alumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->alumno->first_name . ' ' . $modelAlumno->alumno->middle_name . ' ' . $modelAlumno->alumno->last_name ?></li>
            </ol>
        </nav> 


        <div class="jumbotron">
            <strong>Calificando parcial: </strong><?= $modelBloque->name ?>

            <?php

            echo Html::beginForm(['calificarparcial'], 'post');
            ?>


            <p>Seleccionar calificación:</p>


            <?php
            foreach ($modelRubrica as $rubrica) {
                ?>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="rubrica_calificacion" id="exampleRadios1" value="<?= $rubrica->id ?>" required="">
                    <label class="form-check-label" for="exampleRadios1">
                        <?= '( ' . $rubrica->valor . ' ) ' . $rubrica->descripcion ?>
                    </label>
                </div>

                <input type="hidden" name="grupoId" value="<?= $modelAlumno->id ?>">
                <input type="hidden" name="bloqueId" value="<?= $modelBloque->id ?>">
                

                <?php
            }
            ?>
            <br>
            <?php
            echo Html::submitButton('Calificar', ['class' => 'submit btn btn-primary btn-block']);

            echo Html::endForm();
            ?>


        </div>


    </div>
</div>

