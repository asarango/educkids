<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Menús';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index" style="padding-left: 10px; padding-right: 10px">


    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="panel-title">
                    
                    <div class=""><?= Html::a('<i class="fa fa-plus-circle" aria-hidden="true"></i> Nuevo', ['create'], ['class' => 'btn btn-link']) ?></div>
                </div>
            </div>                            
        </div>
        <div class="panel-body">
            <p>
                
            </p>
            <hr>

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'id',
                    'codigo',
                    'nombre',
                    'orden',
                    'icono',
                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            ?>

        </div>
    </div>

</div>
