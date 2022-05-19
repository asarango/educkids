<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

use backend\models\PlanArea;

use backend\models\ResPartner;

use backend\models\ScholarisInstitutoAutoridades;


/* @var $this yii\web\View */
/* @var $model backend\models\PlanPduCabecera */
/* @var $form yii\widgets\ActiveForm */

$usuario = Yii::$app->user->identity->usuario;
$fecha = date("Y-m-d H:i:s");

?>

<div class="plan-pdu-cabecera-form">

    <div class="container">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clase_id')->hiddenInput(['value' => $clase_id])->label(FALSE) ?>
    <?= $form->field($model, 'bloque_id')->hiddenInput(['value' => $bloque_id])->label(FALSE) ?>
    <?php
        if($model->isNewRecord){
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => $usuario])->label(FALSE);
        } else{
            echo $form->field($model, 'creado_por')->hiddenInput(['value' => true])->label(FALSE);
        }

    ?>
    <?php
        if($model->isNewRecord){
            echo $form->field($model, 'creado_fecha')->hiddenInput(['value' => $fecha])->label(FALSE);
        }else{
            echo $form->field($model, 'creado_fecha')->hiddenInput()->label(FALSE);
        }
    ?>
    <?= $form->field($model, 'actualizado_por')->hiddenInput(['value' => $usuario])->label(FALSE) ?>
    <?= $form->field($model, 'actualizado_fecha')->hiddenInput(['value' => $fecha])->label(FALSE) ?>




    <?php
        $lista = PlanArea::find()->all();
        $listData = ArrayHelper::map($lista, 'id', 'nombre');
        echo $form->field($model, 'asignatura_curriculo_id')->widget(Select2::className(),[
            'data' => $listData,
            'options' => [
                            'placeholder' => 'Seleccione asignatura...',
                            'onchange' => 'muestraObjetivos(this,"' . Url::to(['cajas-select/objetivos']) . '");'
                         ],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
        ]);
    ?>

    <?= $form->field($model, 'periodos')->textInput() ?>

    <?php 
    $lista = ResPartner::find()->all();
    $listData = ArrayHelper::map($lista, 'id', 'name');
    echo $form->field($model, 'coordinador_id')->widget(Select2::className(),[
        'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Coordinador...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
    ]); 
            ?>

    <?php
    $lista = ScholarisInstitutoAutoridades::find()->all();
    $listData = ArrayHelper::map($lista, 'id', 'nombre');
    echo $form->field($model, 'vicerrector_id')->widget(Select2::className(),[
        'data' => $listData,
            'options' => ['placeholder' => 'Seleccione Vicerrector...'],
            'pluginLoading' => false,
            'pluginOptions' => [
                'allowClear' => false
            ],
    ]);
    ?>

        <label class="control-label">Objetivo:</label>
        <div id="objetivoId"></div>
        
    <?= $form->field($model, 'planificacion_titulo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'objetivo_por_nivel_id')->hiddenInput()->label(FALSE) ?>

    <?= $form->field($model, 'estado')->dropDownList([
                                                        "construyendo" => 'CONSTRUYENDO',
                                                        "en_coordinador" => 'EN COORDINADOR',
                                                        "en_vicerrector" => 'EN VICERRECTOR',
                                                        "regresa_profesor" => 'REGRESA PROFESOR',
                                                        "aceptado" => 'ACEPTADO',
                                                    ]) 
    ?>



    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
<script>
    function muestraObjetivos(obj, url)
    {
//        console.log(obj);
//        console.log(url);
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
                $("#objetivoId").html(response);

            }
        });
    }
    
    
    function cambiaObjetivoId(obj){
        var parametros = {
                    "id": $(obj).val()
             }
        
        
        $("#planpducabecera-objetivo_por_nivel_id").val(parametros.id);
        
    }
    
    
</script>
