<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisAsistenciaProfesorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Registro de asistencia profesores';

?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">        
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>

<div class="scholaris-asistencia-profesor-index">

    <div class="container">

        <h3><?= Html::encode($this->title) ?></h3>
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <div class="table table-responsive">
            <table class="table-striped table-condensed table-hover table-bordered">
                <thead>
                    <tr>
                        <th>CLASE</th>
                        <th>MATERIA</th>
                        <th>CURSO</th>
                        <th>PARALELO</th>
                        <th>HORA</th>
                        <th>DESDE</th>
                        <th>HASTA</th>
                        <th>INGRESA</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    foreach ($model as $data) {
                        echo '<tr>';
                        echo '<td>' . $data['clase_id'] . '</td>';
                        echo '<td>' . $data['materia'] . '</td>';
                        echo '<td>' . $data['curso'] . '</td>';
                        echo '<td>' . $data['paralelo'] . '</td>';
                        echo '<td>' . $data['hora'] . '</td>';
                        echo '<td>' . $data['desde'] . '</td>';
                        echo '<td>' . $data['hasta'] . '</td>';
                        echo '<td>' . $data['hora_ingresa'] . '</td>';
                        
                        if($data['hora_ingresa']){
                            echo '<td>' . Html::a('Ingresar', [
                            '/comportamiento/index',
                            "id" => $data['asistencia_id']
                                ], ['class' => 'card-link']) .
                        '</td>';
                        }else{
                            echo '<td>' . Html::a('Registrar', [
                            'registrar',
                            "clase" => $data['clase_id'],
                            'hora' => $data['hora_id']
                                ], ['class' => 'card-link','onclick'=>'alerta();']) .
                        '</td>';
                        }
                        
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>




    </div>
</div>

<script>
    
    function alerta(){
        alert('Registrado exitosamente!!!');
    }
    
</script>
