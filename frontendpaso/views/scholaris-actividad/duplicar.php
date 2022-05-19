<?php

use yii\helpers\Html;
use yii\grid\GridView;

$sentencias = new frontend\models\SentenciasSql();

/* @var $this yii\web\View */
/* @var $searchModel app\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Duplicar Actividad: ' . $modelActividad->clase->materia->name . ' / ' .
        'Clase: ' . $modelActividad->clase->id . ' / ' .
        'Curso: ' . $modelActividad->clase->curso->name . ' / ' .
        'Actividad: ' . $modelActividad->title . ' / ' .
        'Fecha: ' . $modelActividad->inicio;
//$this->params['breadcrumbs'][] = $this->title;
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        <li class="breadcrumb-item">
            <?php echo Html::a('Actividades', ['actividad', "actividad" => $modelActividad->id]); ?>
        </li>
        <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
    </ol>
</nav>


<div class="scholaris-actividad-duplicar">

    <div class="container">

        <?php
//        echo date('W', strtotime('2019-09-05'));
        $fechasAsi = toma_dias($modelActividad->inicio);
//        print_r($fechasAsi);


        foreach ($modelClases as $clase) {
            echo '<div class="table table-responsive">';
            echo '<table class="table table-hover table-striped table-bordered">';
            echo '<tr>';
            echo '<td colspan="4" align="center" bgcolor="CCCCCC">PARALELO: ' . $clase->paralelo->name . '</td>';
            echo '</tr>';

            $modelDias = toma_dias_horario($clase->id);
            foreach ($modelDias as $dia) {



                echo '<tr>';
                echo '<td width="30%">' . $dia['dia'] . '</td>';


                for ($i = 0; $i < count($fechasAsi); $i++) {
                    if (get_numero_dia($fechasAsi[$i]) == $dia['num_dia']) {
                        
                        echo '<td>' . $fechasAsi[$i] . '</td>';
                        echo '<td>' . $dia['num_dia'] . $dia['hora'] . '</td>';
                        
                        $modelRes = $sentencias->get_actividad_duplicada($modelActividad->id, $clase->id);

                if ($modelRes) {
                    echo '<td>Actividad ya se encuentra duplicada</td>';
                } 
                else {
                    echo '<td>';
                    echo Html::a('clic aquí...', ['duplicaraqui',
                        "actividadId" => $modelActividad->id,
                        'clase' => $clase->id,
                        'inicio' => $fechasAsi[$i],
                        'hora' => $dia['id']
                    ]);
                    echo '</td>';
                }
                    }
                }
                

                


                echo '</tr>';
            }

            echo '</table>';
            echo '</div>';
        }


//        foreach ($modelClases as $clase) {
//            echo '<p class="tamano10"><strong>';
//            echo 'PARALELO: ' . $clase->paralelo->name . ': ';
//            echo $clase->id.' '.$clase->profesor->last_name . ' ' . $clase->profesor->x_first_name;
//            echo '</strong></p>';
//
//            $modelDias = $sentencias->fecha_para_duplicar($modelActividad, $clase->id);
//            echo '<p class="tamano10">Fecha a duplicar: ' . $modelDias['dia'] . ' ' . $modelDias['fecha'];
//            echo '</p>';
//
//            $modelRes = $sentencias->get_actividad_duplicada($modelActividad->id, $clase->id);
//
//            if ($modelRes) {
//                echo '<p class="tamano10 text-success">Actividad ya se encuentra duplicada</p>';
//            } else {
//                echo '<p class="tamano10">Si desea duplicar la actividad en esta fecha, ';
//                echo Html::a('clic aquí...', ['duplicaraqui',
//                    "actividadId" => $modelActividad->id,
//                    'clase' => $clase->id,
//                    'inicio' => $modelDias['fecha']
//                ]);
//                echo '</p>';
//            }
//
//            echo '<hr>';
//        }
        ?>

    </div>
</div>

<?php

function toma_dias($fecha) {

    $arregloFechas = array();

    $fechaComoEntero = strtotime($fecha);

    $year = date("Y", $fechaComoEntero);
    $month = date("m", $fechaComoEntero);
    $day = date("d", $fechaComoEntero);

# Obtenemos el día de la semana de la fecha dada
    $diaSemana = date("w", mktime(0, 0, 0, $month, $day, $year));

# el 0 equivale al domingo...
    if ($diaSemana == 0)
        $diaSemana = 7;


    for ($i = 1; $i <= 5; $i++) {
        array_push($arregloFechas, date("Y-m-d", mktime(0, 0, 0, $month, $day - $diaSemana + $i, $year)));
    }

    return $arregloFechas;
}

function toma_dias_horario($clase) {
    $con = Yii::$app->db;
    $query = "select 	dia.nombre as dia
                ,dia.numero as num_dia
		,ho.nombre as hora
		,ho.id
from 	scholaris_horariov2_horario h
		inner join scholaris_horariov2_detalle d on d.id = h.detalle_id
		inner join scholaris_horariov2_dia dia on dia.id = d.dia_id
		inner join scholaris_horariov2_hora ho on ho.id = d.hora_id
where	h.clase_id = $clase
order by dia.numero, ho.numero;";
    $res = $con->createCommand($query)->queryAll();
    return $res;
}

function get_numero_dia($fecha) {

    $fechaComoEntero = strtotime($fecha);

    $year = date("Y", $fechaComoEntero);
    $month = date("m", $fechaComoEntero);
    $day = date("d", $fechaComoEntero);

    $diaSemana = date("w", mktime(0, 0, 0, $month, $day, $year));

    return $diaSemana;
}
?>