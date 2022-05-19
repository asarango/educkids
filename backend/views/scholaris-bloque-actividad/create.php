<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisBloqueActividad */

$this->title = 'Bloque de Unidad - Parciales';
$this->params['breadcrumbs'][] = ['label' => 'Parciales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-bloque-actividad-create" style="padding-left: 40px; padding-right: 40px">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'instituto' => $instituto,
        'modelComoCalifica' => $modelComoCalifica
    ]) ?>

</div>
