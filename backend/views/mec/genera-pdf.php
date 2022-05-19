<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="informes-aprendizaje-genera-pdf">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

fdgdfgdgf


<?php
    if($alumno){
        $reporte = new \backend\models\InfSabanaPdfQ1P($paralelo, $alumno, $quimestre);
    }
    
?>

</div>
