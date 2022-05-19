<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Asignatura */

$this->title = 'Editando Área y Asignatura: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Áreas y Asignaturas', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="scholaris-mec-v2-asignatura-update">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
        ])
        ?>

    </div>
</div>