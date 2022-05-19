<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = 'ELIMNAR: ';
;
$this->params['breadcrumbs'][] = ['label' => 'Planificacion',
    'url' => ['planificar',
        'id' => $ambitoId,
        'quimestre' => $quimestre,
        'clase' => $clase
        ]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-plan-pud-planificar" style="padding-left: 40px; padding-right: 40px">

    <div class="alert alert-info">
        <strong>
            <?= $model->codigo_destreza . ' ' . $model->destreza_desagregada ?>
        </strong>
    </div>

    <p class="text-danger">Usted tiene calificada esta destreza.</p>
    <p class="text-danger">¿Desea eliminar las calificaciones?.</p>

    <div class="row">
        <div class="col-lg-1 col-md-1">
            <?php
            echo Html::a('SI', ['eliminar-todo-ejecuta',
                'id' => $model->id,
                
//                                                'ambitoId' => $dest->ambito_id
                    ],
                    //['class' => 'glyphicon glyphicon-pencil']);
                    ['class' => 'btn btn-danger btn-block']);

            
            ?>
        </div>
        <div class="col-lg-1 col-md-1">
            <?php
            
            echo Html::a('NO', ['planificar',
                                'id' => $ambitoId,
                                'quimestre' => $quimestre,
                                'clase' => $clase
//                                                'ambitoId' => $dest->ambito_id
                    ],
                    //['class' => 'glyphicon glyphicon-pencil']);
                    ['class' => 'btn btn-info btn-block']);
            ?>
        </div>
    </div>



</div>

