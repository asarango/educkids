<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisTareaInicial */
?>
<div class="scholaris-tarea-inicial-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'clase_id',
            'quimestre_codigo',
            'titulo',
            'fecha_inicio',
            'fecha_entrega',
            'nombre_archivo',
            'tipo_material',
            'link_videoconferencia',
            'respaldo_videoconferencia',
            
            'creado_por',
            'creado_fecha',
            'actualizado_por',
            'actualizado_fecha',
        ],
    ]) ?>

</div>
