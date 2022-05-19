<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMecV2Malla */

$this->title = 'Creando Malla MEC';
$this->params['breadcrumbs'][] = ['label' => 'Mallas MEC', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-malla-create">

    <div class="container">

        <h1><?= Html::encode($this->title) ?></h1>

        <?=
        $this->render('_form', [
            'model' => $model,
            'modelPeriodo' => $modelPeriodo
        ])
        ?>

    </div>
</div>
