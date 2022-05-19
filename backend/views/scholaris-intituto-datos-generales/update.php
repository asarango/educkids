<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisIntitutoDatosGenerales */

$this->title = 'Update Scholaris Intituto Datos Generales: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Intituto Datos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-intituto-datos-generales-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
