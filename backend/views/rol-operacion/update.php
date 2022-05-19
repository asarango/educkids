<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\RolOperacion */

$this->title = 'Update Rol Operacion: ' . $model->rol_id;
$this->params['breadcrumbs'][] = ['label' => 'Rol Operacions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->rol_id, 'url' => ['view', 'rol_id' => $model->rol_id, 'operacion_id' => $model->operacion_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rol-operacion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
