<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificación de Comportamiento Sección Iniciales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-comportamiento-inicial-index">

    <h1><?= Html::encode($this->title) ?></h1>
<?php // echo $this->render('_search', ['model' => $searchModel]);  ?>


    <div class="container">
        <div class="table table-responsive">
            <table class="table table-condensed table-hover table-striped table-bordered">
                <tr>
                    <td align="center"><strong>CURSO</strong></td>
                    <td align="center"><strong>PARALELO</strong></td>
                    <td align="center"><strong>CALIFICAR</strong></td>
                </tr>

                <?php
                foreach ($modelCursos as $curso){
                    ?>
                
                <tr>
                    <td><?= $curso['curso'] ?></td>
                    <td align="center"><?= $curso['paralelo'] ?></td>
                    <td align="center">
                         <?= Html::a('Calificar', ['index1','paralelo' => $curso['id']], ['class' => 'btn btn-success']) ?>
                    </td>
                </tr>
                
                <?php
                }
                ?>


            </table>
        </div>    
    </div>

</div>
