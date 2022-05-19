<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisRepLibreta */

$this->title = 'Create Scholaris Rep Libreta';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Rep Libretas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-rep-libreta-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
