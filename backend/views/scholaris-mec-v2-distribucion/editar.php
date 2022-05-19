<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2DistribucionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Distributivo de malla: ' . $modelMalla->nombre
        . ' / ' . $modelCurso->name
;
$this->params['breadcrumbs'][] = $this->title;


$periodo = Yii::$app->user->identity->periodo_id;
$instituto = Yii::$app->user->identity->instituto_defecto;

$sentencias = new backend\models\SentenciasCursos();
$modelCursos = $sentencias->get_cursos($periodo, $instituto);
$data = ArrayHelper::map($modelCursos, 'id', 'name');


$modelCursosD = $sentencias->get_cursos_distribucion_mec($modelMalla->id);
$dataD = ArrayHelper::map($modelCursosD, 'id', 'name');
?>
<div class="scholaris-mec-v2-distribucion-index">

    <div class="container">

        <?php
        foreach ($modelMaterias as $mat) {
            ?>

            <div class="row"><u><?= $mat->id . ' ' . $mat->nombre ?></u></div>
            <div class="row">
                <?php echo Html::beginForm(['create', 'post']); ?>

                <div class="col-md-3">
                    <?php
                    echo Select2::widget([
                        'name' => 'tipo',
                        'value' => 0,
                        'data' => ['AREA' => 'AREA', 'ASIGNATURA' => 'ASIGNATURA'],
                        'size' => Select2::SMALL,
                        'options' => [
                            'placeholder' => 'Seleccione tipo de resurso...',
                        'onchange' => 'recurso(this,"' . Url::to(['recurso']) . '",'.$mat->id.','.$modelCurso->id.');',
                        ],
                        'pluginLoading' => false,
                        'pluginOptions' => [
                            'allowClear' => false
                        ],
                    ]);
                    ?>
                </div>
                
                <div class="col-md-3" id="source">
                   
                </div>


                <?php echo Html::submitButton('Aceptar', ['class' => 'btn btn-primary']); ?>
                <?php echo Html::endForm(); ?>
            </div>
            <hr>
            <?php
        }
        ?>

    </div>
</div>

<script>

    function recurso(obj, url, materia,curso) {
        var parametros = {
            "tipo": $(obj).val(),
            "materia": materia,
            "curso": curso
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#source").html(response);
            }
        });
    }
    
</script>

