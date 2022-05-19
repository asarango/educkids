<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PlanPlanificacionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sitio en contrucción' ;
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-under">



    <div class="m-0 vh-50 row justify-content-center align-items-center">

        <div class="card shadow col-lg-8 p-5 text-center">

            <h4><?= Html::encode($this->title) ?></h4>
            <hr>

            <img src="ISM/main/images/enconstruccion.jpg">
            
            <div class="alert alert-danger" role="alert">
                <b>¡Sentimos los inconvenientes presentados!</b>
                Pronto estaremos con este sitio al 100%
            </div>

        </div>

    </div>



</div>


