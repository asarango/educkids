<?php
    use yii\helpers\Html;
?>


<div class="row text-center">
                        <div class="col-lg-12 col-md-12">
                            <?php

                            $modelRutaGraficos = backend\models\ScholarisParametrosOpciones::find()
                                ->where(['codigo' => 'graficos'])
                                ->one();
                            $rutaGraficos = $modelRutaGraficos->nombre;

                            $formativas = $serializado['formativas'];
                            $sumativas = $serializado['sumativas'];
                            //$graphLink = "http://localhost/graficos/menores7.php?datos=$dat&labels=$labels"; // create a new file, you can pass parameter to it also
                            $graphLink = "$rutaGraficos" . "estadisticapai.php?formativas=$formativas&sumativas=$sumativas"; // create a new file, you can pass parameter to it also
                            ?>
                            <img src="<?= $graphLink ?>" width="50%" class="img-thumbnail">

                        </div>
                    </div><!--FIN DE GRAFICO -->


                    <div class="row text-center">
                        <div class="col-lg-12 col-md-12">
                            <div class="table table-responsive">
                                <table class="table table-striped table-hover table-condensed table-bordered">
                                    <thead>
                                        <tr>
                                            <th>CRITERIO</th>
                                            <th>TIPO ACTIVIDAD</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                                                             
                                            foreach($data as $dat){
                                                echo '<tr>';
                                                echo '<td>'.$dat['criterio'].'</td>';
                                                echo '<td>'.$dat['tipo_actividad'].'</td>';
                                                echo '<td>'.$dat['total'].'</td>';
                                                echo '</tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div><!--FIN DE TABLA-->
                    </div>