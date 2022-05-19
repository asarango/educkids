<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

//echo '<pre>';
//print_r($opciones6);
?>

<div class="row">
    <h5 style="text-align: start; margin-top: 10px;color:#0a1f8f">6.-ADAPTACIONES DE ACCESO AL CURRÍCULO</h5>
    <div class="col-lg-12 col-md-12 ">

        <!--Seccion 6.1-->
        <div class="table responsive">
            <table class="table table-hover table-bordered my-text-medium">
                <thead>
                    <tr>
                        <th colspan="2" >6.1- RECURSOS TÉCNICOS: (MARCAR CON UNA X)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($opciones6 as $opcion) {
                        if ($opcion['seccion'] == '61') {
                            ?>
                            <tr>
                                <td style="width: 300px"><?= $opcion['nombre'] ?></td>
                                <td style="width: 100px">
                                     <?php
                                    if ($opcion['es_seleccionado'] == 1) {
                                        ?>
                                        <input type="checkbox" onclick="ajaxUpdateSection6(<?=$opcion['id'] ?>)" class="form-checkbox" value="<?=$opcion['es_seleccionado']?>" checked = "">
                                        <?php
                                    } else {
                                        ?>
                                               <input type="checkbox" onclick="ajaxUpdateSection6(<?=$opcion['id'] ?>)" class="form-checkbox" value="<?= $opcion['es_seleccionado'] ?>">
                                        <?php
                                    }
                                    ?>

                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!--Seccion 6.2-->
        <div class="table responsive">
            <table class="table table-hover table-bordered my-text-medium">
                <thead>
                    <tr>
                        <th colspan="2" >6.2- INTERVENCIÓN DE PROFESIONALES ESPECIALIZADOS DOCENTES Y NO DOCENTES: (MARCAR CON UNA X)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($opciones6 as $opcion) {
                        if ($opcion['seccion'] == '62') {
                            ?>
                            <tr>
                                <td style="width: 300px"><?= $opcion['nombre'] ?></td>
                                <td style="width: 100px">

                                    <?php
                                    if ($opcion['es_seleccionado'] == 1) {
                                        ?>
                                    <input type="checkbox"  onclick="ajaxUpdateSection6(<?=$opcion['id'] ?>,0)" class="form-checkbox" value="<?=$opcion['es_seleccionado']?>" checked = "">
                                        <?php
                                    } else {
                                        ?>
                                               <input type="checkbox" onclick="ajaxUpdateSection6(<?=$opcion['id'] ?>,1)" class="form-checkbox" value="<?= $opcion['es_seleccionado'] ?>">
                                        <?php
                                    }
                                    ?>


                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function ajaxUpdateSection6(id) {
//        alert(id);
//        alert(estado);
        var url = '<?= Url::to(['update-sections6']) ?>';
        var params = {
            id: id
        };
        
        $.ajax({
           data: params,
           url: url,
           type: 'POST',
           beforeSend: function(){},
           success: function(){ }
               
        });
    }
</script>