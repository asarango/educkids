<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use backend\models\Rol;

use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsuarioSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="usuario-profesor">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        
    </p>

    <div class="container">
         <?php echo Html::beginForm(['profesores', 'post']); ?>

    <?php
    $listData = ArrayHelper::map($modelRol, 'id', 'rol');

    echo '<label class="control-label">Perfil:</label>';
    echo Select2::widget([
        'name' => 'perfil',
        'value' => 0,
        'data' => $listData,
        'size' => Select2::SMALL,
        'options' => [
            'placeholder' => 'Seleccione Perfil',
//            'onchange' => 'CambiaParalelo(this,"' . Url::to(['paralelos']) . '");',
        ],
        'pluginLoading' => false,
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]);


    ?>
    <br>
    <?php
        echo Html::submitButton(
                'Aceptar',
                ['class' => 'btn btn-primary']
        );
    ?>
    <?php echo Html::endForm(); ?>
    
        

    </div>
</div>
