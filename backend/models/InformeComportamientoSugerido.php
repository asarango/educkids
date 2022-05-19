<?php

namespace backend\models;

use Yii;
use backend\models\ScholarisMallaCurso;
use backend\models\ScholarisBloqueActividad;
use backend\models\ScholarisPeriodo;
use backend\models\OpStudent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class InformeComportamientoSugerido extends \yii\db\ActiveRecord {

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function genera_reporte($alumno, $bloque, $paralelo) {

        $modelParalelo = OpCourseParalelo::findOne($paralelo);
        $periodo = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($periodo);
        
        $modelBloque = ScholarisBloqueActividad::findOne($bloque);
        $modelAlumno = OpStudent::findOne($alumno);
        
       

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-L',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;



        $html = $this->genera_cuerpo_pdf($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
//
            $mpdf->WriteHTML($html);
//            $mpdf->addPage();

//        $mpdf->addPage();
//        $mpdf->SetFooter($pie);

        $mpdf->Output('ComportamientoSugerido' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo) {
        
        $usaIso = 0;
        $modelUsaIso = ScholarisParametrosOpciones::find()->where(['codigo' => 'usaiso'])->one();
        if($modelUsaIso){
            $usaIso = 1;
        }
        
        $fecha = date("Y-m-d");

        $html = '';

        $html .= '<table width="100%" cellspacing="0" style="font-size:12px">';
        $html .= '<tr>';
        $html .= '<td align="center" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px">';
        
        if($usaIso == 1){
            //$html .= '<br>';
            $html .= '<p class="tamano8"><strong>Proceso: Ejecución y Evaluación</strong></p>';            
        }
        
        $html .= '</td>';
        $html .= '<td align="center"><strong>REGISTRO DE SEGUIMIENTO DE LECCIONARIO</strong>';
        $html .= '<br><strong>' . $modelParalelo->course->name.' '.$modelParalelo->name. ' / '.$modelBloque->name . '</strong>';
        $html .= '<br><strong>DEPARTAMENTO PSICOLÓGICO</strong>';        
        $html .= '</td>';
        $html .= '<td width="20%" class="derechaTexto tamano8">';
        $html .= '<strong>Código: </strong>ISMR3-10<br>';
        $html .= '<strong>Versión: </strong>7.0<br>';
        $html .= '<strong>Fecha: </strong>'.$fecha.'<br>';
        $html .= '</td>';
        $html .= '<tr>';
        $html .= '</table>';
              


        return $html;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= 'Elaborado por: ' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo) {        

        $html = '';
        $html .= '<style>';
        $html .= '.conBorde {
                    border: 0.1px solid black;
                  }
                  
                  .centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano6{
                    font-size: 6px;
                  }
                  
                  .tamano8{
                    font-size: 9px;
                  }
                  
                .tamano10{
                    font-size: 10px;
                 }
                 
                 .paddingTd{
                    padding: 2px;
                }
                
                .fondo{
                    background-color: #CBFFE1;
                }

                    ';
        $html .= '</style>';

        $html .= $this->detalle_comportamiento($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
        $html .= $this->nota_sugerida($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
        

        return $html;
    }
    
    
    
    private function nota_sugerida($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo){
        $sentencias = new \backend\models\ComportamientoSugerido();
        $sentenciasNotas = new SentenciasRepLibreta2();
        $modelActividad = $this->consulta_actividad($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
        $valor = $sentencias->devuelve_nota_sugerida($modelAlumno->id, $modelBloque->id, $modelActividad);                
        
        $html = "";
        
        $html .= '<br>';
        $html .= '<table width="50%" cellspacing="0" align="center">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde fondo"><strong>NOTA CUANTITATIVA:</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde"><strong>'.$valor.'</strong></td>';
        $html .= '</tr>';
        
        $homologa = $sentenciasNotas->homologaComportamiento($valor);
        
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde fondo"><strong>NOTA CULITATIVA:</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde"><strong>'.$homologa['abreviatura'].'</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td class="tamano10 conBorde fondo"><strong>DESCRIPCIÓN:</strong></td>';
        $html .= '<td class="tamano10 conBorde"><strong>'.$homologa['descripcion'].'</strong></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    private function consulta_actividad($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo){
        $model = ScholarisActividad::find()
                ->innerJoin("scholaris_clase c","c.id = scholaris_actividad.paralelo_id")
                ->innerJoin("scholaris_malla_materia mm","mm.id = c.malla_materia")
                ->where([
                    "c.paralelo_id" => $modelParalelo->id,
                    "mm.tipo" => 'COMPORTAMIENTO',
                    "scholaris_actividad.bloque_actividad_id" => $modelBloque->id,
                    "scholaris_actividad.calificado" => 'SI'
                ])
                ->one();
        return $model;
    }


    private function detalle_comportamiento($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo){
        
        
        
        $html = '';
        $html .= '<hr>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td><strong>ESTUDIANTE:</strong>' . $modelAlumno->last_name.' '.$modelAlumno->first_name.' '.$modelAlumno->middle_name.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<table width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>FECHA</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>MATERIA</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>DOCENTE</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>HORA</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>CÓDIGO</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>DESCRIPCIÓN</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>OBSERVACIÓN</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>JUS</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>COD</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>DESCRIPCIÓN(J)</strong></td>';
        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>MOTIVO(J)</strong></td>';

        
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>FALTA</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>OBSERVACIÓN</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>FECHA</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>HORA</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>ASIGNATURA</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>DOCENTE</strong></td>';
//        $html .= '<td class="tamano10 centrarTexto conBorde fondo"><strong>JUSTIFICACIÓN</strong></td>';
        $html .= '</tr>';
        
        $detalle = $this->consulta_detalle($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo);
        
        foreach ($detalle as $det){
            $html .= '<tr>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['fecha'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['materia'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['last_name'].' '.$det['x_first_name'].' '.$det['middle_name'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['hora'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['codigo'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['detalle'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['observacion'].'</td>';
            
            $html .= '<td class="tamano10 centrarTexto conBorde"></td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['cod_justi'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['detalle_justificacion'].'</td>';
            $html .= '<td class="tamano10 centrarTexto conBorde">'.$det['motivo_justificacion'].'</td>';
            $html .= '</tr>';
        }
        
        
        $html .= '</table>';
        
        return $html;
    }
    
    private function consulta_detalle($modelParalelo, $modelBloque, $modelAlumno, $modelPeriodo){
        $con = Yii::$app->db;
        $query = "select 	d.codigo
                                ,d.nombre as detalle
                                ,n.observacion
                                ,a.fecha
                                ,h.nombre as hora
                                ,a.clase_id
                                ,m.name as materia
                                ,f.last_name
                                ,f.x_first_name
                                ,f.middle_name		
                                ,j.opcion_justificacion_id
                                ,j.motivo_justificacion
                                ,dju.nombre as detalle_justificacion
                                ,dju.codigo as cod_justi
                from	scholaris_asistencia_alumnos_novedades n 
                                inner join scholaris_grupo_alumno_clase g on g.id = n.grupo_id 
                                inner join scholaris_clase c on c.id = g.clase_id 
                                inner join scholaris_asistencia_profesor a on a.id = n.asistencia_profesor_id 
                                left join scholaris_asistencia_justificacion_alumno j on j.novedad_id = n.id 
                                left join scholaris_asistencia_comportamiento_detalle d on d.id = n.comportamiento_detalle_id
                                inner join scholaris_materia m on m.id = c.idmateria
                                inner join op_faculty f on f.id = c.idprofesor
                                inner join scholaris_horariov2_hora h on h.id = a.hora_id
                                left join scholaris_asistencia_comportamiento_detalle dju on dju.id = j.opcion_justificacion_id
                where	g.estudiante_id = $modelAlumno->id 
                                and c.periodo_scholaris = '$modelPeriodo->codigo'	
                                and a.fecha between '$modelBloque->bloque_inicia' and '$modelBloque->bloque_finaliza' 
                                and j.opcion_justificacion_id is null 
                union all 
select 	'atras'
	    ,'Atraso al inicio' as detalle
	    ,'' as observacion
	    ,a.fecha
	    ,'' as hora
	    ,0 as clase_id
	    ,'' as materia
	    ,'' as last_name
	    ,'' as x_first_name
	    ,'' as middle_name		
	    ,case 
	    	when d.atraso_justificado = true then 1
	    	else 0
	    end as atraso_justificado	    
	    ,d.atraso_observacion_justificacion  as motivo_justificacion
	    ,'' as detalle_justificacion
	    ,'' as cod_justi
from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
where	d.alumno_id = $modelAlumno->id
		and a.bloque_id = $modelBloque->id
		and d.atraso = true and d.atraso_justificado = false
union all 
select 	'atras'
	    ,'Atraso Justificado al inicio' as detalle
	    ,'' as observacion
	    ,a.fecha
	    ,'' as hora
	    ,0 as clase_id
	    ,'' as materia
	    ,'' as last_name
	    ,'' as x_first_name
	    ,'' as middle_name		
	    ,case 
	    	when d.atraso_justificado = true then 1
	    	else 0
	    end as atraso_justificado	    
	    ,d.atraso_observacion_justificacion  as motivo_justificacion
	    ,'' as detalle_justificacion
	    ,'' as cod_justi
from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
where	d.alumno_id = $modelAlumno->id
		and a.bloque_id = $modelBloque->id
		and d.atraso = false and d.atraso_justificado = true
union all 
select 	'faltas'
	    ,'Faltas al inicio' as detalle
	    ,'' as observacion
	    ,a.fecha
	    ,'' as hora
	    ,0 as clase_id
	    ,'' as materia
	    ,'' as last_name
	    ,'' as x_first_name
	    ,'' as middle_name		
	    ,case 
	    	when d.atraso_justificado = true then 1
	    	else 0
	    end as atraso_justificado	    
	    ,d.atraso_observacion_justificacion  as motivo_justificacion
	    ,'' as detalle_justificacion
	    ,'' as cod_justi
from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
where	d.alumno_id = $modelAlumno->id
		and a.bloque_id = $modelBloque->id
		and d.falta = true 
union all 
select 	'faltas'
	    ,'Faltas justificada al inicio' as detalle
	    ,'' as observacion
	    ,a.fecha
	    ,'' as hora
	    ,0 as clase_id
	    ,'' as materia
	    ,'' as last_name
	    ,'' as x_first_name
	    ,'' as middle_name		
	    ,case 
	    	when d.atraso_justificado = true then 1
	    	else 0
	    end as atraso_justificado	    
	    ,d.atraso_observacion_justificacion  as motivo_justificacion
	    ,'' as detalle_justificacion
	    ,'' as cod_justi
from 	scholaris_toma_asistecia_detalle d
		inner join scholaris_toma_asistecia a on a.id = d.toma_id
where	d.alumno_id = $modelAlumno->id
		and a.bloque_id = $modelBloque->id
		and d.falta_justificada = true ";
        
        $res = $con->createCommand($query)->queryAll();
        
        return $res;
    }

}