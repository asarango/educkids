<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
$this->title = 'Editar Material de Apoyo';
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['scholaris-tarea-inicial/index1', 'clase' => $clase, 'quimestre' => $quimestre]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="scholaris-tarea-inicial-update">

    <?= $this->render('_form', [
        'model' => $model,
        'clase' => $clase,
        'quimestre' => $quimestre,
    ]) ?>

</div>
