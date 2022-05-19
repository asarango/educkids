<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCursoImprimeLibreta */

$this->title = 'Actualizar configuración de curso';
$this->params['breadcrumbs'][] = ['label' => 'Listado de Configuraciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-curso-imprime-libreta-update" style="padding-left: 50px; padding-right: 50px">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
