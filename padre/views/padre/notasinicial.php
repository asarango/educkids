<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
$options = ['class' => ['btn btn-link']];
?>


<div class="padre-notas">


    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
        </ol>
    </nav> 


    <div class="alert alert-warning">
        <strong>NE</strong> = No evaluado
        <strong>; I</strong> = Iniciado
        <strong>; EP</strong> = En proceso
        <strong>; A</strong> = Adquirido
    </div>


    <h1><?= $mensaje ?></h1>

    <ul>
        <li>`
            <?=
            Html::a('<p style="color: white"><u>Descargar informe de aprendizaje - Primer quimestre</u></p>', ['informeaprendizajeinicial',
                'alumno' => $modelAlumno->id,
                'paralelo' => $modelCurso->id,
                'reporte' => 'q1inicial',
                'quimestre' => 'QUIMESTRE I'
                    ],
                    $options);
            ?>
        </li>
        <li>
            <?=
            Html::a('<p style="color: white"><u>Descargar informe de aprendizaje - Segundo quimestre</u></p>', ['informeaprendizajeinicial',
                'alumno' => $modelAlumno->id,
                'paralelo' => $modelCurso->id,
                'reporte' => 'q1inicial',
                'quimestre' => 'QUIMESTRE II'
                    ],
                    $options);
            ?>
        </li>
    </ul>
</div>