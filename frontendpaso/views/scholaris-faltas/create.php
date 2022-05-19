<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisFaltasYAtrasosParcial */

$this->title = 'Create Scholaris Faltas Yatrasos Parcial';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Faltas Yatrasos Parcials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-faltas-yatrasos-parcial-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
