<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Modificar calificaciones';
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Calificar', ['calificar', "id" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-individual">

    <h3><?= Html::encode($this->title) ?></h3>
    <h5>
        <?php
        echo $modelActividad->clase->course->name . ' - ' .
        $modelActividad->clase->paralelo->name . ' - ' .
        $modelActividad->clase->materia->name . ' - ' .
        $modelActividad->clase->profesor->last_name . ' ' . $modelActividad->clase->profesor->x_first_name . ' // ';
        echo '<small>' . $modelActividad->title . '</small>';
        ?>
    </h5>

    <div class="container">
        <div class="row">
            <div class="col-md-2">Criterio</div>
            <div class="col-md-2">Nota</div>
            <div class="col-md-4">Acción</div>
        </div>
        <?php
        foreach ($model as $nota) {
            echo '<div class="row">';
            if (!$nota->criterio_id) {
                echo '<div class="-md-2">Ingrese nueva nota</div>';
            } else {
                echo '<div class="col-md-2">' . $nota->criterio->criterio . '</div>';
            }
            
            echo Html::beginForm(['registra'], 'POST');
            echo '<div class="-md-2">';
            echo Html::textInput("nota", '', ['id' => 'calificar', 'type' => 'number', 'style' => '', 'min' => $modelMinimo->valor, 'max' => $modelMaximo->valor, 'step' => "any"]);
            echo Html::hiddenInput("notaId", $nota->id);
            echo Html::hiddenInput("bandera", 'b');
            echo '</div>';
            echo '<div class="-md-4">';
            echo Html::submitButton('Registrar', ['class' => 'btn btn-outline-primary']);
            echo '</div>';            
            
            echo Html::endForm();
            
//
//            
//
////            <div class="form-group">
//            echo '<div class="col">';
//            echo Html::submitButton('Registrar', ['class' => 'btn btn-outline-primary']);
//            echo '</div>';
////            </div>


            echo '</div>';
        }
        ?>
    </div>


</div>

<script>
    document.getElementById("calificar").focus();

</script>