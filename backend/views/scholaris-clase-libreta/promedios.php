<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisClaseLibretaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$sentencia = new backend\models\SentenciasClaseLibreta();

$this->title = 'Promedios del curso: ' . $modelMallaCurso->curso->name . ' ' . $modelParalelo->name;
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="scholaris-clase-libreta-promedios">

    <p>
        <?php // echo Html::a('PDF', ['pdf', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-danger'])  ?>
        <?php // echo Html::a('EXCEL', ['excel', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-success'])  ?>
    </p>
    <hr>

    <div class="row">

    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="btn-group-vertical">
                <button type="button" class="btn btn-success">REPORTES EN EXCEL</button>
                <?php echo Html::a('QUIMESTRE I', ['excel', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-default']) ?>
                <?php echo Html::a('QUIMESTRE II', ['excel', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-default']) ?>
                <?php echo Html::a('FINAL DE MAYOR A MENOR', ['excelmayoramenor', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id], ['class' => 'btn btn-default']) ?>                
                <button type="button" class="btn btn-danger">REPORTES EN PDF</button>
                <?php echo Html::a('QUIMESTRE I', ['pdf', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id, 'qui' => 'Q1'], ['class' => 'btn btn-default']) ?>
                <?php echo Html::a('QUIMESTRE II', ['pdf', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id, 'qui' => 'Q2'], ['class' => 'btn btn-default']) ?>
                <?php echo Html::a('FINAL DE MAYOR A MENOR', ['pdf', 'curso' => $modelMallaCurso->curso->id, 'paralelo' => $modelParalelo->id, 'qui' => 'FI'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>

        <div class="col-md-10">
            <div class="table table-responsive">
                <table class="table table-striped table-hover table-bordered tamano10">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">ESTUDIANTES</th>     
                            <?php
                            foreach ($modelMaterias as $materia) {
                                if ($materia['promedia'] == true) {
                                    echo '<th colspan="3">' . $materia['abreviarura'] . '</th>';
                                } else {
                                    echo '<th colspan="3"> * ' . $materia['abreviarura'] . '</th>';
                                }
                            }
                            ?>
                            <th colspan="3" bgcolor="">PROM.</th>
                        </tr>
                        <tr>
                            <?php
                            foreach ($modelMaterias as $materia) {
                                echo '<th colspan="">Q1</th>';
                                echo '<th colspan="">Q2</th>';
                                echo '<th colspan="" bgcolor="#CCCCCC">QF</th>';
                            }
                            ?>
                        </tr>
                    </thead>


                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($modelAlumno as $alumno) {
                            $i++;
                            echo '<tr>';
                            echo '<td>' . $i . '</td>';
                            echo '<td>' . $alumno->id . $alumno->last_name . ' ' . $alumno->first_name . '</td>';


                            foreach ($modelMaterias as $mat) {
                                $notas = $sentencia->get_notas_finales_normales($mat['id'], $alumno->id);

                                echo '<td>' . $notas['q1'] . '</td>';
                                echo '<td>' . $notas['q2'] . '</td>';
                                echo '<td bgcolor="#CCCCCC">' . $notas['final_ano_normal'] . '</td>';
                            }


                            $promedios = $sentencia->get_promedios_normales($alumno->id);

                            echo '<td>' . $promedios['q1'] . '</td>';
                            echo '<td>' . $promedios['q2'] . '</td>';
                            echo '<td bgcolor="#CCCCCC">' . $promedios['final_ano_normal'] . '</td>';
                            echo '</tr>';
                        }
                        ?>                
                    </tbody>

                </table>
            </div>
        </div>

    </div>



</div>

<!--<script src="https://code.jquery.com/jquery-3.3.1.js"></script>-->
<!--<script src="jquery/jquery18.js"></script>-->
<!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>-->
<script>

//    $(document).ready(function () {
//        $("#example").DataTable();
//    });
//    
//    function hola(){
//        console.log('ola k ase');
//    }
//    
//    function init() {
//        const table = $("#dt-table").dataTable();
//        const tableData = getTableData(table);
//        createHighcharts(tableData);
//        setTableEvents(table);
//    }
</script>