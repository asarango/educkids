<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3><b><u>CONEXIONES CON TDC</u></b></h3>
<div class="table table-responsive">
    <table class="table table-hover table-condensed table-striped table-bordered">
        <thead>
            <tr style="background-color: #ab0a3d; color: #eee">
                <th class="text-center">OPCIÓN</th>
                <th class="text-center">ESTÁ SELECCIONADO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            //  echo '<pre>';
            //  print_r($modelPlanifVertDiplTDC);
                foreach($modelPlanifVertDiplTDC as $tdc){
                    ?>
                        <tr>
                            <td><?= $tdc['opcion'] ?></td>
                            <td class="text-center"><?php 
                                    if($tdc['es_seleccionado']){
                                        echo Html::a('<i class="far fa-thumbs-up" style="color: #0a1f8f"></i>',
                                            ['update-tdc', 
                                              'pvd_tdc_id' =>  $tdc['pvd_tdc_id'],
                                              'tdc_id' => $tdc['tdc_id'],
                                              'plan_vertical_id' => $modelPlanifVertDiplId,
                                              'accion' => 'eliminar'
                                            ]
                                        );
                                        
                                    }else{
                                        echo Html::a('<i class="fas fa-thumbs-down" style="color: #ab0a3d"></i>',
                                            ['update-tdc', 
                                              'pvd_tdc_id' =>  $tdc['pvd_tdc_id'],
                                              'tdc_id' => $tdc['tdc_id'],
                                              'plan_vertical_id' => $modelPlanifVertDiplId,
                                              'accion' => 'agregar'
                                            ]
                                        );
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>
</div>