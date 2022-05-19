<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisPlanPudDetalleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Detalles de Pud: ' . $modelPud->clase->curso->name . ' - ' . $modelPud->clase->paralelo->name
        . ' / ' . $modelPud->clase->profesor->last_name . ' ' . $modelPud->clase->profesor->x_first_name
        . ' / ' . $modelPud->clase->materia->name . '(' . $modelPud->clase->id . ')'
;
$this->params['breadcrumbs'][] = ['label' => 'Detalle de PUD', 'url' => ['scholaris-plan-pud/index1', 'id' => $modelPud->clase_id]];
$this->params['breadcrumbs'][] = $this->title;


//$data = ArrayHelper::map($modelDestrezas, 'destreza_codigo', 'destreza_detalle');
$data = ArrayHelper::map($modelDestrezas, 'id', 'destreza_detalle');
?>
<div class="scholaris-plan-pud-detalle-index">

    <div class="container">
        <h4><?= Html::encode($modelPud->titulo) . ' - ' ?><small>(<?= $modelPud->estado ?>)</small></h4>


        <?php
        if ($modelPud->estado == 'CONSTRUYENDOSE') {
            echo Html::a('Enviar a revision', ['revisar', 'pudId' => $modelPud->id], ['class' => 'btn btn-warning']);
            //echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
        } elseif ($modelPud->estado == 'RECHAZADO') {

            $modelRechazo = \backend\models\ScholarisPlanPudCorrecciones::find()
                    ->where(['pud_id' => $modelPud->id])
                    ->orderBy(['id' => SORT_DESC])
                    ->limit(1)
                    ->one();
            
            echo Html::a('Enviar a revision', ['revisar', 'pudId' => $modelPud->id], ['class' => 'btn btn-warning']);
            ?>
            <div class="alert alert-danger">
                <strong>Su PUD ha sido rechazado, por favor realizar los cambios solicitados en el mensaje: </strong>
                <p><?= $modelRechazo->detalle_cambios ?></p>
            </div>
            <?php
        } elseif ($modelPud->estado == 'REVISIONC') {
            ?>
            <div class="alert alert-info">
                El PUD de esta unidad se encuentra en estado de revision, en la coordinacion del area
            </div>
            <?php
            //echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
        } elseif ($modelPud->estado == 'REVISIONV') {
            ?>
            <div class="alert alert-info">
                El PUD de esta unidad se encuentra en estado de revision, en el vicerrectorado
            </div>
            <?php
        } else {
            
        }

        if($modelReporte->valor == 1){
            echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
        }elseif ($modelReporte->valor == 2) {
            echo Html::a('Generar Reporte PDF - PROV', ['reporte-pud-prov/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
        }else{
            echo Html::a('Generar Reporte PDF', ['reporte-pud/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
            echo Html::a('Generar Reporte PDF - PROV', ['reporte-pud-prov/index1', 'pudId' => $modelPud->id], ['class' => 'btn btn-danger']);
        }
        
        ?>
        


        <?php echo Html::beginForm(['ingresa-destreza', 'post']); ?>


        <?php
        echo '<label class="control-label">Seleccione la destreza:</label>';
        echo Select2::widget([
            'name' => 'codigo_deztreza',
            'value' => '',
            'data' => $data,
            'size' => Select2::SMALL,
            'options' => [
                'placeholder' => 'Seleccione destreza...',
                'onchange' => 'registra(this,"' . Url::to(['registradestreza']) . '",' . $modelPud->id . ');',
            ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
        ?>

        <?php
//        echo Html::submitButton(
//                'Crear nueva destreza',
//                ['class' => 'btn btn-success']
//        );
        ?>
        <?php echo Html::endForm(); ?>



    </div>

    <div class="container">
        <div id="destrezasDiv"></div>
    </div>



</div>

<script>

    muestra_destrezas();

    function muestra_destrezas() {
        var pud = <?= $modelPud->id ?>;

        var url = "<?= Url::to(['muestradestreza']) ?>";
        var parametros = {
            "pud": pud
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'get',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#destrezasDiv").html(response);

            }
        });

    }


    function registra(obj, url, pud) {

        var parametros = {
            "codigo": $(obj).val(),
            "pud": pud
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                //muestra_destrezas();
                location.reload();

            }
        });

    }
</script>
