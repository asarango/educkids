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

    <nav aria-label="breadcrumb" class="tamano12">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= Url::to(['alumno', 'id' => $modelAlumno->id, 'paralelo' => $modelParalelo->id]) ?>">Volver</a></li>                
            <li class="breadcrumb-item"><a href="<?= Url::to(['site/index']) ?>">Inicio</a></li>                
            <li class="breadcrumb-item active" aria-current="page">CALIFICACIÓN MÉTODO DISCIPLINAR</li>
            <li class="breadcrumb-item active" aria-current="page"><?= $modelAlumno->first_name . ' ' . $modelAlumno->middle_name . ' ' . $modelAlumno->last_name ?></li>
        </ol>
    </nav>


    <div style="padding-left: 40px; padding-right: 40px;" class="text-center">
        <div class="table table-responsive shadow-lg" style="padding: 30px">
            <table class="table table-condensed table-bordered table-striped table-hover" style="font-size: 10px; width: 100%;">
                <tr>
                    <td><strong>PARCIAL</strong></td>
                    <td><strong>TIPO PARCIAL</strong></td>
                    <td><strong>ACCIÓN</strong></td>
                </tr>

                <?php
                foreach ($modelBloques as $bloque) {
                    $fechaLimite = date("Y-m-d", strtotime($bloque->hasta . "- 5 days"));
                    ?>
                    <tr>
                        <td><?= $bloque->name ?></td>
                        <td><?= $bloque->tipo_bloque ?></td>
                        <td>
                            <?php
                            if (($hoy >= $fechaLimite) && ($hoy <= $bloque->hasta)) {
                                // echo '<td>';
                                echo Html::a('Calificar', ['calificarparcialdisciplinar',
                                    'alumnoId' => $modelAlumno->id,
                                    'paraleloId' => $modelParalelo->id,
                                    'bloqueId' => $bloque->id,
                                ]);
                                // echo '</td>';
                            } else if ($hoy > $bloque->hasta) {
                                echo Html::a('<i class="fas fa-angle-double-right"></i> Ver notas', ['calificarparcialdisciplinar',
                                    'alumnoId' => $modelAlumno->id,
                                    'paraleloId' => $modelParalelo->id,
                                    'bloqueId' => $bloque->id,
                                ]);
                            } else {
                                echo 'No puede calificar todavía';
                            }
                            ?>
                        </td>
                    </tr>

                    <?php
                }
                ?>

            </table>
        </div>
    </div>
</div>

