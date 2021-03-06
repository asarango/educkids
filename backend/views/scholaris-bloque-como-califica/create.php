<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueComoCalifica */

$this->title = 'Creando opción';
$this->params['breadcrumbs'][] = ['label' => 'Opciones de calificación', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-como-califica-create" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
