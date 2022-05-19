<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRefuerzo */

$this->title = 'Refuerzos Académicos: Clase:' . $model->grupo->clase->id
        .' / '.$model->grupo->clase->materia->name
        .' / '.$model->grupo->clase->profesor->last_name.' '.$model->grupo->clase->profesor->x_first_name
        .' / Calificación: '.$model->promedio_normal
        ;
//$this->params['breadcrumbs'][] = ['label' => 'Scholaris Refuerzos', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-refuerzo-update">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item">
                <?php echo Html::a('Regresar', ['refuerzo', "grupo" => $model->grupo_id, 'bloque' => $model->bloque_id]); ?>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>

    
    <h3>PROMEDIO NORMAL: <?= $model->promedio_normal ?></h3>
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
