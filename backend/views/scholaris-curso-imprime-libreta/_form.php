<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ScholarisCursoImprimeLibreta */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="scholaris-curso-imprime-libreta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'curso_id')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'imprime')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rinde_supletorio')->dropDownList([
        '1' => 'SI',
        '0' => 'NO'
    ]) ?>
    
    <?= $form->field($model, 'tipo_proyectos')->dropDownList([
        'NOTIENE' => 'NO TIENE',
        'PROYECTOSNORMAL' => 'NORMAL',
        'PROYECTOSBLOQUE' => 'PROYECTOS EN BLOQUE',
    ]) ?>

    <?= $form->field($model, 'esta_bloqueado')->checkbox() ?>
  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
