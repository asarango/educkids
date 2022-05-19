<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Terminar Año Lectivo de la Clase: ' . $modelClase->id . ' / ' .
        'Materia: ' . $modelClase->materia->name
;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-index">

    <div class="container">

        <div class="alert alert-info">
            <p>Usted va a procesar el cierre de año de esta asignatura.
                Esto registra las notas de sus alumnos para ser tomados en cuenta para los reportes finales de libretas y promociones
            </p>
            <p><strong>¿Está seguro que desea realizar el cierre de año en esta asignatura?</strong></p>
            <br><br>
            <p>
                <?= Html::a('Aceptar', ['terminar','clase' => $modelClase->id,'ejecutar' => 'SI'], ['class' => 'btn btn-info']) ?>
            </p>

        </div>




    </div>




</div>
