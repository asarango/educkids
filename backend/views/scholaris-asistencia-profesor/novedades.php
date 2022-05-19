<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Noveades de estudiantes';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">        
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index'], ['class' => 'btn btn-link']); ?>
        </li>

        <li class="breadcrumb-item">
            <?php echo Html::a('Registro docente', ['index'], ['class' => 'btn btn-link']); ?>
        </li>

        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>

<div class="scholaris-asistencia-profesor-novedades" style="padding-left: 40px; padding-right: 40px">



</div>
