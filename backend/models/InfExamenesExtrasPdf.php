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
use yii\helpers\Html;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class InfExamenesExtrasPdf extends \yii\db\ActiveRecord {

    private $periodoId;
    private $periodoCodigo;
    private $cursoId;
    private $modelCurso;
    private $tituloReporte;
    private $usuario;
    private $tipoCalificacion;
    private $notaMinima;
    private $notaRemedial;
    private $imprimeCedulas;


    public function __construct($cursoId, $conCedulas) {

        if (!isset(Yii::$app->user->identity->usuario)) {
            echo 'Su sesión expiró!!!';
            echo Html::a("Iniciar Sesión", ['site/index']);
            die();
        }

        $this->cursoId = $cursoId;
        $this->modelCurso = OpCourse::findOne($this->cursoId);
        $this->titulo_reporte(); //coloca el nombre del titulo del reporte
        $this->usuario = \Yii::$app->user->identity->usuario;
        
        $this->imprimeCedulas = $conCedulas;
        
        $modelNotaMinima = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaminima'])->one();
        $this->notaMinima = $modelNotaMinima->valor;
        
        $modelNotaRemedial = ScholarisParametrosOpciones::find()->where(['codigo' => 'notaRemed'])->one();
        $this->notaRemedial = $modelNotaRemedial->valor;
        
        /*         * * para periodo ** */
        $this->periodoId = Yii::$app->user->identity->periodo_id;
        $modelPeriodo = ScholarisPeriodo::findOne($this->periodoId);
        $this->periodoCodigo = $modelPeriodo->codigo;
        ///// fin de periodo /////
        
        /** tipo de calificacion * */
        $modelTipoCalificacion = ScholarisTipoCalificacionPeriodo::find()->where(['scholaris_periodo_id' => $this->periodoId])->one();
        $this->tipoCalificacion = $modelTipoCalificacion->codigo;
        ///// fin de tippo de calificacion /////

        $this->genera_reporte_pdf();
    }

    private function titulo_reporte() {

        $this->tituloReporte = 'EXAMENES EXTRAS';
    }

    private function genera_reporte_pdf() {
        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 30,
            'margin_bottom' => 10,
            'margin_header' => 3,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera();
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->genera_cuerpo();

        $mpdf->WriteHTML($html);

        $mpdf->Output('Examenes-Extras' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera() {

        $html = '';
        $html .= '<table class="tamano10" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="20%"><img src="imagenes/instituto/logo/logo2.png" width="80px"></td>';
        $html .= '<td align="center" class="tamano12">';
        $html .= '<strong>' . $this->modelCurso->xInstitute->name . '</strong><br>';
        $html .= '<strong>AÑO LECTIVO: </strong>2020-2021<br>';
        $html .= '<strong>INFORME ' . $this->tituloReporte . '</strong>';
        $html .= '</td>';
        $html .= '<td width="20%" align="right" class="tamano10">';

        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<hr>';

        return $html;
    }

    private function genera_cuerpo() {

        $html = '';
        $html .= '<style>';
        $html .= '.bordesolido{border: 0.2px solid black;}';
        $html .= '.tamano10{font-size:10px;}';
        $html .= '.tamano12{font-size:12px;}';
        $html .= '.tamano8{font-size:8px;}';
        $html .= '.tamano6{font-size:6px;}';
        $html .= '.conBorde{border: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '</style>';

        $paralelos = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','1','2','3','4','5','6','7','8','9','10');
        $modelParalelos = OpCourseParalelo::find()
                ->where(['course_id' => $this->cursoId])
                ->andWhere(['in', 'name', $paralelos])
                ->orderBy('name')
                ->all();

        foreach ($modelParalelos as $paralelo) {
            $html .= '<p class="tamano10"><strong>CURSO:</strong>';
            $html .= $paralelo->course->name . ' - ' . $paralelo->name . '</p>';

            $html .= '<table style="font-size: 10px;" cellspacing="0" cellpadding="2" width="100%">';
            $html .= '<tr>';
            $html .= '<td class="conBorde centrarTexto"><strong>#</strong></td>';
            
            if($this->imprimeCedulas == 'NO'){
                $html .= '<td class="conBorde centrarTexto" width="50%"><strong>ESTUDIANTE</strong></td>';
            }
            
            $html .= '<td class="conBorde centrarTexto"><strong>CÉDULA</strong></td>';            
            $html .= '<td class="conBorde centrarTexto" width="30%"><strong>ASIGNATURA</strong></td>';
            $html .= '<td class="conBorde centrarTexto"><strong>PROMEDIO</strong></td>';
            $html .= '<td class="conBorde centrarTexto"><strong>OBSERVACIÓN</strong></td>';

            $html .= '</tr>';

            
            
            $html .= $this->detalle_alumnos($paralelo->id);


            $html .= '</table>';
        }

        return $html;
    }    

    

    private function detalle_alumnos($paraleloId) {

        $html = '';

        if ($this->tipoCalificacion == 0) {
            $sentenciasNotasAlumnos = new AlumnoNotasNormales();
        } elseif ($this->tipoCalificacion == 2) {
            $sentenciasNotasAlumnos = new AlumnoNotasDisciplinar();
        } elseif ($this->tipoCalificacion == 3) {
            $sentenciasNotasAlumnos = new AlumnoNotasInterdisciplinar();
        } else {
            echo 'No tiene creado un tipo de calificación para esta institución!!!';
            die();
        }
        
        $modelAlumnos = $this->consulta_grupos_id($paraleloId);
        
        $i=0;
        
        foreach ($modelAlumnos as $alumno){
            $notaMateria = $sentenciasNotasAlumnos->get_nota_materia($alumno['grupo_id']);
            
            isset($notaMateria['final_ano_normal']) ? $notaAnoFinal = $notaMateria['final_ano_normal'] : $notaAnoFinal = 0;
            
            if($notaAnoFinal < $this->notaMinima){
                
                $validacion = $this->valida_nota_final($notaAnoFinal);
                
                $i++;
                $html .= '<tr>';
                $html .= '<td class="conBorde">' . $i . '</td>';
                if($this->imprimeCedulas == 'NO'){
                    $html .= '<td class="conBorde">' . $alumno['last_name']. ' '. $alumno['first_name'] .' '. $alumno['middle_name'] . '</td>';
                }               
                
                $html .= '<td class="conBorde centrarTexto">' . $alumno['numero_identificacion'] . '</td>';
                
                
                $html .= '<td class="conBorde">' . $alumno['materia'] . '</td>';
                $html .= '<td class="conBorde centrarTexto">' . $notaAnoFinal . '</td>';
                $html .= '<td class="conBorde centrarTexto">' . $validacion . '</td>';
                $html .= '</tr>';
            }
            
            
        }



        return $html;
    }

    private function valida_nota_final($notaFInal){
        if(($notaFInal < $this->notaMinima) && ($notaFInal >= $this->notaRemedial)){
            return 'SUPLETORIO';
        }else{
            return 'REMEDIAL';
        }
        
    }
    
    private function consulta_grupos_id($paraleloId){
        $con = Yii::$app->db;
        $query = "select 	g.id as grupo_id
		,s.last_name, s.first_name, s.middle_name 
                ,p.numero_identificacion
                ,m.name as materia
from 	scholaris_grupo_alumno_clase g  
		inner join op_student_inscription i on i.student_id = g.estudiante_id 
		inner join scholaris_op_period_periodo_scholaris sop on sop.op_id = i.period_id 
		inner join op_student s on s.id = i.student_id
		inner join scholaris_clase cla on cla.id = g.clase_id 
		inner join scholaris_malla_materia mm on mm.id = cla.malla_materia 
                inner join scholaris_materia m on m.id = mm.materia_id 
                inner join res_partner p on p.id = s.partner_id
where 	i.parallel_id = $paraleloId
		and sop.scholaris_id = $this->periodoId
		and i.inscription_state = 'M'
		and mm.tipo in ('NORMAL','OPTATIVAS') 
                and cla.periodo_scholaris = '$this->periodoCodigo'
order by s.last_name, s.first_name, s.middle_name ;";
        $res = $con->createCommand($query)->queryAll();
        return $res;
    }
    
    

}
