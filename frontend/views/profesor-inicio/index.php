<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opciones de profesor';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">        
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="portal-inicio-index">

    <div class="container">


        <h3><?= Html::encode($this->title) ?></h3>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>



        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">Asistencias y comportamientos</div>
                    <div class="panel-body">
                        Registro de asistencia y comportamiento de estudiantes.
                    </div>
                    
                    <div class="panel-footer">
                        <?php
                        echo Html::a('Ingresar', ['/scholaris-asistencia-profesor/index'], ['class' => 'btn btn-warning']);
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">Mis cursos</div>
                    <div class="panel-body">
                        Gestión de actividades, insumos, registro de notas.
                    </div>
                    
                    <div class="panel-footer">
                        <?php
                        echo Html::a('Ingresar', ['clases'], ['class' => 'btn btn-success']);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">Mis Planificaciones</div>
                    <div class="panel-body">
                        Gestión de planificaciones PCA / JEFES DE ÁREA.
                    </div>
                    
                    <div class="panel-footer">
                        <?php
                        echo Html::a('Ingresar', ['revisiones-pud/index1'], ['class' => 'btn btn-primary']);
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>


        

        

    </div>
</div>
