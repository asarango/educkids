<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisNotasPaiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Best Fit PAI: ' . $modelClase->curso->name . ' ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->materia->name
        . ' / Clase #:' . $modelClase->id
;
$this->params['breadcrumbs'][] = ['label' => 'Mis clases', 'url' => ['profesor-inicio/clases']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-notas-pai-index">

    <div class="alert alert-info">
        <?= Html::a('| Quimestre 1 | ', ['index1', 'quimestre' => 'QUIMESTRE I', 'id' => $modelClase->id]) ?>
        <?= Html::a('| Quimestre 2 | ', ['index1', 'quimestre' => 'QUIMESTRE II', 'id' => $modelClase->id]) ?>
        
        <hr>
        <h3><strong><small>Usted está trabajando en el  </small><?= $modelQuimestre->nombre ?></strong></h3>

    </div>

    <div class="table table-responsive">
        <table class="table table-hover table-condensed table-striped table-bordered" style="font-size: 10px;">
            <thead>
                <tr>
                    <td rowspan="2"><strong>#</strong></td>
                    <td rowspan="2"><strong>ESTUADIANTES</strong></td>
                    <td colspan="4" bgcolor="#b4dcc1" align="center"><strong>CRITERIO A</strong></td>
                    <td colspan="4 " bgcolor="#fbe6c1" align="center"><strong>CRITERIO B</strong></td>
                    <td colspan="4"  bgcolor="#b4dcc1" align="center"><strong>CRITERIO C</strong></td>
                    <td colspan="4"  bgcolor=" #fbe6c1 " align="center"><strong>CRITERIO D</strong></td>
                    <td rowspan="2"><strong>TOTAL<br>X/32</strong></td>
                    <td rowspan="2"><strong>FINAL<br>X/7</strong></td>
                </tr>
                <tr>
                    <td bgcolor="#b4dcc1" align="center"><strong>P1</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>P2</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>P3</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>NOTA</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P1</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P2</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P3</strong></td>
                    <td bgcolor="#fbe6c1"><strong>NOTA</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>P1</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>P2</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>P3</strong></td>
                    <td bgcolor="#b4dcc1" align="center"><strong>NOTA</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P1</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P2</strong></td>
                    <td bgcolor="#fbe6c1"><strong>P3</strong></td>
                    <td bgcolor="#fbe6c1"><strong>NOTA</strong></td>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($modelCalificaciones as $nota) {
                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $nota->alumno . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa1_a . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa2_a . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa3_a . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">';
                    echo '<input type="number" value="' . $nota->nota_a . '" class="form-control" id="A' . $nota->alumno_id . '" '
                    . 'onchange=cambianota(' . $nota->alumno_id . ',' . $modelQuimestre->id . ',\'A\',"' . Url::to(['actualizanota']) . '",' . $modelClase->id . ') '
                    . 'onkeypress="return NumCheck(event, this)">';
                    echo '</td>';

                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa1_b . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa2_b . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa3_b . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">';
                    echo '<input type="number" value="' . $nota->nota_b . '" class="form-control" id="B' . $nota->alumno_id . '" '
                    . 'onchange=cambianota(' . $nota->alumno_id . ',' . $modelQuimestre->id . ',\'B\',"' . Url::to(['actualizanota']) . '",' . $modelClase->id . ')>';
                    echo '</td>';

                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa1_c . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa2_c . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">' . $nota->sumativa3_c . '</td>';
                    echo '<td bgcolor="#b4dcc1" align="center">';
                    echo '<input type="number" value="' . $nota->nota_c . '" class="form-control" id="C' . $nota->alumno_id . '" '
                    . 'onchange=cambianota(' . $nota->alumno_id . ',' . $modelQuimestre->id . ',\'C\',"' . Url::to(['actualizanota']) . '",' . $modelClase->id . ')>';
                    echo '</td>';

                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa1_d . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa2_d . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">' . $nota->sumativa3_d . '</td>';
                    echo '<td bgcolor="#fbe6c1" align="center">';
                    echo '<input type="number" value="' . $nota->nota_d . '" class="form-control" id="D' . $nota->alumno_id . '" '
                    . 'onchange=cambianota(' . $nota->alumno_id . ',' . $modelQuimestre->id . ',\'D\',"' . Url::to(['actualizanota']) . '",' . $modelClase->id . ')>';
                    echo '</td>';

                    echo '<td bgcolor="" align="center"><strong>' . $nota->suma_total . '</strong></td>';
                    echo '<td bgcolor="" align="center"><strong>' . $nota->final_homologado . '</strong></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>


</div>

<script>
    function cambianota(alumno, quimestre, criterio, url, clase) {
        var id = '#' + criterio + alumno;
        var nota = $(id).val();

        var parametros = {
            "alumno": alumno,
            "quimestre": quimestre,
            "criterio": criterio,
            "nota": nota,
            "clase": clase
        };


        if (nota >= 0 && nota <= 8) {

            $.ajax({
                data: parametros,
                url: url,
                type: 'POST',
                beforeSend: function () {},
                success: function (response) {
                    //$("#bloque").html(response);
                }
            });
        } else {
             alert("La calificación debe estar ente 0 y 8");
             location.reload();
        }


//    }else{
//         alert("La calificación debe estar ente 0 y 8";
//         location.reload();
//    }

    }


    function NumCheck(e, field) {

        key = e.keyCode ? e.keyCode : e.which

        // backspace
        if (key == 8)
            return true

        // 0-9
        if (key > 47 && key < 58) {
            if (field.value == "")
                return true

            regexp = /.[0-9]{2}$/
            return !(regexp.test(field.value))
        }

        // .

        if (key == 46) {
            if (field.value == "")
                return false
            regexp = /^[0-9]+$/
            return regexp.test(field.value)
        }
        // other key

        if (key == 9)
            return true

        return false
    }
</script>
