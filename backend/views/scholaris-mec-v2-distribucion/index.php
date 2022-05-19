<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisMecV2DistribucionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Distributivo de malla: ' . $modelMalla->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Mallas: ', 'url' => ['scholaris-mec-v2-malla/index']];
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

        <p>
            <?php //Html::a('Create Scholaris Mec V2 Distribucion', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <div class="row">
            <div class="col-md-6">
                <?php
                echo '<label class="control-label">Curso para asignar:</label>';
                echo Select2::widget([
                    'name' => 'curso',
                    'value' => 0,
                    'data' => $data,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione curso',
                        'onchange' => 'detalle(this,"' . Url::to(['detalle']) . '",' . $modelMalla->id . ');',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
            
            
            <div class="col-md-6">
                <?php
                echo '<label class="control-label">Cursos asignados:</label>';
                echo Select2::widget([
                    'name' => 'curso',
                    'value' => 0,
                    'data' => $dataD,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Seleccione curso',
                        'onchange' => 'detalle(this,"' . Url::to(['detalle']) . '",' . $modelMalla->id . ');',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
        </div>




        <hr>

        <div id="detalle"></div>

    </div>
</div>

<script>

    function detalle(obj, url, malla) {
        var parametros = {
            "curso": $(obj).val(),
            "malla": malla
        };

        $.ajax({
            data: parametros,
            url: url,
            type: 'POST',
            beforeSend: function () {},
            success: function (response) {
                $("#detalle").html(response);
            }
        });
    }
</script>

