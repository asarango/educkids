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
class MecIsmNominaMatriculados2 extends \yii\db\ActiveRecord {

    private $paralelo;
    private $modelParalelo;
    private $periodoId;
    private $periodoCodigo;
    private $modelAlumnos;
    

    public function __construct($paralelo) {
        $this->paralelo = $paralelo;

        /*         * ** Periodo actual ** */
        $this->periodoId = \Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// FIN DE PERIODO

    

        $sentencias = new SentenciasAlumnos();
        $this->modelParalelo = OpCourseParalelo::findOne($paralelo);
    
        // toma estudiantes del paralelo
        $this->modelAlumnos = $sentencias->get_alumnos_paralelo_todos($paralelo); 
        
        $this->genera_reporte_pdf();
    }

    

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 30,
            'margin_right' => 10,
            'margin_top' => 25,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
            'default_font' => 'arial'
        ]);


        $cabecera = $this->genera_cabecera();
//        $pie = $this->genera_pie_pdf();

        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;


        $html = $this->genera_cuerpo('NÓMINA DE ESTUDIANTES MATRICULADOS');
        $mpdf->WriteHTML($html);


        $mpdf->Output('MEC-Matriculados' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {
        
        $modelBanner = ScholarisImagenes::find()->where(['codigo' => 'banner2sellos'])->one();
        if(isset($modelBanner->nombre_archivo)){
            $banner = 'imagenesEducandi/'.$modelBanner->nombre_archivo;
        }else{
            $banner = 'imagenesEducandi/noimagen.png';
        }
        
        $html = '';
        $html .= '<img src="'.$banner.'">';
        
        return $html;
    }

    private function genera_cuerpo($titulo) {
        
        $modelParalelo = OpCourseParalelo::findOne($this->paralelo);

        $html = '';

        $html .= '<style>';
        '.rotar90{font-size:30px;text-rotate="45"}';
        $html .= 'td {
                    border-collapse: collapse;
                    border: 1px black solid;
                  }
                  tr:nth-of-type(5) td:nth-of-type(1) {
                    visibility: hidden;
                  }
                  .rotate {
                    /* FF3.5+ */
                    -moz-transform: rotate(-90.0deg);
                    /* Opera 10.5 */
                    -o-transform: rotate(-90.0deg);
                    /* Saf3.1+, Chrome */
                    -webkit-transform: rotate(-90.0deg);
                    /* IE6,IE7 */
                    filter: progid: DXImageTransform.Microsoft.BasicImage(rotation=0.083);
                    /* IE8 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)";
                    /* Standard */
                    transform: rotate(-90.0deg);
                  }';
        $html .= '.bordesolido{border: 0px solid #fff;}';
        $html .= '.tamano12{font-size:12px;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '.arial{font-family: Arial;}';
        $html .= '</style>';

//        $html .= '<table style="font-size:16px" width="100%">';
//        
//        $html .= '<tr>';
//        $html .= '<td align="left" width="10%"></td>';
//
//        $html .= '<td class="centrarTexto" style="font-size:16px">';
//        $html .= '<strong>' . $modelParalelo->course->xInstitute->name . '</strong>';
//        $html .= '</td>';
//
//        $html .= '<td align="right" width="10%"></td>';
//        $html .= '</tr>';
//        $html .= '</table>';
        

        $html .= '<table class="tamano12 centrarTexto" width="100%">';
        $html .= '<tr>';
        $html .= '<td>';
        $html .= '<strong>' . $titulo . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO ' . $this->periodoCodigo . '</strong><br><br>';
        $html .= '<strong>' . $this->modelParalelo->course->xTemplate->name.' '.$this->modelParalelo->name .'</strong><br>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';

        $html .= $this->procesa_nomina();

        return $html;
    }


    private function procesa_nomina() {

        $html = '';

        $html .= '<br><br><br>';
        
        
        $html .= '<table width="100%" cellspacing="0" cellpadding="2" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td rowspan="" class="bordesolido centrarTexto"><strong>No.</strong></td>';
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>FOLIO</strong></td>';
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>CÓDIGO</strong></td>';
        $html .= '<td rowspan="" class="bordesolido centrarTexto" width="250px"><strong>APELLIDOS Y NOMBRES</strong></td>';        
        $html .= '<td colspan="" class="bordesolido centrarTexto"><strong>OBSERVACIONES</strong></td>';
        $html .= '</tr>';

        $html .= $this->lista_estudiantes();


        $html .= '</table>';
        
        
//        $html.= $this->firmas1();
        
        return $html;
    }
    
    public function lista_estudiantes(){
        $html = '';
        
        $i=0;
        foreach ($this->modelAlumnos as $alumno){
            $i++;
            $html .= '<tr>';            
            $html .= '<td rowspan="" class="bordesolido centrarTexto">'.$i.'</td>';
            
            $matricula = $this->toma_matricula_estado($alumno['id']);
            
            isset($matricula['matricula'])      ? $matri = $matricula['matricula']      : $matri = '-';
            isset($matricula['matricula_id'])   ? $matId = $matricula['matricula_id']   : $matId = '-';
            
            $html .= '<td class="bordesolido centrarTexto">'.$matri .'</td>';
            $html .= '<td class="bordesolido centrarTexto">'.$matId .'</td>';
            
            $html .= '<td rowspan="" class="bordesolido">'.$alumno['last_name'].' '.$alumno['first_name'].' '.$alumno['middle_name'] .'</td>';
            
            
            isset($matricula['inscription_state']) ? $inscription = $matricula['inscription_state'] : $inscription = 'R';
            
            if($inscription == 'M'){
                $observacion = '';
            }else if($inscription == 'R'){
                $observacion = 'RETIRADO';
            }
            
            $html .= '<td class="bordesolido centrarTexto">'.$observacion .'</td>';
            
            $html .= '</tr>';
        }
        
        return $html;
    }
    
    
    public function toma_matricula_estado($alumnoId){
        $con = \Yii::$app->db;
        $query = "select 	substring(e.name,4)  as matricula
		,i.inscription_state 
                ,e.id as matricula_id
from 	op_student_inscription i
		inner join op_student_enrollment e on e.inscription_id = i.id
		inner join op_period op on op.id = i.period_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = op.id 
		inner join scholaris_periodo p on p.id = sop.scholaris_id 
where	i.student_id = $alumnoId
		and p.id = $this->periodoId;";
        
//        echo $query;
//        die();
        
        $res = $con->createCommand($query)->queryOne();
        return $res;
        
    }

public function firmas1(){
    
        $instituto = \Yii::$app->user->identity->instituto_defecto;
    
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'codigo_reporte' => 'MEC',
            'template_id' => $this->modelParalelo->course->xTemplate->id,
            'instituto_id' => $instituto
        ])->one();
        
        
      
    
    
        $html = '';
        $html .= '<br><br><br>';
        $html .= '<table width="100%" cellspacing="0" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>__________________________________</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_nombre . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_nombre . '</strong></td>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td align="center"><strong>' . $modelFirmas->principal_cargo . '</strong></td>';
        $html .= '<td></td>';
        $html .= '<td align="center"><strong>' . $modelFirmas->secretaria_cargo . '</strong></td>';
        $html .= '</tr>';

        $html .= '</table>';


        return $html;
    }


}
