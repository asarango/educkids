<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisIntitutoDatosGenerales */

$this->title = 'Create Scholaris Intituto Datos Generales';
$this->params['breadcrumbs'][] = ['label' => 'Scholaris Intituto Datos Generales', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-intituto-datos-generales-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
    ]) ?>

</div>
