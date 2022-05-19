<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTomaAsistecia */

$this->title = 'Create Scholaris Toma Asistecia';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Toma Asistecias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-toma-asistecia-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'paralelo' => $paralelo
    ]) ?>

</div>
