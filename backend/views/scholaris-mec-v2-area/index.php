<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2AreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MEC: '.$modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'MALLAS MEC', 'url' => ['scholaris-mec-v2-malla/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-mec-v2-area-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Crear Area MEC', ['create','mallaId' => $modelMalla->id], ['class' => 'btn btn-success']) ?>
    </p>

    <hr>
    
    
    <div class="container">
        <div class="row">
            <div class="col-md-6">ÁREA</div>
            <div class="col-md-6">ASIGNATURAS</div>
        </div>
        <hr>
        
        <?php
        foreach ($modelAreas as $area){
            ?>
        
        <div class="row">
            <div class="col-md-6">
                <?= $area->nombre ?>
                <p><?= Html::a('Agregar Asignatura', ['scholaris-mec-v2-materia/create','areaId' => $area->id], ['class' => 'btn btn-primary']) ?></p>              
            </div>
            <div class="col-md-6">
                
                <?php
                    $modelMaterias = \backend\models\ScholarisMecV2Materia::find()->where(['malla_area_id' => $area->id])->all();
                    foreach ($modelMaterias as $mat){
                        echo '<li>'.Html::a('', ['scholaris-mec-v2-materia/eliminar','materiaId' => $mat->id], ['class' => 'btn btn-link glyphicon glyphicon-trash']).$mat->nombre.'</li>';
                    }
                ?>
                
            </div>
        </div>
        <hr>
        
        <?php
        }
        
        ?>
        
    </div>
    
</div>
