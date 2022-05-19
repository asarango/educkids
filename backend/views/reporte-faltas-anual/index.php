<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisTomaAsisteciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Faltas y Atrasos Anuales: ' . $modelParalelo->course->name
        . ' / ' . $modelParalelo->name
;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reporte-faltas-anual-index">


    <div class="table table-responsive">
        <table class="table table-hover table-condensed table-bordered">
            <tr>
                <td rowspan="3"><strong>ESTUDIANTES</strong></td>
                <td colspan="20" align="center"><strong>QUIMESTRE I</strong></td>
                <td colspan="20" align="center"><strong>QUIMESTRE II</strong></td>
            </tr>

            <tr>
<?= parciales_titulo(1) ?>
<?= parciales_titulo(4) ?>
            </tr>

            <tr>
<?= faltas_titulo() ?>
            </tr>

                <?= detalle($modelAlumnos, $modelParalelo->id) ?>

        </table>
    </div>



</div>

<?php

function parciales_titulo($inicio) {
    $html = '';
    for ($i = $inicio; $i < $inicio + 3; $i++) {
        $html .= '<td colspan="5" align="center"><strong>PARCIAL ' . $i . '</strong></td>';
    }
    $html .= '<td colspan="5" align="center"><strong>RESUMEN QI</strong></td>';
    return $html;
}

function faltas_titulo() {
    $html = '';
    for ($i = 0; $i < 8; $i++) {
        $html .= '<td align="center"><strong>F.J.</strong></td>';
        $html .= '<td align="center"><strong>F.I.</strong></td>';
        $html .= '<td align="center"><strong>A.T.</strong></td>';
        $html .= '<td align="center"><strong>A.J.</strong></td>';
        $html .= '<td align="center"><strong>D.A.</strong></td>';
    }

    return $html;
}

function detalle($modelAlumnos, $paralelo) {
    $html = '';

    foreach ($modelAlumnos as $alumno) {
        $html .= '<tr>';
        $html .= '<td align="">' . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';

        $html .= detalle_faltas_atrasos($paralelo, $alumno['id']);

        $html .= '</tr>';
    }

    return $html;
}

function dias_laborados($paralelo, $alumno, $orden) {
    $con = Yii::$app->db;
    $query = "select 	b.dias_laborados
                    from 	scholaris_toma_asistecia_detalle d
                                    inner join scholaris_toma_asistecia a on a.id = d.toma_id
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_id
                    where	a.paralelo_id = $paralelo
                                    and b.orden = $orden
                                    and d.alumno_id = $alumno;";
    $res = $con->createCommand($query)->queryOne();
    return $res;
}

function faltas_atrasos($paralelo, $alumno, $campo, $orden) {
    $con = Yii::$app->db;
    $query = "select 	count(d.id) as total
                                ,b.dias_laborados
                    from 	scholaris_toma_asistecia_detalle d
                                    inner join scholaris_toma_asistecia a on a.id = d.toma_id
                                    inner join scholaris_bloque_actividad b on b.id = a.bloque_id
                    where	a.paralelo_id = $paralelo
                                    and b.orden = $orden
                                    and d.alumno_id = $alumno
                                    and d.$campo = true group by b.dias_laborados;";
    $res = $con->createCommand($query)->queryOne();
    return $res;
}

function entrega_cantidades($paralelo, $alumno, $inicio, $fin) {
    $html = '';
    $j = 0;
    $i2 = 0;
    $a = 0;
    $atj = 0;
    $tot = 0;

    for ($i = $inicio; $i <= $fin; $i++) {

        $fj = faltas_atrasos($paralelo, $alumno, 'falta_justificada', $i);
        $fi = faltas_atrasos($paralelo, $alumno, 'falta', $i);
        $at = faltas_atrasos($paralelo, $alumno, 'atraso', $i);
        $aj = faltas_atrasos($paralelo, $alumno, 'atraso_justificado', $i);
        $la = dias_laborados($paralelo, $alumno, $i);

        $html .= '<td align="">' . $fj['total'] . '</td>';
        $html .= '<td align="">' . $fi['total'] . '</td>';
        $html .= '<td align="">' . $at['total'] . '</td>';
        $html .= '<td align="">' . $aj['total'] . '</td>';
        $html .= '<td align="">' . $la['dias_laborados'] . '</td>';

        $j = $j + $fj['total'];
        $i2 = $i2 + $fi['total'];
        $a = $a + $at['total'];
        $atj = $atj + $aj['total'];
        $tot = $tot + $aj['dias_laborados'];
    }

    $html .= $j > 0 ? '<td align="" bgcolor="#98F7DB">' . $j . '</td>' : '<td align="" bgcolor="#98F7DB"></td>';
    $html .= $i2 > 0 ? '<td align="" bgcolor="#98F7DB">' . $i2 . '</td>' : '<td align="" bgcolor="#98F7DB"></td>';
    $html .= $a > 0 ? '<td align="" bgcolor="#98F7DB">' . $a . '</td>' : '<td align="" bgcolor="#98F7DB"></td>';
    $html .= $atj > 0 ? '<td align="" bgcolor="#98F7DB">' . $atj . '</td>' : '<td align="" bgcolor="#98F7DB"></td>';
    $html .= $tot > 0 ? '<td align="" bgcolor="#98F7DB">' . $tot . '</td>' : '<td align="" bgcolor="#98F7DB"></td>';
    
    return $html;
}

function detalle_faltas_atrasos($paralelo, $alumno) {
    $html = '';

    $html .= entrega_cantidades($paralelo, $alumno, 1, 3);
    $html .= entrega_cantidades($paralelo, $alumno, 5, 7);

    return $html;
}
?>
