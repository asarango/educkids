<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Experiencia Microcurricular';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="kids-experiencia-index1">

    <div class="" style="padding-left: 40px; padding-right: 40px">

        <div class="m-0 vh-50 row justify-content-center align-items-center">
            <div class="card shadow col-lg-12 col-md-12 " style="background-color: #ccc; font-size: 12px">

                <!-- comienza encabezado -->
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <p style="color:white">
                            <?= $this->title ?>
                            |                                
                            <?=
                            Html::a('<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                                    ['site/index'], ['class' => 'link']);
                            ?>                
                            |
                            <?=
                            Html::a(
                                    '<span class="badge rounded-pill" style="background-color: #0a1f8f"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificaciones</span>',
                                    [
                                        'kids-menu/index1'
                                    ]
                            );
                            ?>    
                            |
                        </p>

                    </div>
                </div>
                <!-- Fin de encabezado -->

                <!--comienza cuerpo de documento-->
   
                <!--finaliza cuerpo de documento-->

            </div>

        </div>

    </div>
</div>