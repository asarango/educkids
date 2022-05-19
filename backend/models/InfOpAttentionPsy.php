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
class InfOpAttentionPsy extends \yii\db\ActiveRecord {

    private $attentionId;

    public function __construct($attentionId) {

        if (!isset(Yii::$app->user->identity->usuario)) {
            echo 'Su sesión expiró!!!';
            echo Html::a("Iniciar Sesión", ['site/index']);
            die();
        }
        
        $this->attentionId = $attentionId;


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
            'default_font' => 'dejavusans'
        ]);


        $cabecera = $this->genera_cabecera('{PAGENO}','{nb}');
        $mpdf->SetHtmlHeader($cabecera);
        $mpdf->showImageErrors = true;

        $html = $this->genera_cuerpo();

        $mpdf->WriteHTML($html);

        $mpdf->Output('AtencionPsicologica' . "curso" . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera($pagina, $totalPaginas) {

        $html = '';
        $html .= '<table class="tamano8" width="100%">';
        $html .= '<tr>';
        $html .= '<td align="left" width="15%"><img src="imagenes/instituto/logo/sellolibreta.jpeg" width="80px"></td>';
        
        $html .= '<td align="center" class="tamano12"></td>';
        
        $html .= '<td width="15%" align="left" class="tamano10">';
        $html .= '<p><strong>Código: </strong>ISMR20-08</p>';
        $html .= '<p><strong>Versión: </strong>2.0</p>';
        $html .= '<p><strong>Fecha: </strong>17/09/2018</p>';
        $html .= '<p><strong>Pág: </strong>'.$pagina.' / '.$totalPaginas.'</p>';
        $html .= '</td>';
        
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="15%">Proceso Académico</td>';
        $html .= '<td></td>';
        $html .= '<td width="15%"></td>';
        $html .= '</tr>';
        $html .= '</table>';

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
        $html .= '.conBordeAbajo{border-bottom: 0.1px solid black;}';
        $html .= '.centrarTexto{text-align: center;}';
        $html .= '</style>';

        $html .= '<br>';
        $html .= '<table class="tamano12" width="100%">';
        $html .= '<tr>';
        $html .= '<td width="20%"></td>';
        $html .= '<td width="" class="centrarTexto">';
        $html .= '<strong><p>ISM</p>';
        $html .= '<p>Intenational Scholastic model</p>';
        $html .= '<p>REGISTRO DE SEGUIMIENTO</p></strong>';
        $html .= '</td>';
        $html .= '<td width="20%"></td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';
        
        $modelAttention = OpPsychologicalAttention::findOne($this->attentionId);
        
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td width="25%"><strong>Nombres completos:</strong></td>';
        $html .= '<td width="25%"><strong>Nivel:</strong></td>';
        $html .= '<td width="25%"><strong>Paralelo:</strong></td>';
        $html .= '<td width="25%"><strong>Sección:</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>'.$modelAttention->student->last_name.' '.$modelAttention->student->first_name.' '.$modelAttention->student->middle_name.'</td>';
        
        isset($modelAttention->course->name) ? $courseName = $modelAttention->course->name : $courseName = 'No asignado';
        isset($modelAttention->parallel->name) ? $parallelName = $modelAttention->parallel->name : $parallelName = 'No asignado';
        isset($modelAttention->course->section0->name) ? $sectionName = $modelAttention->course->section0->name : $sectionName = 'No asignado';
        
        $html .= '<td>'.$courseName.'</td>';
        $html .= '<td>'.$parallelName.'</td>';
        $html .= '<td>'.$sectionName.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';
        
        
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBordeAbajo"><strong>Fecha</strong></td>';
        $html .= '<td class="conBordeAbajo"><strong>Detalle del Seguimiento</strong></td>';
        $html .= '<td class="conBordeAbajo" align="right"><strong>Departamento</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>'.$modelAttention->create_date.'</td>';
        $html .= '<td>'.$modelAttention->detail.'</td>';
        $html .= '<td>'.$modelAttention->departament->name.'</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';
        
        $modelAssistants = OpPsychologicalAttentionAsistentes::find()->where(['psychological_attention_id' => $this->attentionId])->all();
        
        $html .= '<p>ACCIONES Y/O ACUERDOS</p>';
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="conBordeAbajo"><strong>Fecha</strong></td>';
        $html .= '<td class="conBordeAbajo"><strong>Detalle de los acuerdos</strong></td>';
        $html .= '<td class="conBordeAbajo" align="right"><strong>Firmas/CI/Nombre/Parentesco</strong></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td>'.$modelAttention->create_date.'</td>';
        $html .= '<td>'.$modelAttention->agreements.'</td>';
        $html .= '<td>';
        foreach ($modelAssistants as $assistant){
            $html .= '<p>'.$assistant->name.'</p>';
        }
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';        
        
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>'.$modelAttention->persona_lidera.'</strong></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td width="30%"><strong>_________________________________________</strong></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>Firma y Nombre</strong></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td class="centrarTexto"><strong>(Persona que lidera la reunión)</strong></td>';
        $html .= '<td></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<br>';
        $html .= '<br>';
        $html .= '<br>';
        
        $html .= '<table class="tamano10" width="100%" cellspacing="0">';
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td class="centrarTexto"><img src="imagenes/instituto/logo/selloiso.jpeg" width="80px"></td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        
        
        return $html;
    }
    
    

    
}
