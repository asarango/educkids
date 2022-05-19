<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisActividadSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sentencias = new \frontend\models\SentenciasSql();
$sentencias2 = new \backend\models\Notas();

$this->title = 'Actividades del Parcial: ' . $modelBloque->name . '(' . $modelBloque->id . ')'
        . ' / Clase: ' . $modelClase->id
        . ' / ' . $modelClase->materia->name
        . ' / ' . $modelClase->profesor->last_name . ' ' . $modelClase->profesor->x_first_name
;
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scholaris-actividad-parcial">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
            <li class="breadcrumb-item">
                <?php echo Html::a('Sabana Profesor', ['reporte-sabana-profesor/index1', "id" => $modelClase->id]); ?>
            </li>

            <li class="breadcrumb-item active" aria-current="page"><?= $this->title ?></li>
        </ol>
    </nav>


    <div class="table table-responsive">
        <table class="table table-bordered table-condensed table-hover tamano10">
            <thead>
                <tr>
                    <th rowspan="2">#</th>
                    <th rowspan="2">Estudiante</th>
                    <th rowspan="2">Estado Est</th>
                    <?php
                    foreach ($modelTipo as $tipo) {
                        echo '<th>' . $tipo['nombre_grupo'] . '</th>';
                    }
                    ?>
                    <th rowspan="2"><center><?= $modelBloque->name ?></center></th>

            <th rowspan="2"><center>OBSERVACIÓN</center></th>


            <?php
            foreach ($modelTipo as $tipo) {
                echo '<th>REF.' . $tipo['nombre_grupo'] . '</th>';
            }
            ?>

            <th rowspan="2"><center><?= $modelBloque->name ?>FIN</center></th>
            </tr>
            <tr>
                <?php
                foreach ($modelTipo as $tipo) {
                    echo '<th bgcolor="#CCCCCC"><center>PROMEDIO</center></th>';
                    echo '<th bgcolor="#CCCCCC"><center>PROMEDIO</center></th>';
                }
                ?>
            </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($modelAlumnos as $alumno) {
                    $i++;
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . $alumno['alumno_id'] . $alumno['last_name'] . ' ' . $alumno['first_name'] . ' ' . $alumno['middle_name'] . '</td>';
                    echo '<td>' . $alumno['inscription_state'] . '</td>';


                    foreach ($modelTipo as $tipo) {
                        $modelPromediosInsumos = $sentencias2->get_promedio_insumo($modelClase->id, $alumno['alumno_id'], $modelBloque->id, $tipo['grupo_numero']);
                        echo '<td bgcolor="#CCCCCC"><center><strong>' . $modelPromediosInsumos['calificacion'] . '</strong></center></td>';
                    }


                    $modelParcial = $sentencias2->get_promedio_parcial($modelClase->id, $alumno['alumno_id'], $modelBloque->id);

                    if ($modelParcial['promedio'] < $minima) {
                        echo '<td bgcolor="#FF0000"><center><strong>' . $modelParcial['promedio'] . '</strong></center></td>';
                        echo '<td><center><strong>';
                        echo Html::a('Reforzar', ['scholaris-refuerzo/refuerzo', 'grupo' => $alumno['grupo_id'], 'bloque' => $modelBloque->id]);
                        echo '</strong></center></td>';
                    } else {
                        echo '<td><center><strong>' . $modelParcial['promedio'] . '</strong></center></td>';
                        echo '<td><center></strong></center></td>';
                    }

                    foreach ($modelTipo as $tipo) {
                        $modelRefuerzos = backend\models\ScholarisRefuerzo::find()->where([
                            'grupo_id' => $alumno['grupo_id'],
                            'bloque_id' => $modelBloque->id,
                            'orden_calificacion' => $tipo['grupo_numero']
                        ])->one();
                        
                        if(isset($modelRefuerzos)){
                            echo '<td><center><strong>' . $modelRefuerzos->nota_refuerzo . '</strong></center></td>';
                        }else{
                            echo '<td><center><strong>-</strong></center></td>';
                        }
                        
                        
                    }
                    
                    $parcial = entrega_parcial($alumno['grupo_id'], $modelBloque->orden);
                    echo '<td><center><strong>'.$parcial.'</center></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>


</div>

<?php

    function entrega_parcial($grupo, $orden){
        switch ($orden){
            case 1:
                $campo = 'p1';
                break;
            case 2:
                $campo = 'p2';
                break;
            case 3:
                $campo = 'p3';
                break;
            case 4:
                $campo = 'ex1';
                break;
            case 5:
                $campo = 'p4';
                break;
            case 6:
                $campo = 'p5';
                break;
            case 7:
                $campo = 'p6';
                break;
            case 8:
                $campo = 'ex2';
                break;
        }
        
        
        $modelParcial = backend\models\ScholarisClaseLibreta::find()->where(['grupo_id' => $grupo])->one();
        if(isset($modelParcial)){
            return $modelParcial->$campo;
        }else{
            return 0;
        }
        
        
    }

?>