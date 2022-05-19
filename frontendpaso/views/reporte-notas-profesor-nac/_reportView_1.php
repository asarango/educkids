<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="table table-responsive">
    <table class="table table-condensed table-hover table-bordered" style="font-size: 7px">

        <!--titulos de la tabla-->

        <tr>
            <th>CRITERIOS Y ACTIVIDADES</th>
            <?php
            foreach ($tituloCriterios as $titulo) {

                $insumo = nombreInsumo($titulo['grupo_numero']);
                ?>
                <th colspan="<?php echo $titulo['total'] ?>"><?php echo $insumo . $titulo['total'] ?></th>
                <?php
            }


            foreach ($titulosSumativas as $suma) {
                $insumo = insumoSumativa($suma['grupo_numero']);
                ?>
                <th colspan="<?php echo $suma['total'] ?>"><?php echo $insumo ?></th>
                <?php
            }
            ?>
            <th clospan="">FINAL</th>
        </tr>
        <!--FIN titulos de la tabla-->

        <!--INICIO actividades de la tabla-->

        <tr>
            <th>Descripción y aspectos evaluados</th>
            <?php
            $bandera = $actNoSumativas[0]['grupo_numero'];
                                    
            foreach ($actNoSumativas as $actN) {
                if($actN['grupo_numero'] == $bandera){
                ?>                
                <th colspan=""><?php echo $actN['title'] ?></th>
                <?php
                }else{
                    ?>
                <th colspan="">PROMEDIO</th>                
                <?php
                $bandera = $actN['grupo_numero'];
                }
            }
            ?>
        </tr>


        <!--FIN actividades de la tabla-->


    </table>
</div>


<?php

function nombreInsumo($grupo) {
//        $insumo = $grupo;
    if ($grupo == 1) {
        $insumo = 'Tareas - Criterio A';
    } elseif ($grupo == 2) {
        $insumo = 'Act. Individual - Criterio B';
    } elseif ($grupo == 3) {
        $insumo = 'Act. Grupal - Criterio C';
    } elseif ($grupo == 4) {
        $insumo = 'Lecciones - Criterio D';
    } elseif ($grupo == 5) {
        $insumo = 'Avaluación';
    }

    return $insumo;
}

function insumoSumativa($grupo) {
    if ($grupo == 1) {
        $insumo = 'A';
    } elseif ($grupo == 2) {
        $insumo = 'B';
    } elseif ($grupo == 3) {
        $insumo = 'C';
    } elseif ($grupo == 4) {
        $insumo = 'D';
    }

    return $insumo;
}
?>