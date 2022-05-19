<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttention */

$this->title = 'Actualizar atención: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Principal', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="op-psychological-attention-update">
    
    <div class="container">
        <h3><?= Html::encode($this->title) ?></h3>
    </div>

    

    <?= $this->render('_form', [
        'model' => $model,
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider
    ]) ?>
    
  

</div>
