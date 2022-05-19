<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
?>
<br>
<ul>
    <li>
        <b>1.- DATOS INFORMATIVOS</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('1.1.-');">1.1.- Ver Datos</a></li>
        </ul>
    </li>
    <hr>
    <li>
        <b>2.- DESCRIPCIÓN Y TEXTOS DE LA UNIDAD</b>
        <ul>
            <li class="zoom"><a href="#" onclick="ver_detalle('2.1.-');">2.1.- Descripción y Texto de la Unidad</a></li>
        </ul>
    </li>
    <hr>
    
    <li>
        <b>3.- EVALUACIÓN DEL PD PARA LA UNIDAD</b>
        <ul>                                          
            <li class="zoom"><a href="#" onclick="ver_detalle('3.1.-');">3.1.- Evaluación del PD para la unidad</a></li> 
                                           
        </ul>
    </li>
    <hr>

    <li>
        <b>4.- INDAGACIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('4.1.-');">4.1.- Objetivos de Tranferencia</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>5.- ACCIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('5.1.-');">5.1.- Contenido, Habilidades y Conceptos: Conocimientos Esenciales</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.2.-');">5.2.- Proceso de aprendizaje</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.3.-');">5.3.- Enfoque del aprendizaje (EDA):</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.4.-');">5.4.- Lenguaje y Aprendizaje</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.5.-');">5.5.- Conexiones con TDC</a></li>
            <li class="zoom"><a href="#" onclick="ver_detalle('5.6.-');">5.6.- Conexiones con CAS</a></li>
        </ul>
    </li>
    <hr>

    <li>
        <b>6.- RECURSOS</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('6.1.-');">6.1.- Recursos</a></li>            
        </ul>
    </li>
    <hr>

    <li>
        <b>7.- REFLEXIÓN</b>
        <ul>                                
            <li class="zoom"><a href="#" onclick="ver_detalle('7.1.-');">7.1.- Lo que fuincionó bien</a></li>            
            <li class="zoom"><a href="#" onclick="ver_detalle('7.2.-');">7.2.- Lo que nó fuincionó bien</a></li>            
            <li class="zoom"><a href="#" onclick="ver_detalle('7.3.-');">7.3.- Observaciones, Cambios y sugerencias </a></li>          
            
        </ul>
    </li>   
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
            }
        });
        
    }
</script>