<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanificacionDesagregacionCabeceraSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$condicionClass = new backend\models\helpers\Condiciones;


$this->title = $planUnidad->unit_title . ' (' . $planUnidad->curriculoBloque->last_name . ')'.' - TEMARIO' ;
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($planUnidad);
//die();
$estado = $planUnidad->planCabecera->estado;
$isOpen = $planUnidad->is_open;
$condicion = $condicionClass->aprobacion_planificacion($estado,$isOpen,$planUnidad->settings_status);

//echo $condicion;
//die();

?>

<!-- JS y CSS Ckeditor -->
<script src="https://cdn.ckeditor.com/4.17.1/full/ckeditor.js"></script>


<div class="planificacion-desagregacion-cabecera-index">

    <div class="m-0 vh-50 row justify-content-center align-items-center">
        <div class="card shadow col-lg-12 col-md-12">
            <div class=" row align-items-center p-2">
                <div class="col-lg-1">
                    <h4><img src="ISM/main/images/submenu/herramientas-para-reparar.png" width="64px" style="" class="img-thumbnail"></h4>
                </div>
                <div class="col-lg-11">
                    <h4><?= Html::encode($this->title) ?></h4>
                    <small>
                        (
                        <?=
                        $planUnidad->planCabecera->ismAreaMateria->materia->nombre . ' - ' 
                        . $planUnidad->planCabecera->ismAreaMateria->mallaArea->periodoMalla->malla->opCourseTemplate->name
                        ?>
                        )
                    </small>
                </div>
            </div><!-- FIN DE CABECERA -->


            <!-- inicia menu  -->
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <!-- menu izquierda -->
                    |
                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #9e28b5"><i class="fa fa-briefcase" aria-hidden="true"></i> Inicio</span>',
                            ['site/index'],
                            ['class' => 'link']
                    );
                    ?>

                    |

                    <?=
                    Html::a(
                            '<span class="badge rounded-pill" style="background-color: #65b2e8"><i class="fa fa-briefcase" aria-hidden="true"></i> Planificación Temas</span>',
                            ['index1', 'id' => $planUnidad->plan_cabecera_id],
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

            <!-- inicia cuerpo de card -->
            <div class="row" style="margin: 25px;">

                <!-- Columna que muestra subtitulo1 y subtitulo2 -->
                <div class="col-lg-3 col-md-3">
                    <div style="text-align: end">
                        <h5 style="text-align: center">
                            
<!--                            Condición si está el bloque cerrado (Es Abierto) 
                            -- si el bloque estado (planUnidad->settings_status) está configurado 
                            -- y si está la cabecera estado aprobada o en coordinacion
-->
                            <?php
                            
                            if ($condicion == false) {
                                
                            } else {
                                ?>
                                <!-- Botón Modal Agregar Título -->
                                <button type="button" title="Agregar tema" class="btn" data-bs-toggle="modal" data-bs-target="#agregarTituloModal">
                                    <i class="fas fa-plus-square" style="color: green" ></i>
                                </button>

                                <?php
                            }
                            ?>
                            Temas de <?= $planUnidad->unit_title ?>
                        </h5>
                    </div>

                    <hr>

                    <!-- Muestra titulo -->
                    <div>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <?php
                            foreach ($subtitulos as $subtitulo) {
                                ?>

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">

                                        <!--MUESTRA ACORDIÓN DE TITULOS-->
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-<?= $subtitulo['id'] ?>" aria-expanded="true" aria-controls="flush-collapseOne">
                                            <?php
                                            if ($condicion == false) {
                                                echo $subtitulo['orden'] . ' - ' . $subtitulo['subtitulo'];
                                            } else {
                                                ?>


                                                <!-- Boton Modal Editar Titulo -->
                                                <a type="button" class="btn" data-bs-toggle="modal" data-bs-target="#me<?= $subtitulo['id'] ?>">
                                                    <i class="fas fa-pencil-alt" style="color: #0a1f8f " ></i>
                                                </a>


                                                <!-- Boton Modal Eliminar Titulo -->
                                                <a type="button" class="btn" data-bs-toggle="modal" data-bs-target="#m<?= $subtitulo['id'] ?>">
                                                    <i class="fas fa-trash-alt" style="color: #ab0a3d" ></i>                                                
                                                </a>



                                                <strong class="my-text-medium"><?= $subtitulo['orden'] . ' - ' . $subtitulo['subtitulo'] ?> </strong>

                                                <?php
                                            }
                                            ?>
                                        </button>


                                    </h2>
                                    <div id="flush-<?= $subtitulo['id'] ?>" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                        <div class="accordion-body" style="border: solid 1px #e7f1ff" >
                                            <div class="div-subtitulo-2" style="text-align: end">

                                                <?php
                                                if ($condicion == false) {
                                                    
                                                } else {
                                                    ?>
                                                    <!-- Boton Modal Agregar SUBTITULO -->
                                                    <a type="button" class="btn my-text-medium" data-bs-toggle="modal" data-bs-target="#sb<?= $subtitulo['id'] ?>">
                                                        <i class="fas fa-plus-square" style="color: #65b2e8 " title="Agregar Subtitulo" > Agregar subtítulo</i>
                                                    </a> 
                                                    <?php
                                                }
                                                ?>

                                            </div>

                                            <?php
                                            $subtitulo2 = busca_subtitulos2($subtitulo['id']);

                                            foreach ($subtitulo2 as $subtitulo2) {
                                                ?>
                                                <ul class="list-group">
                                                    <li class="my-text-medium list-group-item d-flex justify-content-between align-items-start">
                                                        <div class="ms-2 me-auto">
                                                            <div class="fw-bold">
                                                                <?= $subtitulo2['orden'] . ' - ' . $subtitulo2['contenido'] ?>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($condicion == false) {
                                                        } else {
                                                            ?>
                                                            <!--Boton Modal Eliminar Subtitulo2-->
                                                            <a type="button" data-bs-toggle="modal" data-bs-target="#sbE<?= $subtitulo2['id'] ?>">
                                                                <span class="badge rounded-pill" style="font-size: 12px">
                                                                    <i class="far fa-trash-alt" style="color: #ab0a3d" ></i>
                                                                </span>
                                                            </a>
                                                            <?php
                                                        }
                                                        ?>



                                                        <!-- Modal Eliminar SUBTITULO2-->
                                                        <div class="modal fade" id="sbE<?= $subtitulo2['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">Eliminar "<?= $subtitulo2['contenido'] ?>"</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body alert alert-warning" role="alert" style="margin: 20px" >
                                                                        <h6>Usted va a borrar contenidos de <strong>"<?= $subtitulo2['contenido'] ?>"</strong></h6>
                                                                        <h6>¿Desea eliminar?</h6>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                                        <?=
                                                                        Html::a(
                                                                                'Eliminar',
                                                                                ['delete-subtitle2', 'id' => $subtitulo2['id']],
                                                                                ['class' => 'btn btn-warning']
                                                                        );
                                                                        ?>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                                <?php
                                            }
                                            ?>

                                        </div>
                                    </div>





                                    <!-- Modal Agregar SUBTITULO-->
                                    <div class="modal fade" id="sb<?= $subtitulo['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Agregando subtitulo de "<?= $subtitulo['subtitulo'] ?>"</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <?php
                                                    $form = ActiveForm::begin(['action' => ['create-subtitle2'],
                                                                'method' => 'post',]);
                                                    $modelS2 = new backend\models\PlanificacionBloquesUnidadSubtitulo2();
                                                    ?>

                                                    <?= $form->field($modelS2, 'plan_unidad_id')->hiddenInput(['value' => $subtitulo['plan_unidad_id']])->label(false) ?>
                                                    <?= $form->field($modelS2, 'subtitulo_id')->hiddenInput(['value' => $subtitulo['id']])->label(false) ?>
                                                    <?= $form->field($modelS2, 'contenido')->textInput(['required'=>''])->label('Subtitulo') ?>
                                                    <?= $form->field($modelS2, 'orden')->textInput(['required'=>''])->label('Orden') ?>

                                                    <br>
                                                    <div class="form-group" style="text-align: end" >
                                                        <?= Html::submitButton('Crear', ['class' => 'btn btn-success']) ?>
                                                    </div>

                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal Editar Titulo -->
                                    <div class="modal fade" id="me<?= $subtitulo['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-xl">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel"><?= $subtitulo['subtitulo'] ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    $form = ActiveForm::begin(['action' => ['update-subtitle'],
                                                                'id' => $subtitulo['id'],
                                                                'method' => 'post',]);
                                                    ?>

                                                    <?= $form->field($subtitulo, 'id')->hiddenInput()->label(false) ?>
                                                    <?= $form->field($subtitulo, 'subtitulo')->textInput(['required'=>''])->label('Título') ?>
                                                    <?= $form->field($subtitulo, 'orden')->textInput(['required'=>''])->label('Orden') ?>
                                                    

                                                    <hr>
                                                    <div class="form-group">
                                                        <label for="experiencia">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA:</label>
                                                        <textarea name="experiencia_update" id="experiencia-editor-update<?= $subtitulo->id ?>" ><?= $subtitulo->experiencias ?></textarea>
                                                        <script>
                                                            CKEDITOR.replace("experiencia-editor-update<?= $subtitulo->id ?>", {
                                                                customConfig: "/ckeditor_settings/config.js"
                                                            });
                                                        </script>
                                                    </div>

                                                    <hr>
                                                    <div class="form-group">
                                                        <label for="experiencia">EVALUACIÓN FORMATIVAS:</label>
                                                        <textarea name="evaluacion_update" id="evaluacion-editor-update<?= $subtitulo->id ?>" ><?= $subtitulo->evaluacion_formativa ?></textarea>
                                                        <script>
                                                            CKEDITOR.replace("evaluacion-editor-update<?= $subtitulo->id ?>", {
                                                                customConfig: "/ckeditor_settings/config.js"
                                                            });
                                                        </script>
                                                    </div>

                                                    <hr>
                                                    <div class="form-group">
                                                        <label for="experiencia">DIFERENCIACIÓN:</label>
                                                        <textarea name="diferenciacion_update" id="diferenciacion-editor-update<?= $subtitulo->id ?>" ><?= $subtitulo->diferenciacion ?></textarea>
                                                        <script>
                                                            CKEDITOR.replace("diferenciacion-editor-update<?= $subtitulo->id ?>", {
                                                                customConfig: "/ckeditor_settings/config.js"
                                                            });
                                                        </script>
                                                    </div>     

                                                    <br>
                                                    <div class="form-group">
                                                        <?= Html::submitButton('Actualizar', ['class' => 'btn btn-primary']) ?>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal Borrar Titulo -->
                                    <div class="modal fade" id="m<?= $subtitulo['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body alert alert-danger" role="alert" style="margin: 20px" >
                                                    <h6>Usted va a borrar contenidos de <strong>"<?= $subtitulo['subtitulo'] ?>"</strong></h6>
                                                    <h6>¿Desea eliminar?</h6>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                    <?=
                                                    Html::a(
                                                            'Eliminar',
                                                            ['delete-subtitle', 'id' => $subtitulo['id']],
                                                            ['class' => 'btn btn-danger']
                                                    );
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <?php
                            }
                            ?>
                        </div>

                    </div>
                </div>

                <!--Columna donde muestra contenido-->
                <div class="col-lg-9 col-md-9">
                    <div style="text-align: center">
                        <h5>TEMARIO</h5>
                    </div>
                </div>
            </div>
            <!-- fin cuerpo de card -->
        </div>
    </div>

