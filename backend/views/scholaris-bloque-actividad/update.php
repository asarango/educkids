<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = 'Editando Bloques de Unidad - Parciales: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Lista de Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-bloque-actividad-update" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'instituto' => $instituto,
        'modelComoCalifica' => $modelComoCalifica
    ]) ?>

</div>
