<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */

$this->title = 'Cambiar clave: ' . $model->usuario;
//$this->params['breadcrumbs'][] = ['label' => 'Menus', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profesor-inicio-cambiarclave">

    <?php $form = ActiveForm::begin(); ?>

    
    <?= $form->field($model, 'clave')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Modificar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
