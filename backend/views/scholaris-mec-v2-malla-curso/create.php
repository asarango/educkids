<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2MallaCurso */

$this->title = 'Asignando cursos a la malla MEC: '.$modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Malla Cursos MEC', 'url' => ['index1','id' => $modelMalla->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-curso-create">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelMalla' => $modelMalla,
            'cursos' => $cursos
        ])
        ?>

    </div>
</div>
