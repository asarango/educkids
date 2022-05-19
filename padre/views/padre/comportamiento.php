<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
?>


<div class="padre-comportamiento">

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $paralelo]) ?>">Volver</a></li>                
                <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
                <li class="breadcrumb-item active" aria-current="page">COMPORTAMIENTO EN EL AÑO LECTIVO</li>
                <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
            </ol>
        </nav> 
        
        
        
        
        
        
        <div class="row">            
            
            <div class="table table-responsive tamano10P">
                <table class="table table-hover table-bordered table-striped" id="tabla">
                        <thead>
                            <tr>
                                <th>FECHA</th>
                                <th>ASIGNATURA</th>
                                <th>PROFESOR</th>
                                <th>TIPO</th>
                                <th>CODIGO</th>
                                <th>DETALLE COMPORTAMIENTO</th>
                                <th>OBSERVACIÓN</th>
                                <th>JUSTIFICACION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            foreach ($modelActi as $actividad){
                                echo '<tr>';
                                echo '<td>'.$actividad['fecha'].'</td>';
                                echo '<td>'.$actividad['materia'].'</td>';
                                echo '<td>'.$actividad['last_name'].' '.$actividad['x_first_name'].'</td>';                                
                                echo '<td class="alinearIzquierda">'.$actividad['tipo'].'</td>';
                                echo '<td>'.$actividad['codigo'].'</td>';
                                echo '<td>'.$actividad['detalle'].'</td>';
                                echo '<td>'.$actividad['observacion'].'</td>';
                                echo '<td>'.$actividad['motivo_justificacion'].'</td>';
                                echo '</tr>';
                            }
                            
                            ?>
                        </tbody>
                    </table>
                
            </div>
                   
        </div>
        

    </div>
</div>
<br>
<br>
<br>
<br>
<script src="jquery/jquery18.js"></script>
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->
<script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
<script>
    
//    $(document).ready( function () {
//    $('#table_id').DataTable();
//} );
    
    hola();
    
    function hola(){
        console.log('ola k ase');
        $("#tabla").DataTable();
//        $('#tabla').DataTable();
    }
    
    
    

</script>