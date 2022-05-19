<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<ul>
    <li>
        <b>1.- DATOS INFORMATIVOS</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('datos');">Ver datos</a></li>
        </ul>
    </li>
    <hr>
    
    <li>
        <b>2.- INDAGACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('conceptos');">Conceptos y enunciado</a></li>
            <!-- <li class="zoom"><a href="#" onclick="ver_detalle('enunciado');">Enunciado</a></li> -->
            <li class="zoom"><a href="#" onclick="ver_detalle('preguntas');">Preguntas</a></li>                                
        </ul>
    </li>
    <hr>

    <li>
        <b>3.- EVALUACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('evaluacion');">Ver más</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>4.- ENFOQUES DEL APRENDIZAJE</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('grupo_habilidades');">Grupo de habilidades</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('aspecto_objetivo');">Aspecto del Objetivo</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('inidicador_habilidad');">Indicador de habilidad</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('como_se_ensenara');">Como se enseñará</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('perfil_bi');">Perfil BI</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- ACCIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('accion');">Ver más</a></li>            
        </ul>
    </li>
    <hr>

    <li>
            <b>6.- SERVICIO COMO ACCIÓN</b>
            <ul>                                
                <li class="zoom"><a href="#" onclick="ver_detalle('servicio_accion');">Ver más</a></li>            
            </ul>
    </li>
    <hr>

    <li>
        <b>8.- RECURSOS</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('recursos');">Ver más</a></li>            
        </ul>
    </li>
    <hr>

    <li>
        <b>9.- REFLEXIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('reflexion');">Ver más</a></li>            
        </ul>
    </li>
    <hr>
</ul>

<script>
    function ver_detalle( pestana ){
        var planUnidadId = '<?= $planUnidad->id ?>';        
        var url = '<?= Url::to(['pestana']) ?>';

        var params = {
            plan_unidad_id  : planUnidadId,
            pestana         : pestana
        };

        $.ajax({
            data    : params,
            url     : url,
            type    : 'GET',
            beforeSend: function(){},
            success : function(response){
                $("#div-detalle").html(response);
                
                if(pestana == 'preguntas'){                 
                    muestra_preguntas();
                }else if(pestana == 'evaluacion'){
                    show_sumativas_evaluaciones();
                    show_sumativas_evaluaciones2();
                }else if(pestana == 'como_se_ensenara'){
                    show_ensenara();
                }else if(pestana == 'perfil_bi'){
                    show_perfiles_seleccionados();
                }else if(pestana == 'recursos'){
                    show_recursos();
                }else if(pestana == 'reflexion'){
                    show_reflexion_seleccionados();
                }else if(pestana=='servicio_accion'){
                    show_servicios_accion_disponibles();
                    show_servicios_accion_seleccionadas();
                }
            }
        });
        
    }
</script>