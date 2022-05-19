<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

  //echo '<pre>';
  //print_r($modelPlanifVertDiplHab);

?>

<h3><b><u>HABILIDADES DE ENFOQUE DEL APRENDIZAJE</u></b></h3>

<div class="row">
    <div class="col-lg-12 col-md-12">

        <?php
            foreach($modelPlanifVertDiplHab as $hab){
                ?>
                <b><u><?= $hab['es_titulo2']?></u></b>
                <?php

                echo '<ul>';
                foreach($hab['subhabilidades'] as $sub){
                    if($sub['es_seleccionado'] == true){
                        echo Html::a('<i class="far fa-thumbs-up zoom" style="color: #0a1f8f"> '.$sub['es_exploracion'].'</i>',['update-habilidad',
                            'id' => $sub['pvd_tdc_id'],
                            'accion' => 'eliminar',
                            'plan_vertical_id' => $modelPlanifVertDiplId
                        ]).'<br>';
                    }else{
                        echo Html::a('<i class="far fa-thumbs-down zoom" style="color: #ab0a3d"> '.$sub['es_exploracion'].'</i>',['update-habilidad',
                            'habilidad_id' => $sub['hab_id'],
                            'accion' => 'agregar',
                            'plan_vertical_id' => $modelPlanifVertDiplId
                        ]).'<br>';
                    }                                     
                }
                echo '</ul>';
            }
        ?>

    </div>
</div>