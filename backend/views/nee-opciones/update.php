<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisArea */

$this->title = 'Actualizando datos de Opciones NEE: ';
$this->params['breadcrumbs'][] = ['label' => 'Nee Opciones', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';

//echo '<pre>';
//print_r($model);
//die();


?>
<div class="planificacion-opciones-update">

   <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-8 col-md-8">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/asignaturas.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                </div>
            </div><!-- FIN DE CABECERA -->




            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                        '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Volver a NEE Opciones</span>',
                        ['index'],
                        ['class' => 'link']
                    );
                    ?>

                    |
                </div> <!-- fin de menu izquierda -->

                <div class="col-lg-6 col-md-6" style="text-align: right;">
                    <!-- inicio de menu derecha -->
                </div><!-- fin de menu derecha -->
            </div>
            <!-- finaliza menu menu  -->


            <div class="row" style="margin: 20px 100px 100px 100px;">
                <?= $this->render('_form', [
                    'model' => $model
                ])
                ?>
            </div>


        </div>




    </div>
</div>
