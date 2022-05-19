<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ScholarisCalificaComportamientoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Califciacion de comportamiento';
$this->params['breadcrumbs'][] = ['label' => 'Mis clases', 'url' => ['profesor-inicio/clases']];
$this->params['breadcrumbs'][] = $this->title;
//echo '<pre>';
//print_r($modelAlumnos);
//print_r($arregloAl);
//die();

?>
<div class="scholaris-califica-comportamiento-index">
    <div class="table table-responsive" style="padding: 40px">
        <table class="table table-condensed table-hover">
            <tr>
                <td><strong>#</strong></td>
                <td><strong>ESTUDIANTES</strong></td>
                <?php
                foreach ($modelBloques as $bloque){
                    if($bloque->quimestre == 'QUIMESTRE I'){
                        echo '<td>'.$bloque->abreviatura.'</td>';
                    }
                }
                
                echo '<td><strong>Q1</strong></td>';
                
                foreach ($modelBloques as $bloque){
                    if($bloque->quimestre == 'QUIMESTRE II'){
                        echo '<td>'.$bloque->abreviatura.'</td>';
                    }
                }
                
                echo '<td><strong>Q2</strong></td>';
                ?>
            </tr>
            <?php
            
            $i=0;
            foreach ($arregloAl as $al){
                $i++;
                ?>
            <tr>
                <td><?= $i ?></td>
                <td><?= $al['last_name'].' '.$al['first_name'].' '.$al['middle_name']?></td>
                <td><input type="number" name="nota" id="nota<?=$al['notas'][0]['calificacionId']?>" value="<?= $al['notas'][0]['calificacion'] ?>" onchange="cambiaNota(<?=$al['notas'][0]['calificacionId']?>)"></td>
                <td><input type="number" name="nota" id="nota<?=$al['notas'][1]['calificacionId']?>" value="<?= $al['notas'][1]['calificacion'] ?>" onchange="cambiaNota(<?=$al['notas'][1]['calificacionId']?>)"></td>                
                <td><strong><?= $al['notas'][1]['calificacion'] ?></td>
                
                <td><input type="number" name="nota" id="nota<?=$al['notas'][2]['calificacionId']?>" value="<?= $al['notas'][2]['calificacion'] ?>" onchange="cambiaNota(<?=$al['notas'][2]['calificacionId']?>)"></td>                
                <td><input type="number" name="nota" id="nota<?=$al['notas'][3]['calificacionId']?>" value="<?= $al['notas'][3]['calificacion'] ?>" onchange="cambiaNota(<?=$al['notas'][3]['calificacionId']?>)"></td>                
                <td><strong><?= $al['notas'][3]['calificacion'] ?></strong></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>

    
</div>


<script>
    function cambiaNota(idd){
        var ids = 'nota'+idd;
        var nota = $('#'+ids).val();
        var url = '<?= Url::to(['cambiar-nota']) ?>';
        
        var parametros = {
            "id"    : idd,
            "nota"  : nota
        };
        
        $.ajax({
            data: parametros,
            url: url,
            type:  'post',
            beforeSend: function () {
                //$(".field-facturadetalles-"+ItemId+"-servicios_id").val(0);
            },
            success:  function (response) {
                //$("#paralelo").html(response);

            }
        });
    }
    
    
    
   
</script>