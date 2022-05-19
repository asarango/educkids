<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Eliminando actividad: ' . $modelActividad->title . ' (' . $modelActividad->id . ')';

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividades', ['/profesor-inicio/actividades', "id" => $modelActividad->paralelo_id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?=$this->title?></li>
    </ol>
</nav>

<div class="scholaris-actividad-eliminar">

<div class="container">

    <p>
        
    </p>

    <?php if ($mensaje == 'NO') {?>

        <div class="alert alert-danger" role="alert">
            <p><strong>Al eliminar la actividad <?= $modelActividad->title ?> </strong></p>

            <p>Se perderán los datos de las calificaciones, y se recalcula los promedios de parcial y quimestre</p>
            <p>¿Desea eliminar la actividad?</p>

            <?=Html::a('Eliminar', ['eliminar','mensaje' => 'SI', 'id' => $modelActividad->id], ['class' => 'btn btn-danger'])?>

        </div>

    <?php } else {?>

        <div class="alert alert-success" role="alert">
            <p><strong>La actividad <?= $modelActividad->title ?>, ha sido eliminada... </strong></p>            

        </div>


    <?php }?>


</div>
</div>
