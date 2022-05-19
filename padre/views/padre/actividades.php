<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$sentencia1 = new \backend\models\SentenciasRepLibreta2();
$usuario = Yii::$app->user->identity->usuario;

$this->title = 'Educandi-Portal';
?>


<div class="padre-actividades">

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $paralelo]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">ACTIVIDADES DE DESEMPEÑO DEL AÑO LECTIVO</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
        </ol>
    </nav>



    <div style="padding-left: 40px; padding-right: 40px">

        <div class="card shadow-lg" style="padding: 30px">

            <div class="row">
                <div class="col-lg-4">
                    <center>
                        <div class="card shadow" style="">                                                            
                            <!--<img class="card-img-top" src="imagenes/instituto/padre/nino1.jpg" alt="estudiante">-->
                            <img class="card-img-top" src="imagenes/educandi/pasado.jpeg" alt="estudiante">
                            <div class="card-body">
                                <!--<h5 class="card-title">Estudiante: </h5>-->
                                <p class="card-text">
                                
                                    <a href="<?php
                                    echo Url::to(['detalle-actividades',
                                        'id' => $modelAlumno->id,
                                        'paralelo' => $paralelo,
                                        'tiempo' => 'anterior'
                                    ])
                                    ?>" class="primary-btn">
                                        <span>ANTERIORES</span>
                                    </a>                        
                              
                                </p>
                            </div>
                        </div>
                    </center>
                </div>

                <div class="col-lg-4">
                    <center>
                        <div class="card shadow" style="">                                                            
                            <!--<img class="card-img-top" src="imagenes/instituto/padre/nino1.jpg" alt="estudiante">-->
                            <img class="card-img-top" src="imagenes/educandi/presente.jpeg" alt="estudiante">
                            <div class="card-body">
                                <!--<h5 class="card-title">Estudiante: </h5>-->
                                <p class="card-text">
                                
                                    <a href="<?php
                                    echo Url::to(['detalle-actividades',
                                        'id' => $modelAlumno->id,
                                        'paralelo' => $paralelo,
                                        'tiempo' => 'ahora'
                                    ])
                                    ?>" class="primary-btn">
                                        <span>PARA HOY</span>
                                    </a>                        
                                          
                                </p>


                            </div>


                        </div>
                    </center>
                </div>

                <div class="col-lg-4">
                    <center>
                        <div class="card shadow" style="">                                                            
                            <!--<img class="card-img-top" src="imagenes/instituto/padre/nino1.jpg" alt="estudiante">-->
                            <img class="card-img-top" src="imagenes/educandi/futuro.jpeg" alt="estudiante">
                            <div class="card-body">
                                <!--<h5 class="card-title">Estudiante: </h5>-->
                                <p class="card-text">
                                
                                    <a href="<?php
                                    echo Url::to(['detalle-actividades',
                                        'id' => $modelAlumno->id,
                                        'paralelo' => $paralelo,
                                        'tiempo' => 'futuro'
                                    ])
                                    ?>" class="primary-btn">
                                        <span>POSTERIORES</span>
                                    </a>                        
                                             
                                </p>

                            </div>


                        </div>
                    </center>
                </div>
            </div>
        </div>

    </div>


</div>
<script src="jquery/jquery18.js"></script>
<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>-->
<script type="text/javascript" charset="utf8" src="DataTables/datatables.js"></script>
<script>

//    $(document).ready( function () {
//    $('#table_id').DataTable();
//} );

    hola();

    function hola() {
//        console.log('ola k ase');
        $("#tabla").DataTable({
            "order": [[1, "desc"]]
        });
//        $('#tabla').DataTable();
    }




</script>

<?php

function totalArchivo($actividad) {
    $con = Yii::$app->db;
    $query = "select 	count(id) as total
                    from	scholaris_archivosprofesor
                    where	idactividad = $actividad;";
    $res = $con->createCommand($query)->queryOne();
    return $res['total'];
}
?>