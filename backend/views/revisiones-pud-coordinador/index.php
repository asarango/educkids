<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRefuerzoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revisones PUD: '
;
?>
<div class="revisiones-pud-index">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <?php echo Html::a('Inicio', ['profesor-inicio/index']); ?>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>
    
    <div class="row">
        <div class="table table-responsive">
            <table class="table table-striped table-hover table-bordered table-condensed">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Paralelo</th>
                        <th>Materia</th>
                        <th>Profesor</th>
                        <th>Bloque</th>
                        <th>Titulo</th>
                        <th>Estado</th>
                        <th>Prof. Revisa</th>
                        <th>Observacion de rechazo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    
                        <?php
                        foreach ($modelo as $data){
                            
                            ?>
                        <tr>
                        <td><?= $data['curso'] ?></td>
                        <td><?= $data['paralelo'] ?></td>
                        <td><?= $data['materia'] ?></td>
                        
                        <td><?= $data['nomprof'].' '.$data['apeprof'] ?></td>
                        <td><?= $data['bloque']?></td>
                        <td><?= $data['titulo']?></td>
                        <td><?= $data['estado']?></td>
                        <td><?= $data['last_name'].' '.$data['x_first_name'] ?></td>
                        <?php
                            $modelCorrecion = backend\models\ScholarisPlanPudCorrecciones::find()
                                              ->where(['pud_id' => $data['id']])
                                              ->orderBy(['id' => SORT_DESC])
                                              ->limit(1)
                                              ->one();
                        ?>
                        
                        <?php
                            $modelCorrecion = backend\models\ScholarisPlanPudCorrecciones::find()
                                              ->where(['pud_id' => $data['id']])
                                              ->orderBy(['id' => SORT_DESC])
                                              ->limit(1)
                                              ->one();
                        ?>
                        
                        <td>
                            <?php 
                                if($modelCorrecion){
                                    echo $modelCorrecion->detalle_cambios;
                                }else{
                                    echo '';
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if($data['estado']=='REVISIONV' || $data['estado']=='REVISIONC'){
                                   echo Html::a('Revisar', ['correciones','pudId' => $data['id']]); 
                                }
                            ?>
                            
                        
                        </td>
                        </tr>
                        <?php
                        }
                        ?>
                    
                </tbody>
            </table>
        </div>
    </div>

    
</div>
