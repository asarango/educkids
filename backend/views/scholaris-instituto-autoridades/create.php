<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisInstitutoAutoridades */

$this->title = 'Create Scholaris Instituto Autoridades';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Instituto Autoridades', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-instituto-autoridades-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'periodoId' => $periodoId,
    ])
    ?>

</div>
