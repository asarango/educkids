<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMomentosAcademicos */

$this->title = 'Create Scholaris Momentos Academicos';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Momentos Academicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-momentos-academicos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