</div>







<!-- Modal Crear Titulo -->
<div class="modal fade" id="agregarTituloModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Título</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'plan_unidad_id')->hiddenInput(['value' => $planUnidad->id])->label(false) ?>

                <?= $form->field($model, 'subtitulo')->textInput()->label() ?>

                <?php
                    $totalSubtitutlos = count($subtitulos) + 1;            
                    echo $form->field($model, 'orden')->textInput(['value' => $totalSubtitutlos, 'required' => ''])->label() 
                ?>

                <hr>
                <div class="form-group">
                    <label for="experiencia">EXPERIENCIAS DE APRENDIZAJE Y ESTRATEGIAS DE ENSEÑANZA:</label>
                    <textarea name="experiencia" id="experiencia-editor" ></textarea>
                    <script>
                        CKEDITOR.replace("experiencia-editor", {
                            customConfig: "/ckeditor_settings/config.js"
                        });
                    </script>
                </div>

                <hr>
                <div class="form-group">
                    <label for="experiencia">EVALUACIÓN FORMATIVAS:</label>
                    <textarea name="evaluacion" id="evaluacion-editor" ></textarea>
                    <script>
                        CKEDITOR.replace("evaluacion-editor", {
                            customConfig: "/ckeditor_settings/config.js"
                        });
                    </script>
                </div>

                <hr>
                <div class="form-group">
                    <label for="experiencia">DIFERENCIACIÓN:</label>
                    <textarea name="diferenciacion" id="diferenciacion-editor" ></textarea>
                    <script>
                        CKEDITOR.replace("diferenciacion-editor", {
                            customConfig: "/ckeditor_settings/config.js"
                        });
                    </script>
                </div>               

                <br>
                <div class="form-group" style="text-align: end">
                    <?= Html::submitButton('Agregar', ['class' => 'btn btn-primary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

        </div>
    </div>
</div>


<!--Funciones PHP-->
<?php

function busca_subtitulos2($subtituloId) {
    $model = backend\models\PlanificacionBloquesUnidadSubtitulo2::find()->where([
                'subtitulo_id' => $subtituloId
            ])
            ->orderBy('orden')
            ->all();

    return $model;
}
?>