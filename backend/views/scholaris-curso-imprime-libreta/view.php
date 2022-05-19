<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCursoImprimeLibreta */

$this->title = 'Configuración de curso';
$this->params['breadcrumbs'][] = ['label' => 'Listado de Configuraciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="scholaris-curso-imprime-libreta-view" style="padding-left: 50px; padding-right: 50px">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'curso_id',
            'imprime',
            'rinde_supletorio',
            'esta_bloqueado'
        ],
    ]) ?>

</div>
