<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepPromedios */

$this->title = 'Create Scholaris Rep Promedios';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Rep Promedios', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-promedios-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
