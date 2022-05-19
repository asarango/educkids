<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OpPsychologicalAttention */

$this->title = 'Atención Psicológica #: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Atenciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="op-psychological-attention-view" style="padding-left: 50px; padding-right: 50px">


    <?php
    if ($model->state == 'open') {
        ?>
        <h1><?= Html::encode($this->title) . ' ' . Html::a('Imprimir', ['print-one', 'id' => $model->id], ['class' => 'btn btn-danger']) ?></h1>
        <?php
    } else {
        echo Html::encode($this->title);
    }
    ?>



    <p>
        <?php
        if ($model->state == 'draft') {
            echo Html::a('Actualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

            echo Html::a('Eliminar', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>


    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'attention_type_id',
                'label' => 'Tipo de Atención',
                'value' => function($model) {
                    if (isset($model->attentionType->name)) {
                        $respuesta = $model->attentionType->name;
                    } else {
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'student_id',
                'label' => 'Estudiante',
                'value' => function($model) {
                    if (isset($model->student->first_name)) {
                        $student = $model->student->first_name . ' ' . $model->student->middle_name . ' ' . $model->student->last_name;
                    } else {
                        $student = '';
                    }
                    return $student;
                }
            ],
            [
                'attribute' => 'course_id',
                'label' => 'Curso',
                'value' => function($model) {
                    if (isset($model->course->name)) {
                        $respuesta = $model->course->name;
                    } else {
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'parallel_id',
                'label' => 'Paralelo',
                'value' => function($model) {
                    if(isset($model->parallel->name)){
                        $respuesta = $model->parallel->name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            'date',
            'subject',
            [
                'attribute' => 'departament_id',
                'label' => 'Deparatamento',
                'value' => function($model) {
                    if(isset($model->departament->name)){
                        $respuesta = $model->departament->name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            'detail:ntext',
            //'attended_faculty_id',            
            [
                'attribute' => 'attended_faculty_id',
                'label' => 'Docente Atendido',
                'value' => function($model) {
                    if(isset($model->attendedFaculty->x_first_name)){
                        $respuesta = $model->attendedFaculty->x_first_name . ' ' . $model->attendedFaculty->last_name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'attended_student_id',
                'label' => 'Estudiante Atendido',
                'value' => function($model) {
                    if (isset($model->attendedStudent->first_name)) {
                        
                        $student = $model->attendedStudent->first_name . ' ' . $model->attendedStudent->middle_name . ' ' . $model->attendedStudent->last_name;
                        
                    } else {
                        $student = '';
                    }
                    return $student;
                }
            ],
            [
                'attribute' => 'attended_parent_id',
                'label' => 'Representante Atendido',
                'value' => function($model) {
                    if(isset($model->attendedParent->name0->name)){
                        $respuesta = $model->attendedParent->name0->name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'external_derivation_id',
                'label' => 'Derivación Externa',
                'value' => function($model) {
                    if(isset($model->externalDerivation->name)){
                        $respuesta = $model->externalDerivation->name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'violence_modality_id',
                'label' => 'Modalidad de Violencia',
                'value' => function($model) {
                    if (isset($model->violenceModality->name)) {
                        $respuesta = $model->violenceModality->name;
                    } else {
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'violence_type_id',
                'label' => 'Tipo de Violencia',
                'value' => function($model) {
                    if (isset($model->violenceType->name)) {
                        $respuesta = $model->violenceType->name;
                    } else {
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'violence_reason_id',
                'label' => 'Motivo',
                'value' => function($model) {
                    if (isset($model->violenceReason->name)) {
                        $respuesta = $model->violenceReason->name;
                    } else {
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
            [
                'attribute' => 'special_need_id',
                'label' => 'Necesidad Especial',
                'value' => function($model) {
                    if(isset($model->specialNeed->name)){
                        $respueste = $model->specialNeed->name;
                    }else{
                        $respueste = '';
                    }
                    return $respueste;
                }
            ],
            [
                'attribute' => 'substance_use_id',
                'label' => 'Uso de Substancias',
                'value' => function($model) {
                    if(isset($model->substanceUse->name)){
                        $respuesta = $model->substanceUse->name;
                    }else{
                        $respuesta = '';
                    }
                    return $respuesta;
                }
            ],
//            'create_date',
//            'create_uid',
//            'employee_id',
            'state',
//            'write_date',
//            'write_uid',            
            'special_attention:boolean',
            'persona_lidera',
            'agreements:ntext',
        ],
    ])
    ?>

</div>
