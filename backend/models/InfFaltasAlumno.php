<?php

namespace backend\models;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class InfFaltasAlumno extends \yii\db\ActiveRecord {

    private $alumnoId;
    private $parcialId;
    private $periodoId;
    private $periodoCodigo;
    private $paraleloId;
    private $modelAlumno;

    public function __construct($alumno, $parcial) {
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        
        $this->alumnoId = $alumno;
        $this->parcialId = $parcial;
        
        $this->consulta_paralelo_alumno();

        $sentencias = new SentenciasAlumnos();
        
        $this->modelAlumno = $sentencias->get_alumnos_paralelo_alumno($this->paraleloId, $this->alumnoId);
        
        
        $this->genera_reporte_pdf();
    }

    
    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 3,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
//        $mpdf->showImageErrors = true;


            $html = $this->genera_cuerpo();
            $mpdf->WriteHTML($html);
//            $mpdf->addPage();
        

        $mpdf->SetFooter($pie);

        $mpdf->Output('Reporte-Faltas-Atraso' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        $modelParalelo = OpCourseParalelo::findOne($this->paraleloId);

        $html = '';
        $html .= '<table style="font-size:12px" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="10%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';

        $html .= '<td class="centrarTexto">';
        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>' . $this->periodoCodigo . '<br>';
        $html .= '<strong>REPORTE PRIMER QUIMESTRE</strong><br>';
        $html .= '</td>';

        $html .= '<td align="right" width="10%">';

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
//        $html .= '<hr>';

        return $html;
    }
    
    private function genera_pie_pdf(){
        
        $usuario = \Yii::$app->user->identity->usuario;
        $modelUser = ResUsers::find()->where(['login' => $usuario])->one();
        $hoy = date('Y-m-d H:i:s');
        
        $html = '';
        $html .= '<table class="tamano8" width="100%">';
        $html .= '<tr>';
        $html .= '<td><strong>REALIZADO POR EL USUARIO: </strong>' . $modelUser->partner->name . '</td>';
        $html .= '<td align="right"><strong>FECHA: </strong>' . $hoy. '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        return $html;
    }
    
    
    private function genera_cuerpo() {
        $modelParalelo = OpCourseParalelo::findOne($this->paraleloId);

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid #ccc;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';
        
        

        $html .= '<table class="tamano8" width="100%">';
        $html .= '<tr>';
        $html .= '<td><strong>ESTUDIANTE: </strong>' . $this->modelAlumno[0]['last_name'] . ' ' . $this->modelAlumno[0]['first_name'] . ' ' . $this->modelAlumno[0]['middle_name'] . '</td>';
        $html .= '<td align="right"><strong>CURSO: </strong>' . $modelParalelo->course->name . '"' . $modelParalelo->name . '"</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';
        $html .= '<br>';

        
        $html .= $this->html_tabla($this->alumnoId, $this->parcialId);
        
        
        return $html;
    }

    
    
    
    private function html_tabla($alumnoId, $parcialId) {
        
        $html = '';

        
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="bordesolido centrarTexto" width="30%"><strong>FECHA</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>ATRASO</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>ATRASO J</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>FALTA</strong></td>';
        $html .= '<td class="bordesolido centrarTexto"><strong>FALTA J</strong></td>';
        $html .= '</tr>';


        if ($parcialId == 'todos') {
            
            $data = $this->consulta_datos_todos($alumnoId);
        } else {
            $data = $this->consulta_datos_parcial($alumnoId, $parcialId);
        }


        foreach ($data as $dat) {
            $html .= '<tr>';
            $html .= '<td class="bordesolido centrarTexto">' . $dat['fecha'] . '</td>';
            $html .= '<td class="bordesolido centrarTexto">' . $dat['atraso'] . '</td>';
            $html .= '<td class="bordesolido centrarTexto">' . $dat['atraso_justificado'] . '</td>';
            $html .= '<td class="bordesolido centrarTexto">' . $dat['falta'] . '</td>';
            $html .= '<td class="bordesolido centrarTexto">' . $dat['falta_justificada'] . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';
        

        return $html;
    }

    private function consulta_datos_parcial($alumnoId, $parcialId) {

        $con = Yii::$app->db;
        $query = "select 	a.fecha, d.atraso, d.atraso_justificado, d.falta, d.falta_justificada 
                    from	scholaris_toma_asistecia a
                                    inner join scholaris_toma_asistecia_detalle d on d.toma_id = a.id 
                    where	bloque_id = $parcialId
                                    and d.alumno_id = $alumnoId
                                    and (atraso = true
                                    or atraso_justificado = true
                                    or falta = true
                                    or falta_justificada = true)
                    order by a.fecha asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }

    private function consulta_datos_todos($alumnoId) {
        
        $con = Yii::$app->db;
        $query = "select 	a.fecha, d.atraso, d.atraso_justificado, d.falta, d.falta_justificada 
from	scholaris_toma_asistecia a
		inner join scholaris_toma_asistecia_detalle d on d.toma_id = a.id
		inner join scholaris_bloque_actividad b on b.id = a.bloque_id 
where	d.alumno_id = $alumnoId
		and (atraso = true
		or atraso_justificado = true
		or falta = true
		or falta_justificada = true)
		and b.scholaris_periodo_codigo = '$this->periodoCodigo'
		and b.tipo_uso = '$this->uso'
order by a.fecha asc;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    
    
    private function consulta_paralelo_alumno(){
        
        
        $con = \Yii::$app->db;
        $query = "select 	i.parallel_id 
                    from 	op_student_inscription i 
                                    inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id
                    where 	sop.scholaris_id = $this->periodoId
                                    and i.student_id = $this->alumnoId;";
        
        
        $res = $con->createCommand($query)->queryOne();
        
        
        $this->paraleloId = $res['parallel_id'];
        
    }
    
    
    
    

}
