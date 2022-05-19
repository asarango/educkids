<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisIntitutoDatosGenerales */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Intituto Datos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-intituto-datos-generales-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'instituto_id',
            'direccion',
            'codigo_amie',
            'telefono',
            'provincia',
            'canton',
            'parroquia',
            'correo',
            'sitio_web',
            'sostenimiento',
            'regimen',
            'modalidad',
            'niveles_curriculares',
            'subniveles',
            'distrito',
            'circuito',
            'jornada',
            'horario_trabajo',
            'local',
            'genero',
            'ejecucion_desde',
            'ejecucion_hasta',
            'financiamiento',
        ],
    ]) ?>

</div>
