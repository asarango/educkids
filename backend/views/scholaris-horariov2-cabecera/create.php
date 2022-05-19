<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisHorariov2Cabecera */

$this->title = 'Horario Cabecera';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Horariov2 Cabeceras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-horariov2-cabecera-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'periodo' => $periodo
    ]) ?>

</div>
