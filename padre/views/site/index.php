<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Educandi-Portal';

//$link1 = 'http://'.$db.'.dic-integralis360.com/web/image?model=op.student&id=';
//$link2 = '&field=photo&unique=20210405173009';
//$link2 = '&field=image&unique=20210405173009';
?>


<div class="site-index" style="">


    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Detalle de Estudiantes</li>
        </ol>
    </nav>


    <div class="row" style="margin-top: 50px">
        <div class="col-lg-3 col-md-3"></div>
        <div class="col-lg-6 col-md-6">

            <?php
            foreach ($hijos as $hijo) {
                $id = $hijo['id'];
                $paraleloId = $hijo['paralelo_id'];
                $pathFoto = $modelFotos->nombre . $id . $modelFotosPath2->nombre;
                ?>

                <div class="card shadow">
                    <div class="row" style="padding: 10px">
                        <div class="col-lg-3 col-md-3">
                            <img class="card-img-top" src="<?= $pathFoto ?>" alt="estudiante">
                        </div>
                        <div class="col-lg-9 col-md-9">
                            <p class="card-text">
                            <h4><?= $hijo['last_name'] . ' ' . $hijo['first_name'] . ' ' . $hijo['middle_name'] ?></h4>
                            </p>

                            <hr>

                            <p class="">
                            <h6><?= ucwords(strtolower($hijo['curso'] . ' "' . $hijo['paralelo'] . '" ' . $hijo['seccion'])) ?></h6>
                            </p>                           

                            <a href="<?= Url::to(['padre/alumno', 'id' => $id, 'paralelo' => $paraleloId]) ?>" class="primary-btn"><span>Ver más <i class="fas fa-angle-double-right"></i></span></a>
                        </div>
                    </div>
                </div>

                <br>

                <?php
            }
            ?>


        </div>
        <div class="col-lg-3 col-md-3"></div>

    </div>
</div>
