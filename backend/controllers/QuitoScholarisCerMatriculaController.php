<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Mpdf\Mpdf;
use backend\models\OpStudentInscription;
use backend\models\OpStudent;
use backend\models\OpCourseParalelo;
use backend\models\OpStudentEnrollment;

/**
 * ScholarisRepLibretaController implements the CRUD actions for ScholarisRepLibreta model.
 */
class QuitoScholarisCerMatriculaController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ScholarisRepLibreta models.
     * @return mixed
     */
    public function actionIndex() {

        $usuario = \Yii::$app->user->identity->usuario;
        $paralelo = $_GET['paralelo'];
        $alumno = $_GET['alumno'];

        if ($alumno) {
            $modelAlumno = OpStudent::find()
                    ->select(['op_student.id',
                        "concat(op_student.last_name, ' ',op_student.first_name, ' ',op_student.middle_name) as last_name",
                        "op_student_inscription.id as ins_id"
                    ])
                    ->innerJoin("op_student_inscription", "op_student.id = op_student_inscription.student_id")
                    ->where(['op_student.id' => $alumno, 'op_student_inscription.inscription_state' => 'M'])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name, ")
                    ->all();
        } else {
            $modelAlumno = OpStudent::find()
                    ->select(['op_student.id',
                        "concat(op_student.last_name, ' ',op_student.first_name, ' ',op_student.middle_name) as last_name",
                        "op_student_inscription.id as ins_id",
                        "op_student.gender as gender"
                    ])
                    ->innerJoin("op_student_inscription", "op_student.id = op_student_inscription.student_id")
                    ->where(['op_student_inscription.parallel_id' => $paralelo, 'op_student_inscription.inscription_state' => 'M'])
                    ->orderBy("op_student.last_name, op_student.first_name, op_student.middle_name, ")
                    ->all();
        }



        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4-P',
            'margin_left' => 30,
            'margin_right' => 30,
            'margin_top' => 30,
            'margin_bottom' => 0,
            'margin_header' => 5,
            'margin_footer' => 5,
        ]);


        $cabecera = $this->genera_cabecera_pdf();
        $pie = $this->genera_pie_pdf();


        $mpdf->SetHtmlHeader($cabecera);
//        $mpdf->SetHeader($cabecera);
        
        $mpdf->showImageErrors = true;


        foreach ($modelAlumno as $data) {

            $html = $this->genera_cuerpo_pdf($data, $paralelo);

            $mpdf->WriteHTML($html, $this->renderPartial('mpdf'));
            $mpdf->addPage();
        }

        $mpdf->SetFooter($pie);

        $mpdf->Output('Certificado_Matricula' . '.pdf', 'D');
        exit;
    }

    private function genera_cabecera_pdf() {
        $cab = '<div align="center"><img src="imagenes/instituto/logo/logoantiguomec.jpg" width="200"></div>';
        return $cab;
    }

    private function genera_pie_pdf() {
        $fecha = date("Y-m-d");
        $usuario = \Yii::$app->user->identity->usuario;

        $html = '';
        $html .= '<strong>Elaborado por: </strong>' . $usuario . ', el: ' . $fecha;

        return $html;
    }

    private function genera_cuerpo_pdf($alumno, $paralelo) {
        
        $sentencias = new \backend\models\SentenciasMatriculas();
        
        if($alumno->gender == 'f'){
            $gen = 'la';
        }else{
            $gen = 'el';
        }       
        
        
        
        $periodoId = \Yii::$app->user->identity->periodo_id;
        $instituto = \Yii::$app->user->identity->instituto_defecto;
        
        $ciudad = $sentencias->get_ciudad($instituto);
        
        $modelPeriodo = \backend\models\ScholarisPeriodo::findOne($periodoId);

        $modelParalelo = OpCourseParalelo::find()
                ->where(['id' => $paralelo])
                ->one();
        
        $datoMatricula = $this->toma_fecha_matricula($alumno, $paralelo);
        $fechaHoy = date("Y-m-d");
        
        isset($datoMatricula['create_date']) ? $fechaMatricula = $datoMatricula['create_date'] : $fechaMatricula = $fechaHoy;
        
        $fechaMatricula = $this->obtenerFechaEnLetra($fechaMatricula);
        
        $hoy = $this->obtenerFechaEnLetra($fechaHoy);

        
        $modelInscripcion = OpStudentInscription::find()->where([
            'student_id' => $alumno->id,
            'parallel_id' => $paralelo
        ])->one();
        
        $modelMatricula = OpStudentEnrollment::find()->where(['inscription_id' => $modelInscripcion->id])->one();

        $html = '';
        $html .= '<style>';
        $html .= '.centrarTexto {
                    text-align: center;
                  }
                  .derechaTexto {
                    text-align: right;
                  }
                  
                  .tamano14{
                    font-size: 14px;
                  }
                  
                  .tamano12{
                    font-size: 12px;
                  }
                  
