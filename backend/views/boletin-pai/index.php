<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Configuración de Áreas PAI';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <?php echo Html::a('Inicio', ['/profesor-inicio/index']); ?>
        </li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Mis clases', ['/profesor-inicio/clases']); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>
<div class="boletin-pai-index" style="padding-left: 40px; padding-right: 40px;">

    <div class="row">
        <div class="col-lg-4 col-md-4 text-center">
            Actuales
            <div class="table table-responsive">
                <table class="table table-striped table-hover">
                    <tr>
                        <td align="center"><strong>Código</strong></td>
                        <td align="center"><strong>Área</strong></td>
                        <td align="center"><strong>Total Criterios</strong></td>
                    </tr>

                    <?php
                    foreach ($modelAreasActuales as $actual) {
                        ?>
                        <tr>
                            <td align="center"><?= $actual['id'] ?></td>
                            <td align="left"><?= $actual['name'] ?></td>
                            <td align="center"><?= $actual['total'] ?></td>
                        </tr>
                        <?php
                    }
                    ?>

                </table>
            </div>
        </div>

        <div class="col-lg-1 col-md-1 text-center"></div>

        <div class="col-lg-7 col-md-7 text-center">
            Anteriores

            <div class="row">
                <?php
                $listData = ArrayHelper::map($modelAreasAnterior, 'id', 'name');

                echo '<label class="control-label">Área:</label>';
                echo Select2::widget([
                    'name' => 'anterior',
                    'value' => 0,
                    'data' => $listData,
                    'size' => Select2::SMALL,
                    'options' => [
                        'placeholder' => 'Buscar área',
                        'onchange' => 'mostrarCriterios(this,"' . Url::to(['criterios']) . '");',
                    ],
                    'pluginLoading' => false,
                    'pluginOptions' => [
                        'allowClear' => false
                    ],
                ]);
                ?>
            </div>
            
            <hr>
            
            <div class="table table-responsive" id="criterios"></div>


        </div>
    </div>

</div>

<script>
    function mostrarCriterios(obj, url)
    {
        //var instituto = $(obj).val();
        var parametros = {
            "id": $(obj).val(),
        };

        $.ajax({
            data:  parametros,
            url:   url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                $("#criterios").html(response);

            }
        });
    }
</script>