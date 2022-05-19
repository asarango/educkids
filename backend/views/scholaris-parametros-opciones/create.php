<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisParametrosOpciones */

$this->title = 'Create Scholaris Parametros Opciones';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Parametros Opciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-parametros-opciones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
