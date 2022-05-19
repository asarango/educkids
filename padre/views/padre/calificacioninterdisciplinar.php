<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;
$modelLibreta = backend\models\ScholarisParametrosOpciones::find()->where(['codigo' => 'publicalib'])->one();

$this->title = 'Educandi-Portal';
?>


<div class="padre-notas">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">CALIFICACIÓN MÉTODO INTERDISCIPLINAR</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav> 

        
        
        
        <?php
            if($modelTipoCalif->valor == 1){
                ?>
        
        <div class="table table-responsive">
            <table class="table table-hover table-condensed table-striped">
                <tr>
                    <td><strong>PARCIAL</strong></td>
                    <td><strong>NOTA</strong></td>
                    <td><strong>CALIFICAR</strong></td>
                </tr>
                
                <?php
                foreach ($modelNotas as $nota){
                    echo '<tr>';
                    echo '<td>'.$nota['name'].'</td>';
                    echo '<td>'.$nota['nota'].'</td>';
//                    echo '<td>'.$nota['desde'].'</td>';
//                    echo '<td>'.$nota['hasta'].'</td>';
                    
                    
                    if(($hoy >= $nota['desde']) && ($hoy <= $nota['hasta'])){
                        echo '<td>';
                        echo Html::a('Calificar', ['calificarparcial',
                                    'alumnoId' => $modelAlumno->id, 
                                    'parcial' => $nota['id'], 
                                    'grupo_id' => $nota['grupo_id']
                                ]);
                        echo '</td>';
                    }else if($hoy > $nota['hasta']){
                        echo '<td>fuera de tiempo</td>';
                    }else{
                        echo '<td>No puede calificar todavía</td>';
                    }
                    
                    echo '</tr>';
                }
                
                ?>
                
            </table>
        </div>
        <?php
            }
        
        ?>
        
        
        
        
     

    </div>
</div>

