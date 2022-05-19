<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisCalificacionesInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Calificaciones de Iniciales: ' . $modelClase->curso->name
        . ' - ' . $modelClase->paralelo->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
        . ' / ' . $modelClase->materia->name
        . ' / Clase# ' . $modelClase->id
;

$this->params['breadcrumbs'][] = ['label' => 'Clases', 'url' => ['profesor-inicio/clases']];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-calificaciones-inicial-index">


    <div class="alert alert-info">
        <?php
            $modelQuimestresNormales = backend\models\ScholarisQuimestre::find()
                    ->where(['tipo_quimestre' => 'normal'])
                    ->orderBy('orden')
                    ->all();

            foreach ($modelQuimestresNormales as $quim) {
                echo '<strong>| </strong>';
                echo Html::a($quim->nombre, ['index1', 'id' => $modelClase->id, 'quimestre' => $quim->orden], ['class' => 'btn btn-link']);
                echo '<strong> |</strong>';
            }
            ?>
    </div>
    

    <div class="">
        <h1><?= $modelQuimestre->nombre ?></h1>        
    </div>


    

    <div class="table table-responsive">
        <table class="table table-bordered table-hover table-striped table-condensed">
            <tr>
                <td><strong>#</strong></td>
                <td><strong>ESTUDIANTES</strong></td>

                <?php
                foreach ($modelPlanificacion as $planificacion) {
                    echo '<td align="center" tabindex="0" data-toggle="tooltip" data-placement="top"'
                    . 'title="'.$planificacion->destreza_desagregada.'"><strong>' . $planificacion->codigo_destreza . '</strong></td>';
                }
                ?>

            </tr>


            <?php
            $i = 0;
            foreach ($modelAlumnos as $alumno) {
                $i++;
                echo '<tr>';

                echo '<td align="center"><strong>' . $i . '</strong></td>';
                echo '<td align=""><strong>';
                echo Html::a($alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'],
                        ['observaciones', 'id' => $alumno['id'], 'quimestre' => $modelQuimestre->id, 'clase' => $modelClase->id],
                        ['class' => 'btn btn-link']);

                echo '</strong></td>';

                foreach ($modelPlanificacion as $plan) {

                    $nota = get_nota($alumno['id'], $modelClase->id, $plan->id);
                    echo '<td align="center">';
                    echo '<input type="text" name="calif" value="' . $nota['calificacion'] . '" onchange="cambianota(this,' . $nota['id'] . ')">';
                    echo '</td>';
                }

                echo '</tr>';
            }
            ?>

        </table>
    </div>

    <?php
    ?>

</div>




<?php

function get_nota($alumnoId, $claseId, $planId) {
    $con = Yii::$app->db;
    $query = "select 	c.calificacion, c.grupo_id, c.id
                    from 	scholaris_calificaciones_inicial c
                                    inner join scholaris_grupo_alumno_clase g on g.id = c.grupo_id
                    where	g.estudiante_id = $alumnoId 
                                    and g.clase_id = $claseId
                                    and c.plan_id = $planId order by "
            . "c.creado_fecha desc ;";
    $res = $con->createCommand($query)->queryOne();
    return $res;
}
?>

<script>
    function cambianota(obj, id) {

        if (
                $(obj).val() == 'NE' ||
                $(obj).val() == 'I' ||
                $(obj).val() == 'EP' ||
                $(obj).val() == 'A'

                ) {
            var url = "<?= \yii\helpers\Url::to(['cambianota']) ?>";

            var parametros = {
                "calificacion": $(obj).val(),
                "id": id
            };

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
            alert('No es un opción válida!!!');
        }




    }
</script>