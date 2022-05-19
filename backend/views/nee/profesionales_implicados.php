<?php

use yii\helpers\Html;
use yii\grid\GridView;

?>




<div class="row">
    <h5 style="text-align: start; margin-top: 10px;color:#0a1f8f">4.-PROFESIONALES IMPLICADOS</h5>
    <div class="col-lg-12 col-md-12" style="padding:10px">
        <div class="table responsive">
            <table class="table table-hover table-striped my-text-medium">
                <thead>
                    <tr>
                        <th style="text-align:center">NOMBRE</th>
                        <th>FUNCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($materiasNee as $seleccionada) {
                        ?>
                    <tr>
                        <td><?=$seleccionada->clase->profesor->last_name.' '.$seleccionada->clase->profesor->x_first_name ?></td>
                        <td><strong>DOCENTE</strong></td>
                    </tr>
                    <?php
                    }
                    
                    ?>
                    
                </tbody>
            </table>

        </div>
    </div>
</div>

