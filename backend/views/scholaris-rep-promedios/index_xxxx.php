<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use backend\models\OpStudent;
use yii\helpers\Url;

//print_r(@webroot);
//die();

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisRepPromediosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reporte de promedios del paralelo';
$pdfTitle = $this->title;
$this->params['breadcrumbs'][] = $this->title;
$pdfHMTLHeader = '<h4>UNIDAD EDUCATIVA PARTICULAR</h4>'
        . '<h5>"ROSA DE JESÚS CORDERO"</h5>'
        . '<h6>REGISTRO DE PROMEDIOS POR PARCIAL</h6>'
        . '<h6></h6>';
$pdfHeader = [
    'L' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'C' => [
        'content' => $pdfHMTLHeader,
        'font-size' => 10,
        //'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        //'content' => $pdfTitle,
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];
$pdfFooter = [
    'L' => [
        'content' => '',
        'font-size' => 8,
        'font-style' => '',
        'font-family' => 'arial',
        'color' => '#929292'
    ],
    'C' => [
        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'R' => [
        'content' => '{PAGENO}',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#000000'
    ],
    'line' => 1,
];
?>
<div class="scholaris-rep-promedios-index">

    <h1><?= Html::encode($this->title) ?></h1>    

    <p>
        <?= Html::a('Create Scholaris Rep Promedios', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <div class="container">

        <div class="table table-responsive">
            <table id="dataTable" class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ESTUDIANTE</th>
                        <th>PROMEDIO</th>
                    </tr>                    
                </thead>

                <tbody id="detalle">
                    <?php
                    $i = 0;
                    foreach ($model as $data) {
                        $i++;
                        echo "<tr>";
                        echo "<td>$i</td>";
                        echo "<td>" . $data->alumno->last_name . ' ' . $data->alumno->first_name . ' ' . $data->alumno->middle_name . "</td>";
                        echo "<td>" . $data->nota_promedio . "</td>";
                        echo "</tr>";
                        //echo $data->codigo;
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

        <link rel="stylesheet" href="DataTables/datatables.min.css">        
        <script src="jquery/jquery1.js" type="text/javascript"></script>
        <script src="DataTables/datatables.min.js" type="text/javascript"></script>


<script>

    jQuery(document).ready(function () {
        //alert('prueba');
        var url = "<?= Url::to(['respuesta']) ?>";

//        pruebaAjax();



        jQuery("#dataTable").DataTable({
            
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": url,
                "type": "GET"

            },
            
            
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "columns": [
                {"data": "menu_id"},
                {"data": "operacion"},
                {"data": "nombre"},
                {
                    "sTitle": "Acciones",
                    "mData": null,
                    render: function (data, type, row, meta) {

                        data = '<a href="/laboratorio/backend/web/index.php?r=operacion%2Fupdate&id=' + data.id + '">editar</a>';

                        return data;
                    }
                }
            ],
//            dom: 'Bfrtip',
            dom: 'lfrtip<"bottom"B>',
//            "dom": '<"top"i>rt<"bottom"flp><"clear">',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

</script>