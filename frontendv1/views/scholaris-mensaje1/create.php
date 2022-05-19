<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisMensaje1 */

$this->title = 'Create Scholaris Mensaje1';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Mensaje1s', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mensaje1-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