.tamano10{
                    font-size: 12px;
                  }
                  
                 ';
        $html .= '</style>';
        
        $html .= '<p class="centrarTexto"><strong>'.$modelParalelo->course->xInstitute->name.'</strong></p>';
        $html .= '<p class="centrarTexto"><strong>Quito - Ecuador</strong></p>';

        //$html .= '<p class="centrarTexto tamano12"><strong>SECRETARIA GENERAL</strong></p>';
        //$html .= '<p class="centrarTexto tamano12"></p>';
        //$html .= '<p class="centrarTexto tamano12"></p>';
        $html .= '<p class="centrarTexto tamano12"><strong>AÑO LECTIVO: '.$modelPeriodo->codigo.'</strong></p>';
        $html .= '<p class="centrarTexto tamano12"><strong>RÉGIMEN SIERRA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JORNADA MATUTINA</strong></p>';
        $html .= '<p class="centrarTexto tamano12"><strong>CERTIFICADO DE MATRÍCULA</strong></p>';
        

        
        //$html .= '<p class="centrarTexto">ROSA DE JESÚS CORDERO</p>';
//        $html .= '<p class="centrarTexto tamano14"><strong>CERTIFICA:</strong></p>';
        $html .= '<br><br>';
        
        $html .= '<p>Quien subscribe la Rectora y Secretaria de la Institución, previo cumplimiento de las respectivas '
                . 'disposiciones de la Ley Orgánica de Educación Cultural.</p>'
                . '<p>CERTIFICAN QUE en los archivos de la Secretaría reposa la siguiente MATRÍCULA:</p>';
        
        
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<td>ESTUDIANTE:</td>';
        $html .= '<td>'.$alumno->last_name.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td>'.$modelParalelo->course->xTemplate->name.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td></td>';
        $html .= '<td>PARALELO: '.$modelParalelo->name.'</td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        
        $html .= '<table width="100%">';
        $html .= '<tr>';
        
        isset($modelMatricula->name) ? $matricula = $modelMatricula->name : $matricula = '-';
        
        $html .= '<td>MATRÍCULA N° :'.substr($matricula,3).'</td>';
        $html .= '<td width="10%"></td>';
        $html .= '<td>FECHA DE MATRÍCULA :'.$fechaMatricula.'</td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td>FOLIO N° :'.substr($matricula,3).'</td>';
        $html .= '<td></td>';
        $html .= '</tr>';
        
        
        $html .= '</table>';
        
         $html .= '<br><br>';
        $html .= '<p class=""><strong>Distrito Metropolitano de Quito el ' . $fechaMatricula . '</strong>.</p>';

        $html .= '<br><br><br><br>';

        $template = $modelParalelo->course->xTemplate->id;
        
        $modelFirmas = \backend\models\ScholarisFirmasReportes::find()->where([
            'template_id' => $template,
            'instituto_id' => $instituto
        ])->one();
        
        $html .= '<table width="100%" class="tamano10">';
        $html .= '<tr>';
        $html .= '<td width="40%" align="center"><strong>_____________________________________</strong></td>';
        $html .= '<td><strong></strong></td>';
        $html .= '<td width="40%" align="center"><strong>_____________________________________</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="40%" align="center"><strong>'.$modelParalelo->institute->rector.'</strong></td>';
        $html .= '<td><strong></strong></td>';
        $html .= '<td width="40%" align="center"><strong>'.$modelParalelo->institute->secretario.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '<tr>';
        $html .= '<td width="40%" align="center"><strong>'.$modelFirmas->principal_cargo.'</strong></td>';
        $html .= '<td><strong></strong></td>';
        $html .= '<td width="40%" align="center"><strong>'. $modelFirmas->secretaria_cargo.'</strong></td>';
        $html .= '</tr>';
        
        $html .= '</table>';
        
        

        return $html;
    }

    private function toma_fecha_matricula($alumno, $paralelo) {
        $modelAlumno = OpStudentInscription::find()
                ->where(['parallel_id' => $paralelo, 'student_id' => $alumno->id])
                ->one();

        $modelMatricula = OpStudentEnrollment::find()
                ->where(['inscription_id' => $modelAlumno->id])
                ->one();

        return $modelMatricula;
    }

    private function obtenerFechaEnLetra($fecha) {
        //$dia= conocerDiaSemanaFecha($fecha);
        $num = date("j", strtotime($fecha));
        $anno = date("Y", strtotime($fecha));
        $mes = array('enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
        $mes = $mes[(date('m', strtotime($fecha)) * 1) - 1];
        //return $dia.', '.$num.' de '.$mes.' del '.$anno;
        return $num . ' de ' . $mes . ' de ' . $anno;
    }

}
