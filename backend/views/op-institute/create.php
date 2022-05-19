<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\OpInstitute */

$this->title = 'Create Op Institute';
$this->params['breadcrumbs'][] = ['label' => 'Op Institutes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="op-institute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
