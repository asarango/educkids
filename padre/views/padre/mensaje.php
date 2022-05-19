<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;


$this->title = 'Educandi-Portal';
?>


<div class="padre-notas">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelCurso->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">DESEMPEÑO ACADÉMICO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav>
        
        <hr>
        
        <div class="alert alert-danger">
            <?= $mensaje ?>
        </div>
        
    </div>
    
    
</div>